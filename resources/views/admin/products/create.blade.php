@extends('admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">
                    <i class="fas fa-plus-circle text-success me-2"></i>Thêm Sản phẩm mới
                </h1>
                <p class="text-muted mb-0">Điền đầy đủ thông tin sản phẩm và biến thể</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
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
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Tên sản phẩm <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="VD: Nike Air Max 270"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Mã SKU</label>
                                <div class="input-group">
                                    <input type="text" 
                                           name="sku" 
                                           id="skuInput"
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           placeholder="VD: SP001"
                                           value="{{ old('sku') }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="generateSKU()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
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
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Mô tả chi tiết</label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4"
                                      placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Giá bán -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-tag me-2"></i>Giá bán
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-0">
                                <label class="form-label fw-semibold">
                                    Giá bán <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="price" 
                                           id="priceInput"
                                           class="form-control @error('price') is-invalid @enderror" 
                                           placeholder="0"
                                           value="{{ old('price') }}"
                                           min="0"
                                           required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-0">
                                <label class="form-label fw-semibold">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="price_sale" 
                                           id="priceSaleInput"
                                           class="form-control @error('price_sale') is-invalid @enderror" 
                                           placeholder="0"
                                           value="{{ old('price_sale') }}"
                                           min="0">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <small class="text-muted">Để trống nếu không giảm giá</small>
                                @error('price_sale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biến thể sản phẩm -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-boxes me-2"></i>Biến thể sản phẩm (Size & Màu)
                            </h6>
                            <button type="button" class="btn btn-sm btn-success" onclick="addVariantRow()">
                                <i class="fas fa-plus me-1"></i>Thêm biến thể
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="variantsContainer">
                            <!-- Variant row template sẽ được thêm vào đây -->
                        </div>
                        
                        <div class="alert alert-info mb-0 mt-3" id="noVariantsAlert">
                            <i class="fas fa-info-circle me-2"></i>
                            Nhấn nút <strong>"Thêm biến thể"</strong> để thêm size và màu sắc cho sản phẩm
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Ảnh sản phẩm -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-image me-2"></i>Ảnh sản phẩm
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <input type="file" 
                               name="img_thumbnail" 
                               id="imageInput" 
                               class="d-none" 
                               accept="image/*"
                               onchange="previewImage(this)"
                               required>
                        
                        <div class="image-preview" id="imagePreview" onclick="document.getElementById('imageInput').click()">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <p class="mb-1 fw-semibold">Click để tải ảnh lên</p>
                                <small class="text-muted">JPG, PNG, GIF (Max: 2MB)</small>
                            </div>
                            <img id="previewImg" src="" alt="Preview" style="display: none;">
                        </div>
                        
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-3" id="removeImageBtn" style="display: none;" onclick="removeImage()">
                            <i class="fas fa-trash me-1"></i>Xóa ảnh
                        </button>
                    </div>
                </div>

                <!-- Cài đặt -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-cog me-2"></i>Cài đặt
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   id="activeSwitch" 
                                   checked>
                            <label class="form-check-label" for="activeSwitch">
                                <strong>Hiển thị sản phẩm</strong>
                                <small class="d-block text-muted">Sản phẩm sẽ hiển thị trên website</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-chart-line text-warning me-2"></i>Thống kê nhanh
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Số biến thể:</span>
                            <span class="fw-bold" id="variantCount">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tổng tồn kho:</span>
                            <span class="fw-bold text-success" id="totalStock">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Tất cả thông tin sẽ được mã hóa và bảo mật
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Lưu sản phẩm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Form Controls */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
}

/* Image Upload */
.image-preview {
    width: 100%;
    aspect-ratio: 1;
    border: 3px dashed #dee2e6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    background: #f8f9fa;
    position: relative;
}

.image-preview:hover {
    border-color: #4e73df;
    background: #f8f9ff;
}

.upload-placeholder {
    text-align: center;
    padding: 2rem;
}

#previewImg {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

/* Variant Row */
.variant-row {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.variant-row:hover {
    border-color: #4e73df;
    background: #f8f9ff;
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

/* Buttons */
.btn-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
}
</style>

<script>
let variantIndex = 0;

function generateSKU() {
    const randomSKU = 'SP' + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    document.getElementById('skuInput').value = randomSKU;
}

function previewImage(input) {
    const preview = document.getElementById('previewImg');
    const placeholder = document.getElementById('uploadPlaceholder');
    const removeBtn = document.getElementById('removeImageBtn');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const preview = document.getElementById('previewImg');
    const placeholder = document.getElementById('uploadPlaceholder');
    const removeBtn = document.getElementById('removeImageBtn');
    const input = document.getElementById('imageInput');
    
    preview.src = '';
    preview.style.display = 'none';
    placeholder.style.display = 'block';
    removeBtn.style.display = 'none';
    input.value = '';
}

function addVariantRow() {
    const container = document.getElementById('variantsContainer');
    const alert = document.getElementById('noVariantsAlert');
    
    const variantHtml = `
        <div class="variant-row" id="variant-${variantIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary">Biến thể #${variantIndex + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariant(${variantIndex})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label small fw-semibold">Size <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="variants[${variantIndex}][size]" 
                           class="form-control form-control-sm" 
                           placeholder="VD: 39, 40, XL" 
                           required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label small fw-semibold">Màu sắc <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="variants[${variantIndex}][color]" 
                           class="form-control form-control-sm" 
                           placeholder="VD: Trắng, Đen" 
                           required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label small fw-semibold">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="variants[${variantIndex}][quantity]" 
                           class="form-control form-control-sm variant-quantity" 
                           value="0" 
                           min="0"
                           onchange="updateStats()"
                           required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHtml);
    alert.style.display = 'none';
    variantIndex++;
    updateStats();
}

function removeVariant(index) {
    const variant = document.getElementById(`variant-${index}`);
    variant.remove();
    
    const container = document.getElementById('variantsContainer');
    const alert = document.getElementById('noVariantsAlert');
    
    if (container.children.length === 0) {
        alert.style.display = 'block';
    }
    
    updateStats();
}

function updateStats() {
    const quantities = document.querySelectorAll('.variant-quantity');
    const variantCount = quantities.length;
    let totalStock = 0;
    
    quantities.forEach(input => {
        totalStock += parseInt(input.value) || 0;
    });
    
    document.getElementById('variantCount').textContent = variantCount;
    document.getElementById('totalStock').textContent = totalStock;
}

// Form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const price = parseInt(document.getElementById('priceInput').value) || 0;
    const priceSale = parseInt(document.getElementById('priceSaleInput').value) || 0;
    
    if (priceSale && priceSale >= price) {
        e.preventDefault();
        alert('Giá khuyến mãi phải nhỏ hơn giá bán thường!');
        return false;
    }
    
    const variants = document.querySelectorAll('.variant-row');
    if (variants.length === 0) {
        if (!confirm('Bạn chưa thêm biến thể nào. Tiếp tục lưu?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
@endsection