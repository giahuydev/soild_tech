<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
    ];

    /**
     * Quan hệ: 1 cartItem thuộc về 1 giỏ hàng
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Quan hệ: 1 cartItem thuộc về 1 biến thể sản phẩm
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
