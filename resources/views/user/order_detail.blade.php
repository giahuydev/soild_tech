@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-receipt"></i> Chi tiết đơn hàng #{{ $order->id }}
        </h2>
        <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Mã đơn hàng:</strong>
                            <p class="text-primary mb-0">#{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ngày đặt:</strong>
                            <p class="mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Trạng thái đơn hàng:</strong>
                            <p class="mb-0">
                                @switch($order->status_order)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Thất bại</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">Đã hủy</span>
                                        @break
                                    @default
                                        <span class="badge bg-info">{{ $order->status_order }}</span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Trạng thái thanh toán:</strong>
                            <p class="mb-0">
                                @if($order->status_payment == 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @else
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phương thức thanh toán:</strong>
                            <p class="mb-0">
                                @if(str_contains($order->user_address ?? '', 'MoMo'))
                                    <span class="badge" style="background-color: #a50064;">
                                        <i class="bi bi-wallet2"></i> MoMo
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-cash"></i> COD
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Thông tin người nhận</h6>
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $order->user_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user_email }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->user_phone }}</p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->user_address }}</p>
                    
                    @if($order->user_note)
                        <p class="mt-2 mb-0"><strong>Ghi chú:</strong> {{ $order->user_note }}</p>
                    @endif
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body p-0">
                    @if($order->orderItems && $order->orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Size</th>
                                        <th>Màu sắc</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/products/' . $item->product_img_thumbnail) }}" alt="{{ $item->product_name }}">
                                                         class="rounded me-2"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <span class="d-block">{{ $item->product_name }}</span>
                                                        <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->variant_size_name }}</td>
                                            <td>{{ $item->variant_color_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->product_price) }}đ</td>
                                            <td class="fw-bold text-danger">
                                                {{ number_format($item->item_total) }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Không có sản phẩm trong đơn hàng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tổng kết -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Tổng kết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($order->total_price) }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong class="text-success">Miễn phí</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Tổng cộng:</h5>
                        <h5 class="mb-0 text-danger">{{ number_format($order->total_price) }}đ</h5>
                    </div>
                </div>
            </div>

            @if($order->status_order == 'pending')
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle"></i>
                    <strong>Lưu ý:</strong> Đơn hàng của bạn đang được xử lý. 
                    Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!
                </div>
            @endif
        </div>
    </div>
</div>
@endsection