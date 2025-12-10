<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Hiển thị trang hồ sơ
     */
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Cập nhật hồ sơ
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập tên',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        // Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->phone = $request->phone;

        // Đổi mật khẩu nếu có
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Mật khẩu hiện tại không đúng!');
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Danh sách đơn hàng
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('orderItems')
            ->firstOrFail();

        return view('user.order_detail', compact('order'));
    }
}