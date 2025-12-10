@extends('admin.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                <i class="fas fa-box text-primary mr-2"></i>Quản lý Sản phẩm
            </h1>
            <p class="text-muted mb-0">Tổng: <strong>{{ $products->total() }}</strong> sản phẩm</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Thêm sản phẩm
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="form-row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm sản phẩm, SKU..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <select name="category" class="form-control">
                        <option value="">-- Danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="brand" class="form-control">
                        <option value="">-- Thương hiệu --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width: 140px;">Danh mục</th>
                            <th style="width: 140px;">Thương hiệu</th>
                            <th style="width: 130px;" class="text-right">Giá</th>
                            <th style="width: 90px;" class="text-center">Biến thể</th>
                            <th style="width: 100px;" class="text-center">Trạng thái</th>
                            <th style="width: 120px;" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="align-middle">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" 
                                        alt="{{ $product->name }}"
                                        class="product-thumb"
                                        onerror="
                                            if (!this.dataset.failed) {
                                                this.dataset.failed = 'true';
                                                this.src = 'https://via.placeholder.com/80x80/f8f9fa/6c757d?text=No+Image';
                                            }
                                        ">
                                @else
                                    <div class="product-thumb-placeholder">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="font-weight-bold">{{ $product->name }}</div>
                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border">
                                    {{ $product->brand->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="align-middle text-right">
                                @if($product->price_sale)
                                    <div class="text-danger font-weight-bold">{{ number_format($product->price_sale) }}đ</div>
                                    <small class="text-muted">
                                        <del>{{ number_format($product->price) }}đ</del>
                                    </small>
                                @else
                                    <div class="font-weight-bold">{{ number_format($product->price) }}đ</div>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-info">
                                    {{ $product->variants->count() }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                @if($product->is_active)
                                    <span class="badge badge-success">Hiển thị</span>
                                @else
                                    <span class="badge badge-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-primary"
                                       title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          data-confirm-text="Xóa sản phẩm '{{ $product->name }}'?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-delete"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Chưa có sản phẩm</h5>
                                <p class="text-muted mb-3">Hãy thêm sản phẩm đầu tiên</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Thêm sản phẩm
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                    trong {{ $products->total() }} sản phẩm
                </small>
                <div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Product Thumbnail */
.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e3e6f0;
}

.product-thumb-placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fc;
    border-radius: 6px;
    border: 1px dashed #d1d3e2;
}

/* Table Styling */
.table td {
    padding: 1rem 0.75rem;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.75rem;
    border-bottom: 2px solid #e3e6f0;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}

/* Badge Styling */
.badge {
    font-weight: 500;
    padding: 0.4rem 0.75rem;
    font-size: 0.813rem;
}

.badge-light {
    background-color: #f8f9fc;
    color: #5a5c69;
}

/* Button Group */
.btn-group-sm .btn {
    padding: 0.375rem 0.75rem;
}

/* Card */
.card {
    border-radius: 8px;
}

.card-body {
    padding: 1.25rem;
}

/* Responsive */
@media (max-width: 768px) {
    .table {
        font-size: 0.875rem;
    }
    
    .product-thumb,
    .product-thumb-placeholder {
        width: 50px;
        height: 50px;
    }
}
</style>
@endsection