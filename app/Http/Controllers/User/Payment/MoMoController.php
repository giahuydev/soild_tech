<?php

namespace App\Http\Controllers\User\Payment;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MoMoController extends Controller
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $redirectUrl;
    private $ipnUrl;

    public function __construct()
    {
        $this->endpoint = config('services.momo.endpoint');
        $this->partnerCode = config('services.momo.partner_code');
        $this->accessKey = config('services.momo.access_key');
        $this->secretKey = config('services.momo.secret_key');
        $this->redirectUrl = config('services.momo.redirect_url');
        $this->ipnUrl = config('services.momo.ipn_url');
    }

    /**
     * Hiển thị trang checkout
     */
    public function showCheckout()
    {
        $cart = Cart::with('items.variant.product')->where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        
        $cartItems = $cart->items;
        $totalPrice = $this->calculateCartTotal($cartItems);
        
        return view('user.payment.checkout', compact('cartItems', 'totalPrice'));
    }

    /**
     * Tính tổng tiền giỏ hàng
     */
    private function calculateCartTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $product = $item->variant->product;
            $price = $product->price_sale ?? $product->price;
            $total += $price * $item->quantity;
        }
        return $total;
    }

    /**
     * Xử lý thanh toán
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'user_name'      => 'required|string|max:255',
            'user_phone'     => 'required|string|max:20',
            'user_email'     => 'required|email',
            'user_address'   => 'required|string',
            'payment_method' => 'required|in:cod,momo',
        ]);

        $cart = Cart::with('items.variant.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $cartItems = $cart->items;
        $totalPrice = $this->calculateCartTotal($cartItems);

        if ($totalPrice < 1000) {
            return back()->with('error', 'Số tiền thanh toán tối thiểu là 1,000đ!');
        }

        if ($totalPrice > 50000000) {
            return back()->with('error', 'Số tiền thanh toán vượt quá giới hạn 50,000,000đ!');
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'                => Auth::id(),
                'user_name'              => $request->user_name,
                'user_email'             => $request->user_email,
                'user_phone'             => $request->user_phone,
                'user_address'           => $request->user_address,
                'user_note'              => $request->user_note,
                'total_price'            => $totalPrice,
                'status_order'           => 'pending',
                'status_payment'         => 'unpaid',
                'is_ship_user_same_user' => true,
            ]);

            foreach ($cartItems as $item) {
                $product = $item->variant->product;
                $price = $product->price_sale ?? $product->price;

                OrderItem::create([
                    'order_id'              => $order->id,
                    'product_variant_id'    => $item->product_variant_id,
                    'product_name'          => $product->name,
                    'product_sku'           => $product->sku ?? 'N/A',
                    'product_img_thumbnail' => $product->img_thumbnail,
                    'product_price'         => $price,
                    'variant_size_name'     => $item->variant->size ?? '',
                    'variant_color_name'    => $item->variant->color ?? '',
                    'quantity'              => $item->quantity,
                    'item_total'            => $price * $item->quantity,
                    'status'                => 'pending',
                ]);

                $item->variant->decrement('quantity', $item->quantity);
            }

            DB::commit();

            if ($request->payment_method === 'momo') {
                // MoMo sẽ gửi email sau khi thanh toán thành công (trong callback)
                return $this->createMoMoPayment($order);
            } else {
                // COD: Gửi email ngay lập tức
                $this->sendOrderConfirmationEmail($order);
                
                // Xóa giỏ hàng
                $cart->items()->delete();
                
                return redirect()->route('payment.success', $order->id)
                    ->with('success', 'Đặt hàng thành công! Chúng tôi đã gửi email xác nhận đến ' . $order->user_email);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
        }
    }

    /**
     * Gửi email xác nhận đơn hàng
     */
    private function sendOrderConfirmationEmail($order)
    {
        try {
            // Load relationship để tránh N+1 query
            $order->load('orderItems');
            
            // Gửi email
            Mail::to($order->user_email)->send(new OrderPlaced($order));
            
            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'email' => $order->user_email
            ]);
            
        } catch (\Exception $e) {
            // Không throw exception để không ảnh hưởng đến flow đặt hàng
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'email' => $order->user_email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tạo thanh toán MoMo
     */
    private function createMoMoPayment($order)
    {
        $orderId = 'SOLID_' . time() . '_' . $order->id;
        $requestId = 'REQ_' . time() . '_' . uniqid();
        
        $amount = (int) round($order->total_price);
        $amountString = (string) $amount;
        
        $orderInfo = 'Order #' . $order->id;
        $extraData = '';
        $requestType = 'captureWallet';
        
        $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amountString .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $this->ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $this->redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;
        
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'SOLID TECH',
            'storeId' => 'SolidTechStore',
            'requestId' => $requestId,
            'amount' => $amountString,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl' => $this->ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];
        
        Log::info('MoMo Request', [
            'order_id' => $order->id,
            'momo_order_id' => $orderId,
            'amount' => $amountString,
        ]);
        
        $order->update([
            'order_id' => $orderId,
            'request_id' => $requestId,
            'order_info' => $orderInfo,
        ]);
        
        $result = $this->sendMoMoRequest($data);
        
        Log::info('MoMo Response', $result);
        
        if (isset($result['payUrl']) && !empty($result['payUrl'])) {
            return redirect($result['payUrl']);
        }
        
        Log::error('MoMo Failed', $result);
        
        return redirect()->route('payment.failed', $order->id)
                    ->with('error', 'Không thể kết nối MoMo. Vui lòng chọn phương thức khác!');
    }

    /**
     * Gửi request đến MoMo
     */
    private function sendMoMoRequest($data)
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('cURL Error: ' . $error);
            return ['error' => $error];
        }
        
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    /**
     * Callback từ MoMo
     */
    public function callback(Request $request)
    {
        Log::info('MoMo Callback', $request->all());

        $momoOrderId = $request->orderId;
        $resultCode = $request->resultCode;

        $parts = explode('_', $momoOrderId);
        $realOrderId = end($parts);

        $order = Order::find($realOrderId);

        if (!$order) {
            Log::error('Order not found', ['orderId' => $realOrderId]);
            return redirect('/')->with('error', 'Không tìm thấy đơn hàng!');
        }

        if ($resultCode == 0) {
            // ✅ Thanh toán thành công
            $order->update([
                'status_order'   => 'pending',
                'status_payment' => 'paid',
                'trans_id'       => $request->transId,
                'response_data'  => json_encode($request->all()),
            ]);
            
            // Xóa giỏ hàng
            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                $cart->items()->delete();
            }
            
            // ✅ GỬI EMAIL XÁC NHẬN ĐƠN HÀNG
            $this->sendOrderConfirmationEmail($order);
            
            Log::info('Payment Success', ['order_id' => $order->id]);
            
            return redirect()->route('payment.success', $order->id)
                ->with('success', 'Thanh toán thành công! Chúng tôi đã gửi email xác nhận đến ' . $order->user_email);
        } else {
            // ❌ Thanh toán thất bại - hoàn lại số lượng
            foreach ($order->orderItems as $orderItem) {
                $orderItem->variant->increment('quantity', $orderItem->quantity);
            }
            
            $order->update([
                'status_order'   => 'cancelled',
                'status_payment' => 'failed',
                'response_data'  => json_encode($request->all()),
            ]);
            
            Log::warning('Payment Failed', [
                'order_id' => $order->id,
                'result_code' => $resultCode
            ]);
            
            return redirect()->route('payment.failed', $order->id);
        }
    }

    /**
     * IPN từ MoMo
     */
    public function ipn(Request $request)
    {
        Log::info('MoMo IPN', $request->all());

        $momoOrderId = $request->orderId;
        $resultCode = $request->resultCode;

        $parts = explode('_', $momoOrderId);
        $realOrderId = end($parts);

        $order = Order::find($realOrderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($resultCode == 0) {
            // ✅ Thanh toán thành công
            $order->update([
                'status_order'   => 'pending',
                'status_payment' => 'paid',
                'trans_id'       => $request->transId,
                'response_data'  => json_encode($request->all()),
            ]);
            
            // Xóa giỏ hàng
            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                $cart->items()->delete();
            }
            
            // ✅ GỬI EMAIL (nếu chưa gửi trong callback)
            $this->sendOrderConfirmationEmail($order);
            
        } else {
            // ❌ Thanh toán thất bại - hoàn lại số lượng
            foreach ($order->orderItems as $orderItem) {
                $orderItem->variant->increment('quantity', $orderItem->quantity);
            }
            
            $order->update([
                'status_order'   => 'cancelled',
                'status_payment' => 'failed',
                'response_data'  => json_encode($request->all()),
            ]);
        }

        return response()->json(['message' => 'IPN processed'], 200);
    }

    /**
     * Trang thành công
     */
    public function success($orderId)
    {
        $order = Order::with('orderItems.variant.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.payment.success', compact('order'));
    }

    /**
     * Trang thất bại
     */
    public function failed($orderId)
    {
        $order = Order::with('orderItems.variant.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.payment.failed', compact('order'));
    }
}