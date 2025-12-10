@extends('user.layouts.app')

@section('title', 'SOLID TECH - Trang chủ')

@section('body')
<!-- Hero Banner -->
<section class="hero-banner position-relative mb-5">
    <div class="position-relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1556906781-9a412961c28c?w=1920&h=600&fit=crop" 
             class="w-100" 
             style="height: 500px; object-fit: cover; filter: brightness(0.7);" 
             alt="Banner">
        
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
            <h1 class="display-3 fw-bold text-uppercase mb-3 animate__animated animate__fadeInDown">
                SOLID <span class="text-danger">X</span> TECH
            </h1>
            <p class="fs-4 mb-4 animate__animated animate__fadeInUp">
                Bộ sưu tập giày chính hãng mới nhất 2025
            </p>
            <a href="{{ route('shop.index') }}" 
               class="btn btn-white btn-lg px-5 animate__animated animate__fadeInUp animate__delay-1s">
                Khám phá ngay
            </a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase mb-2">Sản phẩm nổi bật</h2>
            <div class="bg-danger mx-auto" style="width: 80px; height: 4px;"></div>
            <p class="text-muted mt-3">Những đôi giày được yêu thích nhất</p>
        </div>
        
        <div class="row g-4">
            @foreach($hotProducts->take(8) as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('user.partials.product_card', ['product' => $product])
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('shop.hotSale') }}" class="btn btn-outline-dark btn-lg px-5 rounded-pill">
                Xem tất cả <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase mb-2">Danh mục sản phẩm</h2>
            <div class="bg-danger mx-auto" style="width: 80px; height: 4px;"></div>
        </div>
        
        <div class="row g-4">
            <!-- Men's Shoes -->
            <div class="col-md-4">
                <div class="group-cat position-relative rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?w=600&h=500&fit=crop" 
                         class="w-100" 
                         style="height: 400px; object-fit: cover;" 
                         alt="Giày Nam">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center" 
                         style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <h3 class="text-white fw-bold mb-3">GIÀY NAM</h3>
                        <a href="{{ route('shop.category', 'giay-nam') }}" 
                           class="btn btn-white rounded-pill px-4">
                            Khám phá ngay
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Women's Shoes -->
            <div class="col-md-4">
                <div class="group-cat position-relative rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600&h=500&fit=crop" 
                         class="w-100" 
                         style="height: 400px; object-fit: cover;" 
                         alt="Giày Nữ">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center" 
                         style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <h3 class="text-white fw-bold mb-3">GIÀY NỮ</h3>
                        <a href="{{ route('shop.category', 'giay-nu') }}" 
                           class="btn btn-white rounded-pill px-4">
                            Khám phá ngay
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Accessories -->
            <div class="col-md-4">
                <div class="group-cat position-relative rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512374382149-233c42b6a83b?w=600&h=500&fit=crop" 
                         class="w-100" 
                         style="height: 400px; object-fit: cover;" 
                         alt="Phụ kiện">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center" 
                         style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <h3 class="text-white fw-bold mb-3">PHỤ KIỆN</h3>
                        <a href="{{ route('shop.category', 'phu-kien') }}" 
                           class="btn btn-white rounded-pill px-4">
                            Khám phá ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <i class="bi bi-truck fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold">Giao hàng nhanh</h5>
                    <p class="text-muted small">Miễn phí ship toàn quốc</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <i class="bi bi-shield-check fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold">Chính hãng 100%</h5>
                    <p class="text-muted small">Cam kết hàng chính hãng</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <i class="bi bi-arrow-repeat fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold">Đổi trả dễ dàng</h5>
                    <p class="text-muted small">Đổi trả trong 7 ngày</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <i class="bi bi-headset fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                    <p class="text-muted small">Tư vấn nhiệt tình</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush