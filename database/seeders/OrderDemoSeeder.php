<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Chỉ chạy khi ở local hoặc testing → production không bao giờ có đơn hàng giả
        if (!app()->environment('local', 'testing')) {
            $this->command->info('OrderDemoSeeder bị bỏ qua trên production.');
            return;
        }

        $this->command->info('Đang tạo 45 đơn hàng demo (chỉ trên local)...');

        User::query()->update(['email_verified_at' => now()]);

        $variants = ProductVariant::with('product')->inRandomOrder()->get();
        $users    = User::all();
        $statuses = ['pending', 'completed', 'failed', 'cancelled'];

        for ($i = 0; $i < 45; $i++) {
            $user  = $users->random();
            $items = $variants->random(rand(1, 4));
            $total = 0;

            $order = Order::create([
                'user_id'        => $user->id,
                'user_name'      => $user->name,
                'user_email'     => $user->email,
                'user_phone'     => $user->phone ?? '090' . rand(1000000, 9999999),
                'user_address'   => 'Quận ' . rand(1, 12) . ', TP. Hồ Chí Minh',
                'total_price'    => 0, // sẽ tính sau
                'status_order'   => $statuses[array_rand($statuses)],
                'status_payment' => 'paid',
                'created_at'     => now()->subDays(rand(1, 120)),
            ]);

            foreach ($items as $v) {
                $qty   = rand(1, 3);
                $price = $v->price ?? $v->product->price_sale ?? $v->product->price;
                $subtotal = $price * $qty;
                $total += $subtotal;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $v->id,
                    'quantity'           => $qty,
                    'price_at_purchase'  => $price,
                    'subtotal'           => $subtotal,
                ]);
            }

            $order->update(['total_price' => $total]);
        }

        $this->command->info('Tạo xong 45 đơn hàng demo (chỉ trên local)!');
    }
}