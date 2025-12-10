@extends('admin.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Mã đơn hàng:</strong>
                            <p>#{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ngày đặt:</strong>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold mb-3">Thông tin khách hàng</h6>
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $order->user_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user_email }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->user_phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->user_address }}</p>
                    @if($order->user_note)
                        <p class="mb-0"><strong>Ghi chú:</strong> {{ $order->user_note }}</p>
                    @endif
                </div>
            </div>

            <!-- Sản phẩm -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm đã đặt</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>SL</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset($item->product_img_thumbnail) }}" width="50" class="mr-2">
                                    {{ $item->product_name }}
                                </td>
                                <td>{{ $item->variant_size_name }}</td>
                                <td>{{ $item->variant_color_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product_price) }}đ</td>
                                <td><strong>{{ number_format($item->item_total) }}đ</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cập nhật trạng thái</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label>Trạng thái đơn hàng</label>
                            <select name="status_order" class="form-control">
                                <option value="pending" {{ $order->status_order == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="completed" {{ $order->status_order == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="failed" {{ $order->status_order == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                <option value="cancelled" {{ $order->status_order == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Trạng thái thanh toán</label>
                            <select name="status_payment" class="form-control">
                                <option value="unpaid" {{ $order->status_payment == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ $order->status_payment == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <h5 class="font-weight-bold">Tổng tiền:</h5>
                        <h4 class="text-danger">{{ number_format($order->total_price) }}đ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection