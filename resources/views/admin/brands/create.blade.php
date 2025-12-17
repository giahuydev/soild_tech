@extends('admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}" class="text-decoration-none">Thương hiệu</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Thêm thương hiệu mới</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-9">
            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
                        @csrf
                        
                        <!-- Brand Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-dark">
                                Tên thương hiệu <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Nhập tên thương hiệu..." 
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tên thương hiệu sẽ hiển thị trên trang web</small>
                        </div>

                        <!-- Logo Upload -->
                        <div class="mb-4">
                            <label for="logo" class="form-label fw-semibold text-dark">Logo thương hiệu</label>
                            
                            <div class="logo-upload-wrapper">
                                <div class="logo-preview" id="logoPreview">
                                    <div class="logo-placeholder">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="mb-1 fw-semibold">Kéo thả hoặc click để tải logo</p>
                                        <small class="text-muted">PNG, JPG, GIF (Max 2MB)</small>
                                    </div>
                                    <img id="previewImage" src="" alt="Preview" style="display: none;">
                                </div>
                                <input type="file" 
                                       class="form-control d-none" 
                                       id="logo" 
                                       name="logo" 
                                       accept="image/*"
                                       onchange="previewLogo(event)">
                                <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('logo').click()">
                                    <i class="fas fa-upload me-2"></i>Chọn file
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm mt-3 ms-2" id="removeLogo" style="display: none;" onclick="removeLogo()">
                                    <i class="fas fa-times me-2"></i>Xóa
                                </button>
                            </div>
                            @error('logo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-dark">Mô tả</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Nhập mô tả về thương hiệu...">{{ old('description') }}</textarea>
                            <small class="text-muted">Mô tả ngắn gọn về thương hiệu</small>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Hiển thị thương hiệu trên website
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-2"></i>Lưu lại
                            </button>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Tips -->
        <div class="col-xl-4 col-lg-3">
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Gợi ý
                    </h6>
                    <ul class="list-unstyled mb-0 tips-list">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Logo nên có nền trong suốt (PNG)</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Kích thước khuyến nghị: 500x500px</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Dung lượng tối đa: 2MB</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Định dạng: PNG, JPG, GIF</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-bolt text-primary me-2"></i>Hành động nhanh
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Xem danh sách
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('brandForm').reset(); removeLogo();">
                            <i class="fas fa-redo me-2"></i>Làm mới form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Logo Upload Styling */
.logo-upload-wrapper {
    position: relative;
}

.logo-preview {
    width: 100%;
    height: 250px;
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.logo-preview:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.logo-placeholder {
    text-align: center;
}

#previewImage {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* Form Controls */
.form-control:focus,
.form-select:focus,
.form-check-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

/* Switch Toggle */
.form-check-input {
    width: 3rem;
    height: 1.5rem;
    margin-left: -3rem;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

/* Buttons */
.btn {
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-success {
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

/* Tips List */
.tips-list li {
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.tips-list li:hover {
    background-color: #f3f4f6;
}

/* Breadcrumb */
.breadcrumb-item a {
    color: #6b7280;
}

.breadcrumb-item a:hover {
    color: #3b82f6;
}

/* Responsive */
@media (max-width: 768px) {
    .logo-preview {
        height: 200px;
    }
}
</style>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImage');
            const placeholder = document.querySelector('.logo-placeholder');
            const removeBtn = document.getElementById('removeLogo');
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
        }
        reader.readAsDataURL(file);
    }
}

function removeLogo() {
    const input = document.getElementById('logo');
    const preview = document.getElementById('previewImage');
    const placeholder = document.querySelector('.logo-placeholder');
    const removeBtn = document.getElementById('removeLogo');
    
    input.value = '';
    preview.src = '';
    preview.style.display = 'none';
    placeholder.style.display = 'block';
    removeBtn.style.display = 'none';
}

// Drag and drop functionality
const logoPreview = document.getElementById('logoPreview');
const logoInput = document.getElementById('logo');

logoPreview.addEventListener('click', () => {
    logoInput.click();
});

logoPreview.addEventListener('dragover', (e) => {
    e.preventDefault();
    logoPreview.style.borderColor = '#3b82f6';
    logoPreview.style.backgroundColor = '#eff6ff';
});

logoPreview.addEventListener('dragleave', (e) => {
    e.preventDefault();
    logoPreview.style.borderColor = '#d1d5db';
    logoPreview.style.backgroundColor = '#f9fafb';
});

logoPreview.addEventListener('drop', (e) => {
    e.preventDefault();
    logoPreview.style.borderColor = '#d1d5db';
    logoPreview.style.backgroundColor = '#f9fafb';
    
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        logoInput.files = e.dataTransfer.files;
        previewLogo({ target: { files: [file] } });
    }
});
</script>
@endsection 