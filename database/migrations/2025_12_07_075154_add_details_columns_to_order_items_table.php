<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_img_thumbnail')->nullable();
            $table->decimal('product_price', 15, 2)->nullable();
            $table->string('variant_size_name')->nullable();
            $table->string('variant_color_name')->nullable();
            $table->decimal('item_total', 15, 2)->nullable();
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'product_sku',
                'product_img_thumbnail',
                'product_price',
                'variant_size_name',
                'variant_color_name',
                'item_total',
                'status',
            ]);
        });
    }
};
