<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('cart_id')
                  ->constrained('carts')
                  ->onDelete('cascade'); // Xóa cart → xóa items
            
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade'); // Xóa variant → xóa cart item
            
            $table->unsignedInteger('quantity')->default(1);
            
            $table->timestamps();
            
            // Index
            $table->index('cart_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};