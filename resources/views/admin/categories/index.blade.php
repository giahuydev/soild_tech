@extends('admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Quản lý Danh mục</h1>
            <p class="text-muted mb-0">Quản lý danh mục sản phẩm trên hệ thống</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-modern">
            <i class="fas fa-plus me-2"></i>Thêm danh mục
        </a>
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
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm danh mục..." id="searchInput">
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
                <table class="table table-hover align-middle mb-0" id="categoriesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold" style="width: 80px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="py-3 text-muted fw-semibold" style="width: 80px;">ID</th>
                            <th class="py-3 text-muted fw-semibold">Tên danh mục</th>
                            <th class="py-3 text-muted fw-semibold" style="width: 200px;">Slug</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="width: 120px;">Trạng thái</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr class="border-bottom category-row">
                            <td class="px-4">
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-semibold">{{ $category->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="category-icon me-3">
                                        <i class="fas fa-folder text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $category->name }}</div>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $category->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="text-muted small">{{ $category->slug }}</code>
                            </td>
                            <td class="text-center">
                                @if($category->is_active)
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
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                        @csrf
                                        @method('DELETE') <!-- 👈 thêm dòng này -->
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
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
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">Chưa có danh mục nào</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary mt-3">
                                        <i class="fas fa-plus me-1"></i>Thêm danh mục đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Actions -->
            @if($categories->count() > 0)
            <div class="p-4 border-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted small">
                        Hiển thị {{ $categories->firstItem() }} - {{ $categories->lastItem() }} 
                        trong tổng số {{ $categories->total() }} danh mục
                    </div>
                    <button class="btn btn-sm btn-outline-danger" id="deleteSelected" style="display: none;">
                        <i class="fas fa-trash me-1"></i>Xóa đã chọn (<span id="selectedCount">0</span>)
                    </button>
                </div>
                <div>
                    {{ $categories->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Tổng danh mục</h6>
                            <h3 class="mb-0 fw-bold">{{ $categories->total() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-layer-group fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Đang hiển thị</h6>
                            <h3 class="mb-0 fw-bold">{{ $categories->where('is_active', 1)->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-eye fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Đang ẩn</h6>
                            <h3 class="mb-0 fw-bold">{{ $categories->where('is_active', 0)->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-eye-slash fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
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

/* Card Styling */
.card {
    transition: all 0.3s ease;
}

/* Category Icon */
.category-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0.5rem;
    color: white;
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
    transform: scale(1.005);
}

/* Button Group Styling */
.btn-group .btn {
    padding: 0.4rem 0.75rem;
    border-radius: 0.375rem !important;
    margin: 0 0.125rem;
}

.btn-outline-primary:hover {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-outline-danger:hover {
    background-color: #ef4444;
    border-color: #ef4444;
}

/* Input Focus */
.form-control:focus,
.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

/* Stat Cards */
.stat-icon {
    font-size: 2rem;
}

/* Code tag */
code {
    background-color: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

/* Checkbox */
.form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-modern {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .category-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const sortBy = document.getElementById('sortBy');
    const table = document.getElementById('categoriesTable');
    const rows = table.querySelectorAll('tbody tr.category-row');

    searchInput.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);
    sortBy.addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilter = filterStatus.value;

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const slug = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const status = row.querySelector('.badge-status').classList.contains('badge-success') ? 'active' : 'inactive';

            const matchesSearch = name.includes(searchTerm) || slug.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const deleteSelected = document.getElementById('deleteSelected');
    const selectedCount = document.getElementById('selectedCount');

    selectAll?.addEventListener('change', function() {
        rowCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const count = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
        selectedCount.textContent = count;
        deleteSelected.style.display = count > 0 ? 'block' : 'none';
        selectAll.checked = count === rowCheckboxes.length && count > 0;
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection