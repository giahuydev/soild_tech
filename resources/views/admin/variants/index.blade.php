@extends('admin.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse mr-2"></i>Quản lý Tồn Kho
        </h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <!-- Stats Cards đơn giản -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng biến thể
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $variants->total() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Còn hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $variants->where('quantity', '>', 0)->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Sắp hết
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $variants->whereBetween('quantity', [1, 9])->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Hết hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $variants->where('quantity', 0)->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng tồn kho -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Biến thể Tồn kho</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="60">Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th width="80">Size</th>
                            <th width="120">Màu</th>
                            <th width="100" class="text-center">Tồn kho</th>
                            <th width="130" class="text-center">Trạng thái</th>
                            <th width="120" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variants as $variant)
                        <tr>
                            <td class="text-center">
                                @if($variant->product->img_thumbnail ?? false)
                                    <img src="{{ asset($variant->product->img_thumbnail) }}" 
                                         width="50" 
                                         height="50"
                                         class="img-thumbnail"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; border-radius: 4px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $variant->product_id) }}" 
                                   class="font-weight-bold text-dark">
                                    {{ $variant->product->name ?? 'Sản phẩm đã xóa' }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-barcode mr-1"></i>SKU: {{ $variant->product->sku ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $variant->size }}</span>
                            </td>
                            <td>{{ $variant->color }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill {{ $variant->quantity > 10 ? 'badge-success' : ($variant->quantity > 0 ? 'badge-warning' : 'badge-danger') }}" 
                                      style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                    {{ $variant->quantity }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($variant->quantity == 0)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle mr-1"></i>Hết hàng
                                    </span>
                                @elseif($variant->quantity < 10)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Sắp hết
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>Còn hàng
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.edit', $variant->product_id) }}" 
                                   class="btn btn-sm btn-info mb-1"
                                   title="Sửa sản phẩm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.product_variants.destroy', $variant->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      data-confirm-text="Xóa biến thể này?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger mb-1 btn-delete" 
                                            title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Chưa có biến thể nào trong kho</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($variants->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $variants->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-danger { border-left: 4px solid #e74a3b !important; }

.table thead th {
    background-color: #f8f9fc;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #858796;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

.img-thumbnail {
    border-radius: 4px;
}

.badge {
    font-weight: 600;
}
</style>
@endsection