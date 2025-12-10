@extends('admin.admin')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thêm Danh mục Mới</h6>
    </div>
    <div class="card-body">
        {{-- Hiển thị lỗi validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Tên Danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required placeholder="Nhập tên danh mục...">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Nhập mô tả (tùy chọn)...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="activeCheck" 
                       value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                <label class="form-check-label" for="activeCheck">Hiển thị ngay</label>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Lưu lại
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </form>
    </div>
</div>
@endsection