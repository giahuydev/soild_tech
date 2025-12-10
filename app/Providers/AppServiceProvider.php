<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator; // THÊM: Dùng để cấu hình phân trang

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // THÊM: Cấu hình cho Laravel Pagination để sử dụng Bootstrap 5
        // Điều này khắc phục lỗi phân trang bị "un-styled" hoặc quá khổ.
        Paginator::useBootstrapFive();

        // Force HTTPS khi sử dụng dev tunnel
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}