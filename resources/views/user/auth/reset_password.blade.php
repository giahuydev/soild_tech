@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <!-- Icon -->
                    <div class="text-center mb-4">
                        <div class="reset-icon-wrapper">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-center mb-3 fw-bold">🔒 Đặt lại mật khẩu</h3>
                    
                    <p class="text-muted text-center mb-4">
                        Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
                    </p>

                    <!-- THÔNG BÁO -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {!! session('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email ?? request('email') }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-2"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   value="{{ $email ?? request('email') }}" 
                                   readonly style="background-color: #f8f9fa;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-key me-2"></i>Mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" name="password" id="newPassword"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       placeholder="Tối thiểu 6 ký tự"
                                       required autofocus>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('newPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-shield-check me-2"></i>Xác nhận mật khẩu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                       class="form-control form-control-lg" 
                                       placeholder="Nhập lại mật khẩu mới"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('confirmPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại đăng nhập
                        </a>
                    </div>
                    
                    <!-- LIÊN HỆ HỖ TRỢ -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Bạn cần hỗ trợ?
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            Hotline: <strong class="text-primary">1900.633.349</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    .reset-icon-wrapper {
        display: inline-flex;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }
    
    .reset-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(118, 75, 162, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(118, 75, 162, 0);
        }
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #0d6efd;
    }
    
    .btn {
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }

    .input-group .btn:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection