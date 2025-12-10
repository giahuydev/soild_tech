<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();

        $cartItems = $cart->items()
            ->with(['variant.product'])
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            $price = $item->variant->product->price_sale
                ?? $item->variant->product->price;
            return $price * $item->quantity;
        });

        return view('user.cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Thêm sản phẩm vào giỏ
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required',
            'color' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Tìm variant phù hợp
            $variant = ProductVariant::where('product_id', $request->product_id)
                ->where('size', $request->size)
                ->where('color', $request->color)
                ->firstOrFail();

            // Kiểm tra tồn kho
            if ($variant->quantity < $request->quantity) {
                return back()->with('error', 'Không đủ hàng trong kho!');
            }

            $cart = $this->getOrCreateCart();

            // Kiểm tra sản phẩm đã có trong giỏ chưa
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;

                if ($variant->quantity < $newQuantity) {
                    return back()->with('error', 'Không đủ hàng trong kho!');
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $request->quantity
                ]);
            }

            DB::commit();
            
            // QUAN TRỌNG: Redirect về trang trước đó thay vì giỏ hàng
            return redirect()->back()
                ->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->with('variant.product')
            ->firstOrFail();

        if ($cartItem->variant->quantity < $request->quantity) {
            return back()->with('error', 'Không đủ hàng trong kho!');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cập nhật số lượng thành công!');
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function remove($id)
    {
        $cart = $this->getOrCreateCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ (dùng cho header)
     */
    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $cart = $this->getOrCreateCart();
        $count = $cart->items()->sum('quantity');

        return response()->json(['count' => $count]);
    }

    /**
     * Helper: Lấy hoặc tạo giỏ hàng
     */
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['user_id' => Auth::id()]
        );
    }
}