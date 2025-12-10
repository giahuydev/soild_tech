<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategoriesAndBrandsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Đang tạo Danh mục & Thương hiệu...');

        // === DANH MỤC ===
        Category::updateOrCreate(
            ['slug' => 'giay-nam'],
            [
                'name'        => 'Giày Nam',
                'description' => 'Thời trang phái mạnh',
                'is_active'   => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'giay-nu'],
            [
                'name'        => 'Giày Nữ',
                'description' => 'Duyên dáng & Cá tính',
                'is_active'   => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'phu-kien'],
            [
                'name'        => 'Phụ Kiện',
                'description' => 'Vớ, dây giày, vệ sinh giày',
                'is_active'   => true,
            ]
        );

        // === THƯƠNG HIỆU ===
        $brands = [
            'Nike',
            'Adidas',
            'Puma',
            'Vans',
            'Reebok',        // ← SỬA LỖI "Rebook"
            'Crep Protect',  // ← Str::slug sẽ tạo "crep-protect"
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand)],
                [
                    'name'      => $brand,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Tạo Danh mục & Thương hiệu thành công!');
    }
}
