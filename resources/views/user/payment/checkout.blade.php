@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">Thanh toán đơn hàng</h2>
    
    <form action="{{ route('payment.process') }}" method="POST" id="checkoutForm">
        @csrf
        
        <div class="row">
            <!-- Form thông tin -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Thông tin người nhận</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="user_name" class="form-control rounded-0" 
                                       value="{{ Auth::user()->name }}" required>
                                @error('user_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="user_phone" class="form-control rounded-0" 
                                       value="{{ Auth::user()->phone ?? '' }}" required>
                                @error('user_phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="user_email" class="form-control rounded-0" 
                                       value="{{ Auth::user()->email }}" required>
                                @error('user_email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea name="user_address" class="form-control rounded-0" rows="3" 
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                                @error('user_address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Ghi chú đơn hàng</label>
                                <textarea name="user_note" class="form-control rounded-0" rows="2" 
                                          placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- COD Payment -->
                        <div class="form-check mb-3 p-3 border rounded payment-option">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="cod" value="cod" checked>
                            <label class="form-check-label w-100" for="cod">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cash-coin fs-2 me-3 text-success"></i>
                                    <div>
                                        <strong class="d-block">Thanh toán khi nhận hàng (COD)</strong>
                                        <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- MoMo Payment -->
                        <div class="form-check p-3 border rounded payment-option">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="momo" value="momo">
                            <label class="form-check-label w-100" for="momo">
                                <div class="d-flex align-items-center">
                                    <!-- Logo MoMo -->
                                    <div class="me-3">
                                        <svg width="48" height="48" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <linearGradient id="momoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#A50064;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#D5006D;stop-opacity:1" />
                                                </linearGradient>
                                            </defs>
                                            <circle cx="50" cy="50" r="48" fill="url(#momoGradient)"/>
                                            <text x="50" y="72" font-family="Arial, sans-serif" font-size="52" 
                                                  font-weight="bold" fill="white" text-anchor="middle">M</text>
                                        </svg>
                                    </div>
                                    <div>
                                        <strong class="d-block">Thanh toán qua ví MoMo</strong>
                                        <small class="text-muted">Thanh toán nhanh chóng và bảo mật với MoMo</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Đơn hàng của bạn</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->variant->product;
                                $price = $product->price_sale ?? $product->price;
                                $itemTotal = $price * $item->quantity;
                            @endphp
                            <div class="d-flex align-items-start">
                                <img src="{{ $product->image_url ?? asset('images/no-image.png') }}" 
                                    alt="{{ $product->name }}" 
                                    class="rounded me-2"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                    onerror="
                                        if (!this.dataset.failed) {
                                            this.dataset.failed = 'true';
                                            this.src = 'https://via.placeholder.com/50x50/f8f9fa/6c757d?text=No+Image';
                                        }
                                    ">
                                <div>
                                    <small class="fw-bold d-block">{{ $product->name }}</small>
                                    <small class="text-muted">
                                        Size: {{ $item->variant->size }} | Màu: {{ $item->variant->color }}
                                    </small>
                                    <br><small class="text-muted">SL: {{ $item->quantity }}</small>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($totalPrice) }}đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        
                        <hr class="border-2">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <strong class="fs-5">Tổng cộng:</strong>
                            <strong class="fs-5 text-danger">{{ number_format($totalPrice) }}đ</strong>
                        </div>
                        
                        <button type="d-flex justify-content-center mt-5submit" class="btn btn-danger w-100 py-3 rounded-0 fw-bold text-uppercase">
                            Đặt hàng
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Thanh toán an toàn & bảo mật
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .payment-option {
        transition: all 0.3s;
        cursor: pointer;
    }

    .payment-option:hover {
        border-color: #000 !important;
        background-color: #f8f9fa;
    }

    .form-check-input:checked + .form-check-label {
        color: #000;
    }

    .form-check-input:checked ~ label .payment-option {
        border-color: #000 !important;
    }
</style>
@endsection 