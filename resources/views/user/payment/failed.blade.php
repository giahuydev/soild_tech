@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm rounded-0 text-center">
                <div class="card-body p-5">
                    {{-- Icon thất bại --}}
                    <div class="mb-4">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="38" stroke="#dc3545" stroke-width="4" fill="#f8d7da"/>
                            <path d="M30 30L50 50M50 30L30 50" stroke="#dc3545" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h2 class="text-danger mb-3">Thanh toán thất bại!</h2>
                    <p class="text-muted mb-4">
                        {{ $message ?? 'Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.' }}
                    </p>

                    {{-- Thông tin đơn hàng --}}
                    <div class="bg-light p-4 rounded-0 mb-4">
                        <div class="row text-start">
                            <div class="col-6">
                                <p class="mb-2"><strong>Mã đơn hàng:</strong></p>
                                <p class="mb-2"><strong>Số tiền:</strong></p>
                                <p class="mb-2"><strong>Trạng thái:</strong></p>
                                <p class="mb-0"><strong>Thời gian:</strong></p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-2"><code>{{ $order->order_id }}</code></p>
                                <p class="mb-2 text-danger fw-bold">{{ number_format($order->amount, 0, ',', '.') }} VNĐ</p>
                                <p class="mb-2">
                                    <span class="badge bg-danger">Thất bại</span>
                                </p>
                                <p class="mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lý do thất bại --}}
                    <div class="alert alert-danger rounded-0 text-start">
                        <strong><i class="bi bi-exclamation-triangle"></i> Có thể do:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Số dư tài khoản không đủ</li>
                            <li>Thông tin xác thực không chính xác</li>
                            <li>Hủy giao dịch</li>
                            <li>Lỗi kết nối</li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('payment.checkout') }}" class="btn btn-danger rounded-0 py-2">
                            <i class="bi bi-arrow-clockwise"></i> Thử lại thanh toán
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-0 py-2">
                            <i class="bi bi-house-door"></i> Về trang chủ
                        </a>
                    </div>

                    {{-- Support --}}
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="bi bi-telephone"></i>
                            Cần hỗ trợ? Liên hệ: <strong>1900.633.349</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection