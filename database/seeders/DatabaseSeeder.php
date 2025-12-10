<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategoriesAndBrandsSeeder::class,
            ProductsDemoSeeder::class,      // ← 50 sản phẩm thật
        ]);

        if (app()->environment('local', 'testing')) {
            $this->call(OrderDemoSeeder::class); // 45 đơn hàng giả chỉ ở local
        }
    }
}