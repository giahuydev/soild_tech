@extends('admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">
                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa: {{ $product->name }}
                </h1>
                <p class="text-muted mb-0">ID: #{{ $product->id }} • SKU: {{ $product->sku ?? 'Chưa có' }}</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Thông tin cơ bản -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Tên sản phẩm <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Mã SKU</label>
                                <input type="text" 
                                       name="sku" 
                                       class="form-control @error('sku') is-invalid @enderror" 
                                       value="{{ old('sku', $product->sku) }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    Danh mục <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Thương hiệu <span class="text-danger">*</span>
                            </label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mô tả chi tiết</label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Giá bán <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="price" 
                                           id="priceInput"
                                           class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price', $product->price) }}"
                                           min="0"
                                           required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="price_sale" 
                                           id="priceSaleInput"
                                           class="form-control @error('price_sale') is-invalid @enderror" 
                                           value="{{ old('price_sale', $product->price_sale) }}"
                                           min="0">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('price_sale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ảnh sản phẩm</label>
                            
                            {{-- ✅ FIX: Sử dụng accessor image_url --}}
                            @if($product->image_url)
                            <div class="current-image mb-3" id="currentImageSection">
                                <p class="small text-muted mb-2">Ảnh hiện tại:</p>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ $product->image_url }}" 
                                         class="img-thumbnail" 
                                         style="width: 150px; height: 150px; object-fit: cover;"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/150x150/f8f9fa/6c757d?text=No+Image';">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                                            onclick="removeCurrentImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                            </div>
                            @endif

                            <input type="file" 
                                   name="img_thumbnail" 
                                   id="imageInput" 
                                   class="form-control" 
                                   accept="image/*"
                                   onchange="previewNewImage(this)">
                            
                            <div id="newImagePreview" style="display: none;" class="mt-3">
                                <p class="small text-muted mb-2">Ảnh mới:</p>
                                <img id="newPreviewImg" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   id="activeSwitch" 
                                   {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeSwitch">
                                <strong>Hiển thị sản phẩm trên website</strong>
                            </label>
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật thông tin
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý biến thể -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-success">
                            <i class="fas fa-boxes me-2"></i>Quản lý Biến thể
                        </h6>
                        <span class="badge bg-primary">{{ $product->variants->count() }} biến thể</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Form thêm biến thể mới -->
                    <form action="{{ route('admin.product_variants.store', $product->id) }}" method="POST" class="add-variant-form mb-4">
                        @csrf
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-plus-circle text-success me-2"></i>Thêm biến thể mới
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold small">Size <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="size" 
                                               class="form-control form-control-sm" 
                                               placeholder="VD: 39, 40, XL" 
                                               required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold small">Màu sắc <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="color" 
                                               class="form-control form-control-sm" 
                                               placeholder="VD: Trắng, Đen" 
                                               required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold small">Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="quantity" 
                                               class="form-control form-control-sm" 
                                               value="0" 
                                               min="0" 
                                               required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i>Thêm biến thể
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Danh sách biến thể hiện có -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Số lượng</th>
                                    <th style="width: 100px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->variants as $index => $variant)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $variant->size }}</span>
                                        </td>
                                        <td>{{ $variant->color }}</td>
                                        <td>
                                            @if($variant->quantity > 0)
                                                <span class="badge bg-success">{{ $variant->quantity }} sản phẩm</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.product_variants.destroy', $variant->id) }}" 
                                                  method="POST" 
                                                  class="d-inline variant-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-variant="Size: {{ $variant->size }}, Màu: {{ $variant->color }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                            Chưa có biến thể nào. Thêm biến thể ở form bên trên!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Thống kê - ✅ FIX: Cải thiện UI -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Thống kê
                    </h6>
                    
                    {{-- ✅ FIX: Card với background gradient đẹp --}}
                    <div class="stats-card bg-gradient-primary text-white mb-3 p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block mb-1 opacity-75">Tổng số biến thể</small>
                                <h3 class="mb-0 fw-bold">{{ $product->variants->count() }}</h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-boxes fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card bg-gradient-success text-white mb-3 p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block mb-1 opacity-75">Tổng tồn kho</small>
                                <h3 class="mb-0 fw-bold">{{ $product->variants->sum('quantity') }}</h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-warehouse fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <small class="text-muted d-block mb-2">Trạng thái hiển thị</small>
                        @if($product->is_active)
                            <span class="badge bg-success px-3 py-2 w-100">
                                <i class="fas fa-check-circle me-1"></i>Đang hiển thị
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2 w-100">
                                <i class="fas fa-eye-slash me-1"></i>Đang ẩn
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thông tin -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>Thông tin
                    </h6>
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block mb-1">Ngày tạo</small>
                        <span class="fw-semibold">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                        <span class="fw-semibold">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone - ✅ FIX: Thêm JavaScript xử lý xóa -->
            <div class="card border-danger">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
                    <h6 class="fw-bold mb-2 text-danger">Vùng nguy hiểm</h6>
                    <p class="text-muted small mb-3">Hành động này không thể hoàn tác</p>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                          method="POST" 
                          id="deleteProductForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="fas fa-trash-alt me-2"></i>Xóa sản phẩm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Form Controls */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
}

/* Switch */
.form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
    margin-left: -3rem;
}

.form-check-input:checked {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

/* Add Variant Form */
.add-variant-form .card {
    border-left: 4px solid #1cc88a;
}

/* Table */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* ✅ FIX: Stats Card với gradient đẹp */
.stats-card {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
}

.stats-icon {
    font-size: 2rem;
}
</style>

<script>
// ✅ FIX: Preview ảnh mới
function previewNewImage(input) {
    const preview = document.getElementById('newPreviewImg');
    const container = document.getElementById('newImagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}

// ✅ FIX: Xóa ảnh hiện tại
function removeCurrentImage() {
    if (confirm('Bạn có chắc muốn xóa ảnh hiện tại?')) {
        document.getElementById('currentImageSection').style.display = 'none';
        document.getElementById('removeImageFlag').value = '1';
    }
}

// ✅ FIX: Validation form
document.getElementById('productForm').addEventListener('submit', function(e) {
    const price = parseInt(document.getElementById('priceInput').value) || 0;
    const priceSale = parseInt(document.getElementById('priceSaleInput').value) || 0;
    
    if (priceSale && priceSale >= price) {
        e.preventDefault();
        alert('Giá khuyến mãi phải nhỏ hơn giá bán thường!');
        return false;
    }
});

// ✅ FIX: Xóa variant với confirm
document.querySelectorAll('.variant-delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var variantInfo = this.querySelector('button').dataset.variant;
        var message = 'Bạn có chắc muốn xóa biến thể?\n\n' + variantInfo;
        
        if (confirm(message)) {
            this.submit();
        }
    });
});

// ✅ FIX: Xóa variant với confirm
document.querySelectorAll('.variant-delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const variantInfo = this.querySelector('button').dataset.variant;
        const message = `Bạn có chắc muốn xóa biến thể?\n\n${variantInfo}`;
        
        if (confirm(message)) {
            this.submit();
        }
    });
});
</script>
@endsection