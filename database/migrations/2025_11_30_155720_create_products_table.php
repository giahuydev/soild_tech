<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys với onDelete
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade'); // Xóa category → xóa products
            
            $table->foreignId('brand_id')
                  ->constrained('brands')
                  ->onDelete('cascade'); // Xóa brand → xóa products
            
            // Thông tin sản phẩm
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->nullable();
            $table->string('img_thumbnail')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('price_sale', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Index để tăng tốc query
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};