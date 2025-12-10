<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',           // THÊM
        'request_id',         // THÊM
        'order_info',         // THÊM
        'trans_id',           // THÊM
        'response_data',      // THÊM
        'user_name',
        'user_email',
        'user_phone',
        'user_address',
        'user_note',
        'is_ship_user_same_user',
        'status_order',
        'status_payment',
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'is_ship_user_same_user' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}