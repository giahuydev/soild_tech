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
        'quantity' => 'integer',
    ];

    // ✅ THÊM: Accessor cho image URL (QUAN TRỌNG)
    public function getImageUrlAttribute()
    {
        if ($this->product_img_thumbnail) {
            // Check if path already includes storage/
            if (str_starts_with($this->product_img_thumbnail, 'storage/')) {
                return asset($this->product_img_thumbnail);
            }
            return asset('storage/' . $this->product_img_thumbnail);
        }
        return asset('images/no-image.png'); // fallback image
    }

    // ✅ THÊM: Accessor cho formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->product_price, 0, ',', '.') . 'đ';
    }

    // ✅ THÊM: Accessor cho formatted item total
    public function getFormattedItemTotalAttribute()
    {
        return number_format($this->item_total, 0, ',', '.') . 'đ';
    }

    // ✅ THÊM: Accessor cho full variant name
    public function getFullVariantNameAttribute()
    {
        $parts = [];
        
        if ($this->variant_size_name) {
            $parts[] = "Size: {$this->variant_size_name}";
        }
        
        if ($this->variant_color_name) {
            $parts[] = "Màu: {$this->variant_color_name}";
        }
        
        return implode(' - ', $parts);
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

    // ✅ THÊM: Helper method to get product URL
    public function getProductUrl()
    {
        if ($this->variant && $this->variant->product) {
            return route('shop.detail', $this->variant->product->slug);
        }
        return '#';
    }
}