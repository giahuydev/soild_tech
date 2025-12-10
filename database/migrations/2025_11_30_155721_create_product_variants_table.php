<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            
            // Foreign key với onDelete cascade
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade'); // Xóa product → xóa variants
            
            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('product_id');
            $table->index('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};