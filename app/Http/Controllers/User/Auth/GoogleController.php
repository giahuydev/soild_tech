<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect đến trang đăng nhập Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            Log::info('Google Login Attempt:', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);
            
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                    
                    Log::info('Linked Google account to existing user', ['user_id' => $user->id]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                        'role' => 0,
                        'password' => null,
                    ]);
                    
                    Log::info('Created new user from Google', ['user_id' => $user->id]);
                }
            }

            Auth::login($user, true);
            session()->forget(['error', 'warning']);

            Log::info('Google login successful', ['user_id' => $user->id]);

            return redirect('/')
                ->with('success', "Đăng nhập Google thành công! Chào {$user->name}");

        } catch (Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Đăng nhập Google thất bại! Vui lòng thử lại. Lỗi: ' . $e->getMessage());
        }
    }
}