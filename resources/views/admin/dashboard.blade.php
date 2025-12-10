@extends('admin.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-chart-line mr-2"></i>Dashboard - Tổng quan
    </h1>
    <div>
        <button class="btn btn-sm btn-primary shadow-sm" onclick="refreshData()">
            <i class="fas fa-sync-alt fa-sm text-white-50"></i> Làm mới
        </button>
        <a href="#" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Xuất báo cáo
        </a>
    </div>
</div>

<!-- Thẻ thống kê -->
<div class="row">
    <!-- Doanh thu tháng -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 hover-scale">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Doanh thu (Tháng)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="revenue-month">
                            {{ number_format($revenueMonth ?? 40000000) }} VNĐ
                        </div>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> +12% so với tháng trước
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng mới -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 hover-scale">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Đơn hàng mới
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="new-orders">
                            {{ $newOrders ?? 18 }}
                        </div>
                        <small class="text-muted">Chờ xử lý</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sản phẩm -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 hover-scale">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng sản phẩm
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-products">
                            {{ $totalProducts ?? 150 }}
                        </div>
                        <small class="text-muted">Đang hoạt động</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cảnh báo tồn kho -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 hover-scale">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Cảnh báo tồn kho
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="low-stock">
                            {{ $lowStock ?? 12 }}
                        </div>
                        <small class="text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Sắp hết hàng
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ -->
<div class="row">
    <!-- Biểu đồ doanh thu -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area mr-2"></i>Doanh thu 7 ngày qua
                </h6>
                <div class="dropdown no-arrow">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-calendar"></i> Tuần này
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="height: 320px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Biểu đồ tròn danh mục -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie mr-2"></i>Sản phẩm theo danh mục
                </h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="height: 320px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top sản phẩm bán chạy & Đơn hàng gần đây -->
<div class="row">
    <!-- Top sản phẩm -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-fire mr-2"></i>Top 5 sản phẩm bán chạy
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                    $topProducts = [
                        ['name' => 'Nike Air Max 270', 'sold' => 45, 'revenue' => 45000000],
                        ['name' => 'Adidas Ultraboost', 'sold' => 38, 'revenue' => 38000000],
                        ['name' => 'Puma RS-X', 'sold' => 32, 'revenue' => 25000000],
                        ['name' => 'New Balance 574', 'sold' => 28, 'revenue' => 22000000],
                        ['name' => 'Converse Chuck Taylor', 'sold' => 25, 'revenue' => 18000000],
                    ];
                    @endphp
                    
                    @foreach($topProducts as $index => $product)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-pill badge-success mr-3">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-weight-bold">{{ $product['name'] }}</div>
                                    <small class="text-muted">Đã bán: {{ $product['sold'] }} đơn</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold text-success">
                                    {{ number_format($product['revenue']) }}đ
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-clock mr-2"></i>Đơn hàng gần đây
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $recentOrders = [
                                ['code' => 'DH001', 'customer' => 'Nguyễn Văn A', 'total' => 2500000, 'status' => 'pending'],
                                ['code' => 'DH002', 'customer' => 'Trần Thị B', 'total' => 1800000, 'status' => 'confirmed'],
                                ['code' => 'DH003', 'customer' => 'Lê Văn C', 'total' => 3200000, 'status' => 'shipping'],
                                ['code' => 'DH004', 'customer' => 'Phạm Thị D', 'total' => 950000, 'status' => 'completed'],
                                ['code' => 'DH005', 'customer' => 'Hoàng Văn E', 'total' => 2100000, 'status' => 'pending'],
                            ];
                            
                            $statusBadges = [
                                'pending' => '<span class="badge badge-warning">Chờ xử lý</span>',
                                'confirmed' => '<span class="badge badge-info">Đã xác nhận</span>',
                                'shipping' => '<span class="badge badge-primary">Đang giao</span>',
                                'completed' => '<span class="badge badge-success">Hoàn thành</span>',
                            ];
                            @endphp
                            
                            @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order['code'] }}</strong></td>
                                <td>{{ $order['customer'] }}</td>
                                <td class="text-danger font-weight-bold">
                                    {{ number_format($order['total']) }}đ
                                </td>
                                <td>{!! $statusBadges[$order['status']] !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-scale {
    transition: transform 0.2s ease;
}
.hover-scale:hover {
    transform: translateY(-5px);
}
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Biểu đồ doanh thu
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [3200000, 4100000, 3800000, 5200000, 6100000, 5800000, 7200000],
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return (value / 1000000).toFixed(1) + 'M';
                    }
                }
            }
        }
    }
});

// Biểu đồ tròn danh mục
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: ['Giày thể thao', 'Giày da', 'Sandal', 'Dép', 'Giày boot'],
        datasets: [{
            data: [45, 25, 15, 10, 5],
            backgroundColor: [
                'rgba(78, 115, 223, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(231, 74, 59, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 15, font: { size: 12 } }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' sản phẩm';
                    }
                }
            }
        }
    }
});

function refreshData() {
    Swal.fire({
        icon: 'success',
        title: 'Đã làm mới!',
        text: 'Dữ liệu đã được cập nhật',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>
@endsection