<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price_at_purchase', 15, 2)->after('quantity')->default(0);
            $table->decimal('subtotal', 15, 2)->after('price_at_purchase')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['price_at_purchase', 'subtotal']);
        });
    }
};