@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 80px;"></i>
                    <h2 class="mt-3">Thanh toán thành công!</h2>
                    <p class="text-muted">Đơn hàng của bạn đã được xác nhận</p>
                    
                    <div class="mt-4 text-start">
                        <p><strong>Mã đơn hàng:</strong> {{ $order->order_id }}</p>
                        <p><strong>Số tiền:</strong> {{ number_format($order->amount) }} VNĐ</p>
                        <p><strong>Nội dung:</strong> {{ $order->order_info }}</p>
                        <p><strong>Mã giao dịch:</strong> {{ $order->trans_id }}</p>
                    </div>

                    <a href="{{ route('home') }}" class="btn btn-primary mt-4">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection