<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade'); // Xóa order → xóa history
            
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // Xóa user → set null
            
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->text('note')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            
            // Index
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};