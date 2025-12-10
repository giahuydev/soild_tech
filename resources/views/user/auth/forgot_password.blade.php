@extends('user.layouts.app')

@section('body')
<div class="forgot-password-container">
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="forgot-card">
                    <!-- Animated Icon -->
                    <div class="text-center mb-4">
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div class="icon-pulse"></div>
                        </div>
                    </div>
                    
                    <h2 class="text-center mb-2 title-gradient">Quên mật khẩu?</h2>
                    <p class="text-center text-muted mb-4 subtitle">
                        Đừng lo lắng! Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu
                    </p>

                    <!-- THÔNG BÁO -->
                    @if (session('success'))
                        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                                <div class="flex-grow-1">
                                    <strong>Thành công!</strong>
                                    <div>{!! session('success') !!}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <div class="flex-grow-1">
                                    <strong>Lỗi!</strong>
                                    <div>
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('password.email') }}" method="POST" class="forgot-form">
                        @csrf
                        
                        <div class="form-floating mb-4">
                            <input type="email" 
                                   name="email" 
                                   id="emailInput"
                                   class="form-control modern-input @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="example@email.com"
                                   required 
                                   autofocus>
                            <label for="emailInput">
                                <i class="bi bi-envelope me-2"></i>Email đã đăng ký
                            </label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 mb-3">
                            <i class="bi bi-send-fill me-2"></i>
                            <span>Gửi link đặt lại mật khẩu</span>
                        </button>
                    </form>

                    <div class="divider my-4">
                        <span>hoặc</span>
                    </div>

                    <!-- QUAY LẠI ĐĂNG NHẬP -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-modern w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại đăng nhập
                        </a>
                    </div>

                    <!-- LIÊN HỆ HỖ TRỢ -->
                    <div class="support-box mt-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-headset fs-3 text-primary"></i>
                        </div>
                        <p class="text-center text-muted small mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Bạn cần hỗ trợ thêm?
                        </p>
                        <div class="text-center">
                            <a href="tel:1900633349" class="support-link">
                                <i class="bi bi-telephone-fill me-2"></i>
                                <strong>Hotline: 1900.633.349</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 15px 50px rgba(0, 0, 0, 0.15);
}

.forgot-password-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.forgot-password-container::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    50% { transform: translate(-30px, -30px) rotate(180deg); }
}

.forgot-card {
    background: white;
    border-radius: 24px;
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    z-index: 1;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Icon Animation */
.icon-wrapper {
    position: relative;
    display: inline-block;
}

.icon-circle {
    width: 100px;
    height: 100px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
    animation: bounce 2s infinite;
}

.icon-circle i {
    font-size: 3rem;
    color: white;
}

.icon-pulse {
    position: absolute;
    top: 0;
    left: 0;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--gradient-primary);
    opacity: 0.3;
    animation: pulse 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.3);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 0;
    }
}

/* Title */
.title-gradient {
    font-size: 2rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.subtitle {
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Modern Input */
.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    height: 58px;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: #f8f9ff;
}

.form-floating > label {
    padding: 1rem 1.25rem;
    color: #6c757d;
}

/* Gradient Button */
.btn-gradient {
    background: var(--gradient-primary);
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.btn-gradient:hover::before {
    left: 100%;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-gradient:active {
    transform: translateY(0);
}

/* Outline Button */
.btn-outline-modern {
    border: 2px solid #e9ecef;
    background: white;
    color: #667eea;
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-modern:hover {
    background: #f8f9ff;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

/* Divider */
.divider {
    position: relative;
    text-align: center;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e9ecef;
}

.divider span {
    position: relative;
    background: white;
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

/* Custom Alerts */
.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem;
    color: #155724;
}

.alert-danger-custom {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem;
    color: #721c24;
}

/* Support Box */
.support-box {
    background: linear-gradient(135deg, #f8f9ff 0%, #e9ecff 100%);
    border-radius: 16px;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
}

.support-link {
    color: #667eea;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.support-link:hover {
    color: #764ba2;
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
    .forgot-card {
        padding: 2rem 1.5rem;
    }
    
    .title-gradient {
        font-size: 1.75rem;
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
    }
    
    .icon-circle i {
        font-size: 2.5rem;
    }
}
</style>
@endsection