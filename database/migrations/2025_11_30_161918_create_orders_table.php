<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Thông tin khách hàng
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete(); // tương thích cả MySQL & PostgreSQL

            $table->string('user_name', 100);
            $table->string('user_email', 150);
            $table->string('user_phone', 20);
            $table->text('user_address');
            $table->text('user_note')->nullable();

            // Thông tin đơn hàng
            $table->boolean('is_ship_user_same_user')->default(true);

            // Trạng thái đơn hàng & thanh toán: dùng enum để tránh lỗi CHECK
            $table->enum('status_order', ['pending', 'completed', 'failed', 'cancelled'])
                  ->default('pending');
            $table->enum('status_payment', ['paid', 'unpaid'])
                  ->default('unpaid');

            // Tổng giá trị đơn hàng
            $table->decimal('total_price', 15, 2);

            $table->timestamps();

            // Index để tăng tốc query
            $table->index('user_id');
            $table->index('status_order');
            $table->index('status_payment');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
