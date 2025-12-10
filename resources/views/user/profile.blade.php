@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">
        <i class="bi bi-person-badge"></i> Thông tin tài khoản
    </h2>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Cập nhật thông tin</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hiển thị <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Đổi mật khẩu (tùy chọn)</h6>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" style="width: 100px; height: 100px;">
                        @else
                            <i class="bi bi-person-circle" style="font-size: 100px;"></i>
                        @endif
                    </div>
                    <h5 class="fw-bold">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2">
                            <strong>Vai trò:</strong> 
                            @if($user->role == 1)
                                <span class="badge bg-danger">Quản trị viên</span>
                            @else
                                <span class="badge bg-primary">Khách hàng</span>
                            @endif
                        </p>
                        <p class="mb-2">
                            <strong>Tham gia:</strong> {{ $user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection