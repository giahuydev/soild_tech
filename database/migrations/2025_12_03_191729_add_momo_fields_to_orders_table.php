<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Các cột MoMo Payment
            $table->string('order_id')->unique()->nullable()->after('id');
            $table->string('request_id')->unique()->nullable()->after('order_id');
            $table->text('order_info')->nullable()->after('user_note');
            $table->string('trans_id')->nullable()->after('order_info');
            $table->text('response_data')->nullable()->after('trans_id');

            // KHÔNG tạo index vì unique đã tạo index tự động
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Xóa unique index
            $table->dropUnique(['order_id']);
            $table->dropUnique(['request_id']);

            // Xóa các cột
            $table->dropColumn([
                'order_id',
                'request_id',
                'order_info',
                'trans_id',
                'response_data'
            ]);
        });
    }
};
