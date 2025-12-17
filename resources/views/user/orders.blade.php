@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">
        <i class="bi bi-box-seam"></i> Đơn hàng của tôi
    </h2>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag-x display-1 text-muted"></i>
            <h4 class="mt-4">Bạn chưa có đơn hàng nào</h4>
            <p class="text-muted">Hãy mua sắm ngay để trải nghiệm dịch vụ của chúng tôi!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-dark mt-3">
                <i class="bi bi-cart"></i> Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th class="pe-4 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <strong class="text-primary">#{{ $order->id }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong class="text-danger">{{ number_format($order->total_price) }}đ</strong>
                                    </td>
                                    <td>
                                        {{-- ✅ OPTIMIZED: Sử dụng accessor --}}
                                        <span class="badge {{ $order->status_badge_class }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- ✅ OPTIMIZED: Sử dụng accessor --}}
                                        @if($order->payment_method === 'momo')
                                            <span class="badge bg-pink">
                                                <i class="bi bi-wallet2"></i> {{ $order->payment_method_label }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-cash"></i> {{ $order->payment_method_label }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-center">
                                        <a href="{{ url('/user/orders/' . $order->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<style>
    .badge.bg-pink {
        background-color: #a50064 !important;
    }
</style>
@endsection