<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SOLID TECH - Chuyên giày chính hãng')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* Fix header luôn ở trên cùng */
        header, .navbar {
            position: sticky !important;
            top: 0;
            z-index: 1050 !important;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* ✅ FIX: Alert notifications - Tự động tắt */
        .alert-fixed {
            position: fixed;
            top: 80px;
            right: 20px;
            min-width: 350px;
            max-width: 500px;
            z-index: 9999 !important;
            animation: slideInRight 0.4s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 1;
            transition: opacity 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(120%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ✅ FIX: Fade out animation */
        .alert-fixed.fade-out {
            opacity: 0;
            transform: translateX(120%);
            transition: all 0.3s ease-out;
        }

        /* Back to top button */
        #back-to-top {
            display: none;
            width: 50px;
            height: 50px;
            z-index: 1000;
            transition: all 0.3s;
        }

        #back-to-top:hover {
            transform: translateY(-5px);
        }

        /* ✅ THÊM: Progress bar cho alert */
        .alert-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0,0,0,0.2);
            width: 100%;
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('user.layouts.header')

    <!-- ✅ FIX: Flash Messages - Tự động tắt sau 5 giây -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-fixed" role="alert" data-auto-dismiss="true">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Thành công!</strong> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="alert-progress"></div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-fixed" role="alert" data-auto-dismiss="true">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Lỗi!</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="alert-progress"></div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show alert-fixed" role="alert" data-auto-dismiss="true">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong>Cảnh báo!</strong> {!! session('warning') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="alert-progress"></div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show alert-fixed" role="alert" data-auto-dismiss="true">
            <i class="bi bi-info-circle-fill me-2"></i>
            {!! session('info') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="alert-progress"></div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main id="main-content">
        @yield('body')
    </main>
    
    <!-- Footer -->
    @include('user.layouts.footers')
    
    <!-- Back to Top Button -->
    <button id="back-to-top" class="btn btn-dark rounded-circle position-fixed bottom-0 end-0 m-4">
        <i class="bi bi-arrow-up"></i>
    </button>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- ✅ FIX: Custom Scripts - Tự động tắt alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ FIX: Tự động ẩn alerts sau 5 giây
            const alerts = document.querySelectorAll('.alert-fixed[data-auto-dismiss="true"]');
            
            alerts.forEach(function(alert) {
                // Set timeout để tự động ẩn
                const autoHideTimer = setTimeout(function() {
                    hideAlert(alert);
                }, 5000); // 5 giây
                
                // Nếu user click X, hủy timer
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        clearTimeout(autoHideTimer);
                        hideAlert(alert);
                    });
                }
            });
            
            // ✅ Function ẩn alert mượt mà
            function hideAlert(alert) {
                // Thêm class fade-out
                alert.classList.add('fade-out');
                
                // Sau 300ms (thời gian animation), xóa element khỏi DOM
                setTimeout(function() {
                    // Bootstrap dismiss
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 300);
            }

            // Back to top button
            const backToTop = document.getElementById('back-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            });
            
            if (backToTop) {
                backToTop.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>