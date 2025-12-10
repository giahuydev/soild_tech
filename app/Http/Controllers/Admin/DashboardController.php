<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Trang dashboard chính
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 0)->count();
        $totalRevenue = Order::where('status_order', 'completed')->sum('total_price');
        
        // Đơn hàng mới nhất
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders'
        ));
    }
    
    /**
     * Danh sách đơn hàng
     */
    public function orders()
    {
        $orders = Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($orderId)
    {
        $order = Order::with(['user', 'orderItems'])
            ->where('id', $orderId)
            ->firstOrFail();
        
        return view('admin.orders.detail', compact('order'));
    }
    
    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status_order' => 'required|in:pending,completed,failed,cancelled',
            'status_payment' => 'nullable|in:paid,unpaid',
        ]);
        
        $order = Order::findOrFail($orderId);
        
        $order->status_order = $request->status_order;
        
        if ($request->filled('status_payment')) {
            $order->status_payment = $request->status_payment;
        }
        
        $order->save();
        
        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}