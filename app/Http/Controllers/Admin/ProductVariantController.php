<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Product;

class ProductVariantController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ biến thể (Kho hàng)
     */
    public function index(Request $request)
    {
        $query = ProductVariant::with('product');

        // Filter theo sản phẩm
        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        // Filter theo tình trạng kho
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'low':
                    $query->where('quantity', '>', 0)->where('quantity', '<', 10);
                    break;
                case 'in_stock':
                    $query->where('quantity', '>=', 10);
                    break;
            }
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $variants = $query->latest()->paginate(15);
        
        // Lấy danh sách products cho filter
        $products = Product::where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.variants.index', compact('variants', 'products'));
    }

    /**
     * Lưu biến thể mới (Từ form trong trang Edit sản phẩm)
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
        ], [
            'size.required' => 'Vui lòng nhập size',
            'color.required' => 'Vui lòng nhập màu sắc',
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.min' => 'Số lượng không được âm',
        ]);

        // Kiểm tra sản phẩm có tồn tại không
        $product = Product::findOrFail($productId);

        // Kiểm tra biến thể (Size + Color) đã tồn tại chưa
        $exists = ProductVariant::where('product_id', $productId)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Biến thể Size "' . $request->size . '" - Màu "' . $request->color . '" đã tồn tại!');
        }

        ProductVariant::create([
            'product_id' => $productId,
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Thêm biến thể thành công!');
    }

    /**
     * Hiển thị form sửa biến thể (AJAX - trả về JSON cho modal)
     */
    public function edit($id)
    {
        $variant = ProductVariant::with('product')->findOrFail($id);
        
        // Nếu request là AJAX, trả về JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'variant' => $variant
            ]);
        }

        // Nếu không phải AJAX, có thể redirect hoặc return view
        return view('admin.variants.edit', compact('variant'));
    }

    /**
     * Cập nhật biến thể
     */
    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
        ], [
            'size.required' => 'Vui lòng nhập size',
            'color.required' => 'Vui lòng nhập màu sắc',
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.min' => 'Số lượng không được âm',
        ]);

        // Kiểm tra trùng lặp (ngoại trừ chính nó)
        $exists = ProductVariant::where('product_id', $variant->product_id)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Biến thể Size "' . $request->size . '" - Màu "' . $request->color . '" đã tồn tại!');
        }

        $variant->update([
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity,
        ]);

        // Nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật biến thể thành công!'
            ]);
        }

        return back()->with('success', 'Cập nhật biến thể thành công!');
    }

    /**
     * Cập nhật nhanh chỉ số lượng (AJAX)
     */
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $variant = ProductVariant::findOrFail($id);
        $variant->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật số lượng thành công!',
            'quantity' => $variant->quantity
        ]);
    }

    /**
     * Xóa biến thể
     */
    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);
        
        // Kiểm tra xem có order items nào đang dùng variant này không
        $hasOrderItems = $variant->orderItems()->exists();
        
        if ($hasOrderItems) {
            return back()->with('error', 'Không thể xóa biến thể này vì đã có trong đơn hàng!');
        }

        $productName = $variant->product->name;
        $variant->delete();

        return back()->with('success', "Đã xóa biến thể của sản phẩm: {$productName}");
    }

    /**
     * Bulk update số lượng cho nhiều variants
     */
    public function bulkUpdateQuantity(Request $request)
    {
        $request->validate([
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:product_variants,id',
            'variants.*.quantity' => 'required|numeric|min:0',
        ]);

        foreach ($request->variants as $variantData) {
            ProductVariant::where('id', $variantData['id'])
                ->update(['quantity' => $variantData['quantity']]);
        }

        return back()->with('success', 'Cập nhật số lượng hàng loạt thành công!');
    }
}