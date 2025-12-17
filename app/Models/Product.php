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

    /**
     * ✅ FIX: Accessor để lấy URL ảnh - Tương thích Railway
     * Railway không có storage persistent, nên phải xử lý đúng path
     */
    public function getImageUrlAttribute()
    {
        // Nếu không có ảnh -> trả về placeholder
        if (empty($this->img_thumbnail)) {
            return $this->getPlaceholderImage();
        }

        // Nếu đã là URL đầy đủ (http/https) -> trả về luôn
        if (filter_var($this->img_thumbnail, FILTER_VALIDATE_URL)) {
            return $this->img_thumbnail;
        }

        // ✅ FIX: Xử lý path local
        // Loại bỏ các prefix thừa
        $cleanPath = $this->img_thumbnail;
        $cleanPath = str_replace(['storage/', 'public/', 'uploads/'], '', $cleanPath);
        
        // Kiểm tra file tồn tại trong public/uploads/products/
        $publicPath = public_path('uploads/products/' . basename($cleanPath));
        if (file_exists($publicPath)) {
            return asset('uploads/products/' . basename($cleanPath));
        }

        // Kiểm tra file trong storage/app/public/products/
        $storagePath = storage_path('app/public/products/' . basename($cleanPath));
        if (file_exists($storagePath)) {
            return asset('storage/products/' . basename($cleanPath));
        }

        // Nếu không tìm thấy -> trả về placeholder
        return $this->getPlaceholderImage();
    }

    /**
     * Tạo placeholder image với tên sản phẩm
     */
    private function getPlaceholderImage()
    {
        $shortName = substr($this->name ?? 'Product', 0, 20);
        return 'https://via.placeholder.com/400x400/f8f9fa/6c757d?text=' . urlencode($shortName);
    }

    /**
     * ✅ THÊM: Helper để lấy path đầy đủ của ảnh
     */
    public function getFullImagePath()
    {
        if (empty($this->img_thumbnail)) {
            return null;
        }

        // Nếu là URL -> không cần xử lý
        if (filter_var($this->img_thumbnail, FILTER_VALIDATE_URL)) {
            return null;
        }

        // Thử tìm trong public/uploads/products/
        $publicPath = public_path('uploads/products/' . basename($this->img_thumbnail));
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // Thử tìm trong storage/app/public/products/
        $storagePath = storage_path('app/public/products/' . basename($this->img_thumbnail));
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        return null;
    }
}