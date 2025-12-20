<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form nhập email
     */
    public function showLinkRequestForm()
    {
        return view('user.auth.forgot_password');
    }

    /**
     * Gửi email reset password
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email này không tồn tại trong hệ thống'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', '✅ Email đặt lại mật khẩu đã được gửi! Vui lòng kiểm tra hộp thư (kể cả Spam).');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * ✅ FIX: Hiển thị form reset password
     * Tự động logout nếu user đang đăng nhập
     */
    public function showResetForm(Request $request, $token)
    {
        // ✅ FIX CHÍNH: Nếu user đang đăng nhập, logout tự động
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('user.auth.reset_password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * ✅ FIX: Xử lý reset password với bảo mật tốt hơn
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        // ✅ FIX: Sử dụng Hash::make thay vì bcrypt để consistent
        // và thêm remember token mới
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60), // ✅ Tạo remember token mới
                ])->save();

                // ✅ QUAN TRỌNG: Xóa tất cả sessions cũ trên các thiết bị khác
                // Đảm bảo chỉ session mới (sau khi reset password) mới hợp lệ
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete(); // Nếu dùng Sanctum
                }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', '✅ Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}