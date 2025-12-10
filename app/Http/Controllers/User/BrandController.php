<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', true)->get();
        return view('user.brands.index', compact('brands'));
    }

    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $products = Product::where('brand_id', $brand->id)
                           ->where('is_active', true)
                           ->paginate(9);

        return view('user.products.index', [
            'products' => $products,
            'categoryName' => 'Thương hiệu ' . $brand->name
        ]);
    }
}