<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'img_thumbnail',
        'price',
        'price_sale',
        'description',
        'is_active'
    ];

    // N-1: Thuộc về 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // N-1: Thuộc về 1 thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 1-N: Có nhiều biến thể (Size/Màu)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    // Accessor để lấy URL ảnh
    public function getImageUrlAttribute()
{
    if (!$this->img_thumbnail) {
        // Lấy ảnh từ variant nếu không có thumbnail
        $firstVariant = $this->variants()->first();
        if ($firstVariant && $firstVariant->image) {
            return $this->buildImageUrl($firstVariant->image);
        }
        return null;
    }
    
    return $this->buildImageUrl($this->img_thumbnail);
}

private function buildImageUrl($path)
{
    if (!$path) return null;
    
    // Nếu đã là URL đầy đủ
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Nếu đã có storage/ ở đầu
    if (str_starts_with($path, 'storage/')) {
        return asset($path);
    }
    
    // Nếu đã có products/ ở đầu
    if (str_starts_with($path, 'products/')) {
        return asset('storage/' . $path);
    }
    
    // Nếu chỉ có tên file
    return asset('storage/products/' . $path);
}
}