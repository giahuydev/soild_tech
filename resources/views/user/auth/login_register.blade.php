@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row bg-white p-4 shadow-sm rounded position-relative">
                
                <!-- ĐĂNG NHẬP -->
                <div class="col-md-6 pe-md-5 border-end-md">
                    <h3 class="text-center mb-4 text-uppercase fw-bold">Đăng nhập</h3>
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email đăng nhập <span class="text-danger">*</span></label>
                            <input type="email" name="mail" 
                                   class="form-control rounded-0 @error('mail') is-invalid @enderror" 
                                   value="{{ old('mail') }}" required autofocus>
                            @error('mail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="loginPassword"
                                       class="form-control rounded-0" required>
                                <button class="btn btn-outline-secondary rounded-0" type="button" 
                                        onclick="togglePassword('loginPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <button type="submit" class="btn btn-dark rounded-0 px-4 py-2 text-uppercase">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Đăng nhập
                            </button>
                            <a href="{{ route('password.request') }}" class="ms-auto text-decoration-none text-muted small">Quên mật khẩu?</a>
                        </div>
                    </form>

                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1">
                        <span class="px-2 text-muted text-uppercase small">Hoặc</span>
                        <hr class="flex-grow-1">
                    </div>

                    <div class="d-flex align-items-center my-4">
                        <div class="col-6">
                            <a href="{{ route('google.login') }}" class="btn btn-outline-danger w-100 rounded-0">
                                <i class="bi bi-google"></i> Google
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 small text-muted">
                        <i class="bi bi-info-circle"></i>
                        Nếu Quý khách có vấn đề gì thắc mắc hoặc cần hỗ trợ:<br>
                        <i class="bi bi-telephone"></i> Hotline: <strong>1900.633.349</strong><br>
                        <i class="bi bi-messenger"></i> Hoặc Inbox Facebook
                    </div>
                </div>

                <!-- NÚT OR GIỮA 2 CỘT -->
                <div class="or-circle d-none d-md-flex">Or</div>

                <!-- ĐĂNG KÝ -->
                <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
                    <h3 class="text-center mb-4 text-uppercase fw-bold">Đăng ký</h3>
                    
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="register_username" 
                                   class="form-control rounded-0 @error('register_username') is-invalid @enderror" 
                                   value="{{ old('register_username') }}" required>
                            @error('register_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" 
                                   class="form-control rounded-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" 
                                   class="form-control rounded-0 @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" 
                                   placeholder="VD: 0912345678" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="registerPassword"
                                       class="form-control rounded-0 @error('password') is-invalid @enderror" required>
                                <button class="btn btn-outline-secondary rounded-0" type="button" 
                                        onclick="togglePassword('registerPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nhắc lại mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                       class="form-control rounded-0 @error('password_confirmation') is-invalid @enderror" required>
                                <button class="btn btn-outline-secondary rounded-0" type="button" 
                                        onclick="togglePassword('confirmPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark rounded-0 px-4 py-2 text-uppercase">
                                <i class="bi bi-person-plus me-2"></i>
                                Đăng ký
                            </button>
                        </div>

                        <p class="mt-3 small text-muted">
                            <i class="bi bi-shield-check"></i>
                            Thông tin cá nhân của bạn sẽ được bảo mật và dùng để điền vào hóa đơn, 
                            giúp bạn thanh toán nhanh chóng và dễ dàng.
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6;
        }
    }

    .or-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        justify-content: center;
        align-items: center;
        font-style: italic;
        color: #6c757d;
        font-size: 0.9rem;
        z-index: 10;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #000;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: block;
    }

    .input-group .btn:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Toggle hiển thị mật khẩu
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