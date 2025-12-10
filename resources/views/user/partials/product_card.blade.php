<div class="product-card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white">
    {{-- Image Section --}}
    <div class="position-relative overflow-hidden" style="height: 250px; background: #f8f9fa;">
        <a href="{{ route('shop.detail', $product->slug) }}" class="d-block h-100 w-100 text-decoration-none">
            @php
                // ĐƠN GIẢN: Xử lý ảnh
                $imageFile = $product->img_thumbnail ?? '';
                
                if (!empty($imageFile) && file_exists(public_path('storage/products/' . $imageFile))) {
                    // Ảnh tồn tại - dùng asset()
                    $imageUrl = asset('storage/products/' . $imageFile);
                } else {
                    // Ảnh không tồn tại - dùng placeholder đơn giản
                    $shortName = substr($product->name, 0, 12);
                    $imageUrl = 'https://via.placeholder.com/400x400/f8f9fa/6c757d?text=' . urlencode($shortName);
                }
            @endphp

            <div class="h-100 w-100 d-flex align-items-center justify-content-center p-3">
                <img src="{{ $product->image_url ? asset('storage/products/' . $product->image_url) : 'https://via.placeholder.com/400x400/f8f9fa/6c757d?text=No+Image' }}"
                class="product-image"
                style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s ease;"
                alt="{{ $product->name }}"
                loading="lazy"
                onerror="
                    if (!this.dataset.failed) {
                        this.dataset.failed = 'true';
                        this.src = 'https://via.placeholder.com/400x400/f8f9fa/6c757d?text=No+Image';
                    }
                ">
            </div>
        </a>

        {{-- Discount Badge --}}
        @if($product->price_sale && $product->price_sale < $product->price)
            @php
                $discount = round((($product->price - $product->price_sale) / $product->price) * 100);
            @endphp
            <span class="badge bg-danger position-absolute top-0 start-0 m-2 rounded-pill fs-6 fw-bold">
                -{{ $discount }}%
            </span>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="card-body p-3">
        {{-- Brand --}}
        <div class="text-muted text-uppercase small mb-1">
            {{ $product->brand->name ?? 'SOLD TECH' }}
        </div>

        {{-- Product Name --}}
        <h6 class="card-title mb-2" style="height: 40px; overflow: hidden;">
            <a href="{{ route('shop.detail', $product->slug) }}"
               class="text-dark text-decoration-none"
               title="{{ $product->name }}">
               {{ Str::limit($product->name, 45) }}
            </a>
        </h6>

        {{-- Price --}}
        <div class="price-box mb-2">
            @if($product->price_sale && $product->price_sale < $product->price)
                <span class="text-danger fw-bold fs-5 me-2">
                    {{ number_format($product->price_sale) }}đ
                </span>
                <span class="text-muted text-decoration-line-through small">
                    {{ number_format($product->price) }}đ
                </span>
            @else
                <span class="fw-bold text-dark fs-5">
                    {{ number_format($product->price) }}đ
                </span>
            @endif
        </div>

        {{-- Stock --}}
        @if($product->variants->sum('quantity') > 0)
            <small class="text-success">
                <i class="bi bi-check-circle-fill"></i> Còn hàng
            </small>
        @else
            <small class="text-danger">
                <i class="bi bi-x-circle-fill"></i> Hết hàng
            </small>
        @endif
    </div>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0 !important;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-card .card-title a:hover {
        color: #dc3545 !important;
    }
</style>