@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-5 text-center">
                    <!-- Icon -->
                    <div class="mb-4">
                        <div class="email-icon-wrapper">
                            <i class="bi bi-envelope-check-fill"></i>
                        </div>
                    </div>
                    
                    <h2 class="mb-3 fw-bold">📧 Xác thực Email của bạn</h2>
                    
                    <p class="text-muted mb-4 lead">
                        Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác thực địa chỉ email của bạn 
                        bằng cách nhấp vào liên kết chúng tôi vừa gửi đến email.
                    </p>

                    <!-- THÔNG BÁO -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            Link xác thực mới đã được gửi đến email của bạn!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- HIỂN THỊ EMAIL -->
                    <div class="email-display-box mb-4">
                        <p class="mb-2 text-muted small fw-bold">
                            EMAIL ĐÃ ĐĂNG KÝ:
                        </p>
                        <p class="text-primary mb-0 fs-5 fw-bold">
                            <i class="bi bi-envelope-fill me-2"></i>
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <!-- HƯỚNG DẪN -->
                    <div class="instruction-box mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Hướng dẫn xác thực
                        </h6>
                        <ol class="text-start text-muted">
                            <li class="mb-2">Kiểm tra hộp thư đến của email <strong>{{ Auth::user()->email }}</strong></li>
                            <li class="mb-2">Tìm email từ <strong>SOLID TECH</strong> với tiêu đề "Xác thực Email"</li>
                            <li class="mb-2">Click vào nút <strong>"Xác thực Email ngay"</strong> trong email</li>
                            <li class="mb-2">Sau khi xác thực, bạn có thể đăng nhập và sử dụng đầy đủ tính năng</li>
                        </ol>
                    </div>

                    <div class="alert alert-info border-0 mb-4" role="alert">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        <strong>Mẹo:</strong> Nếu không thấy email, vui lòng kiểm tra thư mục <strong>Spam</strong> hoặc <strong>Promotions</strong>
                    </div>

                    <!-- NÚT GỬI LẠI -->
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                            <i class="bi bi-send-fill me-2"></i>
                            Gửi lại Email Xác thực
                        </button>
                    </form>

                    <p class="text-muted small mb-4">
                        <i class="bi bi-clock me-1"></i>
                        Link xác thực có hiệu lực trong <strong>60 phút</strong>
                    </p>

                    <hr class="my-4">

                    <!-- CÁC HÀNH ĐỘNG KHÁC -->
                    <div class="action-buttons">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary rounded-pill me-2 mb-2">
                            <i class="bi bi-person me-2"></i>
                            Cập nhật Thông tin
                        </a>
                        
                        <a href="/" class="btn btn-outline-info rounded-pill me-2 mb-2">
                            <i class="bi bi-house me-2"></i>
                            Về Trang chủ
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill mb-2">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </div>

                    <!-- LIÊN HỆ HỖ TRỢ -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-headset me-2"></i>
                            Cần hỗ trợ?
                        </h6>
                        <p class="mb-2">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            Hotline: <strong class="text-primary">1900.633.349</strong>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-envelope-fill text-primary me-2"></i>
                            Email: <strong class="text-primary">support@solidtech.com</strong>
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
    
    .email-icon-wrapper {
        display: inline-block;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        animation: pulse 2s infinite;
    }
    
    .email-icon-wrapper i {
        font-size: 3rem;
        color: white;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
        }
    }
    
    .email-display-box {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }
    
    .instruction-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #17a2b8;
    }
    
    .instruction-box ol {
        margin: 0;
        padding-left: 20px;
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
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
</style>
@endsection