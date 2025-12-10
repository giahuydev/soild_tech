<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // FK: Mỗi order_item thuộc về 1 đơn hàng
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            // FK: sản phẩm đã chọn (product_variant)
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade');

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            // Index
            $table->index('order_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
