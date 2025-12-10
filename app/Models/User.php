<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Gửi email xác thực tùy chỉnh (Tiếng Việt)
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    /**
     * ✅ THÊM METHOD NÀY - Gửi email reset password tùy chỉnh
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    /**
     * Quan hệ: 1 user có nhiều đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Quan hệ: 1 user có 1 giỏ hàng
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Quan hệ: 1 user có nhiều cartItems
     */
    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, Cart::class);
    }
}