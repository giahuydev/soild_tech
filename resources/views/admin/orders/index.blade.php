@extends('admin.admin')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Quản lý Đơn hàng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái ĐH</th>
                        <th>Thanh toán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            {{ $order->user_name }}
                            <br>
                            <small class="text-muted">{{ $order->user_phone }}</small>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong class="text-danger">{{ number_format($order->total_price) }}đ</strong></td>
                        <td>
                            @switch($order->status_order)
                                @case('pending')
                                    <span class="badge badge-warning">Chờ xử lý</span>
                                    @break
                                @case('completed')
                                    <span class="badge badge-success">Hoàn thành</span>
                                    @break
                                @case('failed')
                                    <span class="badge badge-danger">Thất bại</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge badge-secondary">Đã hủy</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($order->status_payment == 'paid')
                                <span class="badge badge-success">Đã thanh toán</span>
                            @else
                                <span class="badge badge-warning">Chưa thanh toán</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Chưa có đơn hàng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection