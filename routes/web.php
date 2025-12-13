<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ============= USER CONTROLLERS =============
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\BrandController as UserBrandController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\GoogleController;
use App\Http\Controllers\User\Login\LoginController;
use App\Http\Controllers\User\Product\CartController;
use App\Http\Controllers\User\Product\ProductController as UserProductController;
use App\Http\Controllers\User\Payment\MoMoController;

// ============= ADMIN CONTROLLERS =============
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductVariantController;

/*
|--------------------------------------------------------------------------
| HOME PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginRegister'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm']) 
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')->name('password.email');

Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm']) 
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')->name('password.update');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
        return redirect('/')->with('info', 'Email của bạn đã được xác thực.');
    }
    return view('user.auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = \App\Models\User::findOrFail($request->route('id'));

    if (!hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
        abort(403, 'Link xác thực không hợp lệ.');
    }

    if (!$request->hasValidSignature()) {
        return redirect()->route('login')
            ->with('error', 'Link xác thực đã hết hạn. Vui lòng đăng nhập và gửi lại email xác thực.');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')
            ->with('info', 'Email này đã được xác thực. Bạn có thể đăng nhập.');
    }

    $user->markEmailAsVerified();
    Auth::login($user);

    return redirect('/')
        ->with('success', '🎉 Email đã được xác thực thành công! Chào mừng ' . $user->name . ' đến với SOLID TECH!');
        
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return back()->with('info', 'Email của bạn đã được xác thực rồi.');
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('success', 'Email xác thực đã được gửi lại! Vui lòng kiểm tra hộp thư.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/email/resend', [LoginController::class, 'resendVerificationEmail'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

/*
|--------------------------------------------------------------------------
| USER PROFILE & ORDERS (Cần verify email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [UserController::class, 'orderDetail'])->name('order.detail');
});

/*
|--------------------------------------------------------------------------
| CART (Cần verify email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/count', [CartController::class, 'count'])->name('count');
});

/*
|--------------------------------------------------------------------------
| PRODUCTS (Public)
|--------------------------------------------------------------------------
*/
Route::get('/san-pham', [UserProductController::class, 'index'])->name('shop.index');
Route::get('/san-pham/{slug}.html', [UserProductController::class, 'detail'])->name('shop.detail');
Route::get('/danh-muc/{slug}', [UserProductController::class, 'getByCategory'])->name('shop.category');
Route::get('/hot-sale', [UserProductController::class, 'hotSale'])->name('shop.hotSale');

/*
|--------------------------------------------------------------------------
| BRANDS (Public)
|--------------------------------------------------------------------------
*/
Route::get('/thuong-hieu', [UserBrandController::class, 'index'])->name('brands.index');
Route::get('/thuong-hieu/{slug}', [UserBrandController::class, 'show'])->name('brands.show');

/*
|--------------------------------------------------------------------------
| PAYMENT (Cần verify email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout', [MoMoController::class, 'showCheckout'])->name('checkout');
    Route::post('/process', [MoMoController::class, 'processPayment'])->name('process');
    Route::get('/success/{orderId}', [MoMoController::class, 'success'])->name('success');
    Route::get('/failed/{orderId}', [MoMoController::class, 'failed'])->name('failed');
});

// MoMo Callbacks (Public)
Route::get('/payment/momo/callback', [MoMoController::class, 'callback'])->name('momo.callback');
Route::post('/payment/momo/ipn', [MoMoController::class, 'ipn'])->name('momo.ipn');

/*
|--------------------------------------------------------------------------
| OTHER PAGES
|--------------------------------------------------------------------------
*/
Route::view('/return-policy', 'user.return_policy')->name('return.policy');
Route::view('/about', 'user.about')->name('about');
Route::view('/contact', 'user.contact')->name('contact');
/*
|--------------------------------------------------------------------------
| PUBLIC TEST SENDGRID (TEMPORARY - XÓA SAU KHI XONG)
|--------------------------------------------------------------------------
*/
Route::get('/test-mail-now', function() {
    try {
        $config = [
            'default_mailer' => config('mail.default'),
            'sendgrid_key_exists' => config('services.sendgrid.api_key') ? 'YES' : 'NO',
            'sendgrid_key_length' => config('services.sendgrid.api_key') ? strlen(config('services.sendgrid.api_key')) : 0,
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];
        
        // Test gửi mail
        \Illuminate\Support\Facades\Mail::raw(
            'Test SendGrid from Railway - ' . now()->toDateTimeString(), 
            function($message) {
                $message->to('trangiahuy7676@gmail.com')
                        ->subject('Test SendGrid - ' . now()->format('H:i:s'));
            }
        );
        
        return response()->json([
            'status' => 'success',
            'message' => '✅ Email sent successfully!',
            'config' => $config,
            'time' => now()->toDateTimeString()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'type' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'config' => $config ?? []
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES - TEST SENDGRID
|--------------------------------------------------------------------------
*/
Route::get('/debug/test-sendgrid', function() {
    // Check admin role
    if (!Auth::check() || Auth::user()->role != 1) {
        return response()->json(['error' => 'Admin only'], 403);
    }
    
    try {
        // 1. Display current config
        $mailConfig = [
            'default_mailer' => config('mail.default'),
            'sendgrid_configured' => config('services.sendgrid.api_key') ? 'Yes (length: ' . strlen(config('services.sendgrid.api_key')) . ')' : 'No',
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];
        
        // 2. Check ENV variables
        $envVars = [
            'MAIL_MAILER' => env('MAIL_MAILER'),
            'SENDGRID_API_KEY_SET' => env('SENDGRID_API_KEY') ? 'Yes (length: ' . strlen(env('SENDGRID_API_KEY')) . ')' : 'No',
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        ];
        
        // 3. Test send email
        $startTime = microtime(true);
        $testEmail = 'trangiahuy7676@gmail.com';
        
        \Illuminate\Support\Facades\Mail::raw(
            'Test SendGrid from Railway - ' . now()->toDateTimeString() . "\n\nThis is a test email to verify SendGrid integration.", 
            function($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('✅ Test SendGrid - ' . now()->format('Y-m-d H:i:s'));
            }
        );
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        return response()->json([
            'status' => 'success',
            'message' => "✅ Email sent successfully in {$duration}s",
            'test_email' => $testEmail,
            'config' => $mailConfig,
            'env_vars' => $envVars,
            'duration' => $duration . 's',
            'timestamp' => now()->toDateTimeString()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'type' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'config' => $mailConfig ?? null,
            'env_vars' => $envVars ?? null
        ], 500);
    }
});
/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', CheckAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // ========== DASHBOARD ==========
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // ========== PRODUCTS ==========
        Route::resource('products', AdminProductController::class);
        
        // ========== CATEGORIES ==========
        Route::resource('categories', CategoryController::class);
        
        // ========== BRANDS ==========
        Route::resource('brands', AdminBrandController::class);
        
        // ========== PRODUCT VARIANTS ==========
        Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])
            ->name('product_variants.store');
        
        Route::delete('variants/{variant}', [ProductVariantController::class, 'destroy'])
            ->name('product_variants.destroy');
        
        // ========== ORDERS ==========
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [DashboardController::class, 'orders'])->name('index');
            Route::get('/{orderId}', [DashboardController::class, 'orderDetail'])->name('detail');
            Route::post('/{orderId}/update-status', [DashboardController::class, 'updateOrderStatus'])
                ->name('update_status');
        });
    });