<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm (Admin view)
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        // Filter theo tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter theo thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filter theo trạng thái
        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        $products = $query->latest()->paginate(10);
        
        // Lấy categories và brands cho filter
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Form thêm mới
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * ✅ Xử lý thêm mới - LƯU CẢ VARIANTS TỪ FORM
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:products,name',
            'sku'  => 'nullable|unique:products,sku',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'brand_id' => 'required',
            'img_thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|array',
            'variants.*.size' => 'required_with:variants|string|max:50',
            'variants.*.color' => 'required_with:variants|string|max:50',
            'variants.*.quantity' => 'required_with:variants|numeric|min:0',
        ], [
            'name.unique' => 'Tên sản phẩm này đã tồn tại!',
            'sku.unique' => 'Mã SKU này đã tồn tại!',
            'name.required' => 'Vui lòng nhập tên sản phẩm!',
            'price.required' => 'Vui lòng nhập giá sản phẩm!',
            'category_id.required' => 'Vui lòng chọn danh mục!',
            'brand_id.required' => 'Vui lòng chọn thương hiệu!',
            'img_thumbnail.required' => 'Vui lòng tải ảnh sản phẩm!',
            'variants.*.size.required_with' => 'Vui lòng nhập size cho biến thể!',
            'variants.*.color.required_with' => 'Vui lòng nhập màu sắc cho biến thể!',
            'variants.*.quantity.required_with' => 'Vui lòng nhập số lượng cho biến thể!',
        ]);

        try {
            DB::beginTransaction();

            // Tạo sản phẩm
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Upload ảnh
            if ($request->hasFile('img_thumbnail')) {
                $file = $request->file('img_thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $data['img_thumbnail'] = 'uploads/products/' . $filename;
            }

            $product = Product::create($data);

            Log::info("Đã tạo sản phẩm: {$product->name} (ID: {$product->id})");

            // ✅ LƯU VARIANTS NẾU CÓ
            if ($request->has('variants') && is_array($request->variants)) {
                $variantsSaved = 0;
                
                foreach ($request->variants as $variantData) {
                    // Kiểm tra variant đã tồn tại chưa (size + color)
                    $exists = ProductVariant::where('product_id', $product->id)
                        ->where('size', $variantData['size'])
                        ->where('color', $variantData['color'])
                        ->exists();
                    
                    if (!$exists) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'quantity' => $variantData['quantity'] ?? 0,
                        ]);
                        $variantsSaved++;
                    }
                }

                Log::info("Đã lưu {$variantsSaved} variants cho sản phẩm {$product->name}");
                
                DB::commit();

                if ($variantsSaved > 0) {
                    return redirect()->route('admin.products.index')
                        ->with('success', "✅ Đã tạo sản phẩm \"{$product->name}\" với {$variantsSaved} biến thể!");
                } else {
                    return redirect()->route('admin.products.edit', $product->id)
                        ->with('warning', "✅ Đã tạo sản phẩm, nhưng chưa có biến thể nào! Hãy thêm Size và Màu.");
                }
            }

            // ✅ KHÔNG CÓ VARIANTS → REDIRECT ĐẾN TRANG EDIT
            DB::commit();
            
            return redirect()->route('admin.products.edit', $product->id)
                ->with('info', "✅ Đã tạo sản phẩm \"{$product->name}\"! Hãy thêm Size và Màu bên dưới.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi tạo sản phẩm: " . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Form sửa
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Xử lý cập nhật
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|max:255|unique:products,name,'.$id,
            'sku'  => 'nullable|unique:products,sku,'.$id,
            'price' => 'required|numeric',
            'category_id' => 'required',
            'brand_id' => 'required',
        ], [
            'name.unique' => 'Tên sản phẩm này đã tồn tại!',
            'sku.unique' => 'Mã SKU này đã trùng với sản phẩm khác!',
            'name.required' => 'Vui lòng nhập tên sản phẩm!',
            'price.required' => 'Vui lòng nhập giá sản phẩm!',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload ảnh mới (nếu có)
        if ($request->hasFile('img_thumbnail')) {
            // Xóa ảnh cũ
            if ($product->img_thumbnail && File::exists(public_path($product->img_thumbnail))) {
                File::delete(public_path($product->img_thumbnail));
            }

            $file = $request->file('img_thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['img_thumbnail'] = 'uploads/products/' . $filename;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', '✅ Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $productName = $product->name;

            // Xóa ảnh
            if ($product->img_thumbnail && File::exists(public_path($product->img_thumbnail))) {
                File::delete(public_path($product->img_thumbnail));
            }

            // Xóa variants
            $product->variants()->delete();

            // Xóa sản phẩm
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', "✅ Đã xóa sản phẩm \"{$productName}\"!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi xóa sản phẩm: " . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}