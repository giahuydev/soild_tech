<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - SOLID TECH</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .bg-gradient-primary { 
            background-color: #4e73df; 
            background-image: linear-gradient(180deg,#4e73df 10%,#224abe 100%); 
        }
        
        /* Highlight active menu */
        .nav-item.active .nav-link {
            font-weight: 600;
        }
        
        .sidebar .nav-item .nav-link {
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-item .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- ========== SIDEBAR ========== -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            
            <!-- Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SOLID Admin</div>
            </a>
            
            <hr class="sidebar-divider my-0">
            
            <!-- Dashboard -->
            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tổng quan</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Kinh doanh</div>
            
            <!-- Categories -->
            <li class="nav-item {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Danh mục sản phẩm</span>
                </a>
            </li>
            
            <!-- Brands -->
            <li class="nav-item {{ Request::routeIs('admin.brands.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.brands.index') }}">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Thương hiệu</span>
                </a>
            </li>
            
            <!-- Products -->
            <li class="nav-item {{ Request::routeIs('admin.products.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Quản lý Sản phẩm</span>
                </a>
            </li>
            
            <!-- ✅ ĐÃ XÓA MENU "QUẢN LÝ KHO HÀNG" -->
            
            <!-- Orders -->
            <li class="nav-item {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Đơn hàng</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <!-- Sidebar Toggle -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- ========== END SIDEBAR ========== -->

        <!-- ========== CONTENT WRAPPER ========== -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- ========== TOPBAR ========== -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    
                    <!-- Sidebar Toggle (Mobile) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        
                        <div class="topbar-divider d-none d-sm-block"></div>
                        
                        <!-- User Info -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Xin chào, <strong>{{ Auth::user()->name }}</strong>
                                </span>
                                <img class="img-profile rounded-circle" 
                                     src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                     alt="{{ Auth::user()->name }}">
                            </a>
                            
                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" 
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> 
                                    Hồ sơ
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> 
                                    Đăng xuất
                                </a>
                            </div>
                        </li>
                        
                    </ul>
                </nav>
                <!-- ========== END TOPBAR ========== -->

                <!-- ========== PAGE CONTENT ========== -->
                <div class="container-fluid">
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Thành công!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Lỗi!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Cảnh báo!</strong> {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Thông tin!</strong> {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                    
                </div>
                <!-- ========== END PAGE CONTENT ========== -->

            </div>
            
            <!-- ========== FOOTER ========== -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; SOLID TECH {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- ========== END FOOTER ========== -->
            
        </div>
        <!-- ========== END CONTENT WRAPPER ========== -->
        
    </div>
    <!-- ========== END WRAPPER ========== -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- ========== LOGOUT MODAL ========== -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bạn muốn rời đi?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Chọn "Đăng xuất" bên dưới nếu bạn muốn kết thúc phiên làm việc.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        Đăng xuất
                    </a>
                    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== END LOGOUT MODAL ========== -->

    <!-- ========== SCRIPTS ========== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>
    
    <!-- Global Delete Confirmation Script -->
    <script>
        $(document).ready(function() {
            // Delete confirmation with SweetAlert
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                let confirmText = form.data('confirm-text') || 'Bạn có chắc muốn xóa?';
                
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Xóa ngay',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            
            // Auto hide alerts after 5 seconds
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
    <!-- ========== END SCRIPTS ========== -->
    
</body>
</html>