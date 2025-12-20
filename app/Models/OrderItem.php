<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'product_sku',
        'product_img_thumbnail',
        'product_price',
        'variant_size_name',
        'variant_color_name',
        'quantity',
        'item_total',
        'status',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'item_total' => 'decimal:2',
    ];

    // ✅ Accessor cho image URL
    public function getImageUrlAttribute()
    {
        // Mặc định là placeholder
        $default = 'https://via.placeholder.com/50x50/f8f9fa/6c757d?text=No+Image';
        
        // Kiểm tra trường product_img_thumbnail
        if (empty($this->product_img_thumbnail)) {
            return $default;
        }
        
        $img = $this->product_img_thumbnail;
        
        // Nếu đã là URL đầy đủ (http/https)
        if (str_starts_with($img, 'http')) {
            return $img;
        }
        
        // Nếu bắt đầu bằng /uploads (đường dẫn tuyệt đối)
        if (str_starts_with($img, '/uploads')) {
            return asset($img);
        }
        
        // Nếu chỉ là tên file
        return asset('uploads/products/' . $img);
    }

    // ✅ Accessor cho formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->product_price) . 'đ';
    }

    // ✅ Accessor cho formatted item total
    public function getFormattedItemTotalAttribute()
    {
        return number_format($this->item_total) . 'đ';
    }

    // ✅ Method để lấy URL sản phẩm
    public function getProductUrl()
    {
        // Nếu có variant, lấy product từ variant
        if ($this->variant && $this->variant->product) {
            return route('shop.detail', $this->variant->product->slug);
        }
        
        // Fallback: Trang shop
        return route('shop.index');
    }

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}