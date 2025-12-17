<?php
// ====================================
// File: app/Models/Order.php
// ====================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',           // MoMo order ID
        'request_id',         // MoMo request ID
        'order_info',         // Order description
        'trans_id',           // MoMo transaction ID
        'response_data',      // Full MoMo response (JSON)
        'user_name',
        'user_email',
        'user_phone',
        'user_address',
        'user_note',
        'is_ship_user_same_user',
        'status_order',       // pending, completed, failed, cancelled
        'status_payment',     // unpaid, paid
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'is_ship_user_same_user' => 'boolean',
        'response_data' => 'array',  // ✅ THÊM: Auto cast JSON to array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ THÊM: Constants cho status
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';

    // ✅ THÊM: Accessor cho payment method
    public function getPaymentMethodAttribute()
    {
        if (str_contains($this->user_address ?? '', 'MoMo')) {
            return 'momo';
        }
        return 'cod';
    }

    // ✅ THÊM: Accessor cho payment method label
    public function getPaymentMethodLabelAttribute()
    {
        return $this->payment_method === 'momo' ? 'MoMo' : 'COD';
    }

    // ✅ THÊM: Accessor cho status label
    public function getStatusLabelAttribute()
    {
        return match($this->status_order) {
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_FAILED => 'Thất bại',
            self::STATUS_CANCELLED => 'Đã hủy',
            default => $this->status_order
        };
    }

    // ✅ THÊM: Accessor cho status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status_order) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            default => 'bg-info'
        };
    }

    // ✅ THÊM: Accessor cho payment status label
    public function getPaymentStatusLabelAttribute()
    {
        return $this->status_payment === self::PAYMENT_PAID 
            ? 'Đã thanh toán' 
            : 'Chưa thanh toán';
    }

    // ✅ THÊM: Accessor cho payment status badge class
    public function getPaymentStatusBadgeClassAttribute()
    {
        return $this->status_payment === self::PAYMENT_PAID 
            ? 'bg-success' 
            : 'bg-warning';
    }

    // ✅ THÊM: Check methods
    public function isPending()
    {
        return $this->status_order === self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status_order === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status_order === self::STATUS_CANCELLED;
    }

    public function isPaid()
    {
        return $this->status_payment === self::PAYMENT_PAID;
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ✅ THÊM: Scopes
    public function scopePending($query)
    {
        return $query->where('status_order', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_order', self::STATUS_COMPLETED);
    }

    public function scopePaid($query)
    {
        return $query->where('status_payment', self::PAYMENT_PAID);
    }
}