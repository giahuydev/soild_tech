@extends('user.layouts.app')

@section('body')
<div class="container py-5">
    <h1 class="fw-bold mb-4">
        <i class="bi bi-cart3"></i> Giỏ hàng của bạn
    </h1>

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-4 text-muted">Giỏ hàng trống</h3>
            <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <a href="{{ route('shop.index') }}" class="btn btn-dark rounded-0 mt-3">
                <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0 ps-4">Sản phẩm</th>
                                        <th scope="col" class="border-0 text-center">Đơn giá</th>
                                        <th scope="col" class="border-0 text-center">Số lượng</th>
                                        <th scope="col" class="border-0 text-center">Thành tiền</th>
                                        <th scope="col" class="border-0 text-center pe-4">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php
                                            $product = $item->variant->product;
                                            $price = $product->price_sale ?? $product->price;
                                            $itemTotal = $price * $item->quantity;
                                        @endphp
                                        <tr data-item-id="{{ $item->id }}">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="card border-0 shadow-sm">
                                                        <img src="{{ $product->image_url ? asset('storage/products/' . $product->image_url) : asset('images/no-image.png') }}" 
                                                            class="card-img-top" 
                                                            alt="{{ $product->name }}"
                                                            onerror="
                                                                if (!this.dataset.failed) {
                                                                    this.dataset.failed = 'true';
                                                                    this.src = 'https://via.placeholder.com/600x600/f8f9fa/6c757d?text={{ urlencode($product->name) }}';
                                                                }
                                                            ">
                                                        </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                                        <small class="text-muted">
                                                            Size: <span class="fw-bold">{{ $item->variant->size }}</span> | 
                                                            Màu: <span class="fw-bold">{{ $item->variant->color }}</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-danger item-price" data-price="{{ $price }}">
                                                    {{ number_format($price) }}đ
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm justify-content-center" style="width: 120px; margin: 0 auto;">
                                                    <button class="btn btn-outline-secondary btn-decrease" type="button">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center quantity-input" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->variant->quantity }}"
                                                           data-item-id="{{ $item->id }}">
                                                    <button class="btn btn-outline-secondary btn-increase" type="button">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Còn {{ $item->variant->quantity }} sản phẩm
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold item-total">{{ number_format($itemTotal) }}đ</span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-dark rounded-0">
                        <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-0" 
                                onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                            <i class="bi bi-trash me-2"></i> Xóa toàn bộ
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span id="subtotal">{{ number_format($totalPrice) }}đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-5 text-danger" id="total">{{ number_format($totalPrice) }}đ</span>
                        </div>
                        
                        <a href="{{ route('payment.checkout') }}" class="btn btn-dark rounded-0 w-100 py-3 fw-bold text-uppercase">
                            Tiến hành thanh toán
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>

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
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý nút cộng/trừ
    document.querySelectorAll('.btn-decrease, .btn-increase').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.closest('tr').querySelector('.quantity-input');
            let value = parseInt(input.value);
            const max = parseInt(input.max);

            if (this.classList.contains('btn-decrease') && value > 1) {
                value--;
            } else if (this.classList.contains('btn-increase') && value < max) {
                value++;
            }

            input.value = value;
            updateQuantity(input);
        });
    });

    // Xử lý nhập trực tiếp
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            let value = parseInt(this.value);
            const max = parseInt(this.max);

            if (value < 1) value = 1;
            if (value > max) value = max;

            this.value = value;
            updateQuantity(this);
        });
    });

    // Gửi request cập nhật số lượng
    function updateQuantity(input) {
        const itemId = input.dataset.itemId;
        const quantity = input.value;

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('quantity', quantity);

        fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    }
});
</script>
@endpush