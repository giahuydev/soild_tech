@extends('admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="fas fa-tags text-primary me-2"></i>Quản lý Thương hiệu
            </h1>
            <p class="text-muted mb-0">Quản lý các thương hiệu giày trên hệ thống</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-modern">
            <i class="fas fa-plus me-2"></i>Thêm thương hiệu
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng thương hiệu
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $brands->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đang hiển thị
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $brands->where('is_active', 1)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 border-left-secondary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Đang ẩn
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $brands->where('is_active', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 border-left-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Có Logo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $brands->whereNotNull('logo')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-image fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <!-- Search & Filter Bar -->
            <div class="p-4 border-bottom bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" 
                                   placeholder="Tìm kiếm thương hiệu..." 
                                   id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hiển thị</option>
                            <option value="inactive">Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortBy">
                            <option value="newest">Sắp xếp: Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="name_desc">Tên Z-A</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="brandsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold" style="width: 80px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="py-3 text-muted fw-semibold" style="width: 80px;">ID</th>
                            <th class="py-3 text-muted fw-semibold" style="width: 120px;">Logo</th>
                            <th class="py-3 text-muted fw-semibold">Tên thương hiệu</th>
                            <th class="py-3 text-muted fw-semibold">Slug</th>
                            <th class="py-3 text-muted fw-semibold" style="width: 200px;">Mô tả</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="width: 120px;">Trạng thái</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr class="border-bottom brand-row">
                            <td class="px-4">
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-semibold">{{ $brand->id }}</span>
                            </td>
                            <td>
                                @if($brand->logo)
                                    <div class="brand-logo-wrapper">
                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="rounded-2">
                                    </div>
                                @else
                                    <div class="brand-logo-placeholder rounded-2 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $brand->name }}</div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $brand->created_at->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <code class="text-muted small bg-light px-2 py-1 rounded">{{ $brand->slug }}</code>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $brand->description ? Str::limit($brand->description, 50, '...') : 'Chưa có mô tả' }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($brand->is_active)
                                    <span class="badge-status badge-success">
                                        <i class="fas fa-check-circle me-1"></i>Hiển thị
                                    </span>
                                @else
                                    <span class="badge-status badge-secondary">
                                        <i class="fas fa-eye-slash me-1"></i>Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu {{ $brand->name }}?')">
                                        @csrf
                                        @method('DELETE') <!-- 👈 thêm dòng này -->
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger btn-delete" 
                                                data-bs-toggle="tooltip" 
                                                title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-box-open text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">Chưa có thương hiệu nào</p>
                                    <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-primary mt-3">
                                        <i class="fas fa-plus me-1"></i>Thêm thương hiệu đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($brands->hasPages())
            <div class="p-4 border-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted small">
                        Hiển thị {{ $brands->firstItem() }} - {{ $brands->lastItem() }} trong tổng số {{ $brands->total() }} thương hiệu
                    </div>
                    <button class="btn btn-sm btn-outline-danger" id="deleteSelected" style="display: none;">
                        <i class="fas fa-trash me-1"></i>Xóa đã chọn (<span id="selectedCount">0</span>)
                    </button>
                </div>
                <div>
                    {{ $brands->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Modern Button */
.btn-modern {
    padding: 0.625rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Stat Cards */
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-secondary { border-left: 0.25rem solid #858796 !important; }

/* Brand Logo */
.brand-logo-wrapper {
    width: 80px;
    height: 80px;
    overflow: hidden;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
}

.brand-logo-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    padding: 0.5rem;
}

.brand-logo-placeholder {
    width: 80px;
    height: 80px;
    border: 1px dashed #d1d5db;
}

/* Custom Badge Status */
.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    font-size: 0.813rem;
    font-weight: 500;
    border-radius: 0.375rem;
}

.badge-status.badge-success {
    background-color: #d1fae5;
    color: #065f46;
}

.badge-status.badge-secondary {
    background-color: #f3f4f6;
    color: #6b7280;
}

/* Table Hover Effect */
.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f9fafb;
}

/* Button Group */
.btn-group .btn {
    padding: 0.4rem 0.75rem;
    border-radius: 0.375rem !important;
    margin: 0 0.125rem;
}

.btn-outline-primary:hover {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-outline-danger:hover {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

/* Input Focus */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

/* Code tag */
code {
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-modern {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .brand-logo-wrapper,
    .brand-logo-placeholder {
        width: 60px;
        height: 60px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const table = document.getElementById('brandsTable');
    const rows = table.querySelectorAll('tbody tr.brand-row');

    searchInput.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilter = filterStatus.value;

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const slug = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const status = row.querySelector('.badge-status').classList.contains('badge-success') ? 'active' : 'inactive';

            const matchesSearch = name.includes(searchTerm) || slug.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const deleteSelected = document.getElementById('deleteSelected');
    const selectedCount = document.getElementById('selectedCount');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const count = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
        if (selectedCount) selectedCount.textContent = count;
        if (deleteSelected) deleteSelected.style.display = count > 0 ? 'block' : 'none';
        if (selectAll) selectAll.checked = count === rowCheckboxes.length && count > 0;
    }
});
</script>
@endsection