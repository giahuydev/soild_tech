@extends('user.layouts.app')

@section('body')
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-dark">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}" class="text-decoration-none text-dark">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="{{ $product->image_url ?? asset('images/no-image.png') }}" 
                    class="card-img-top" 
                    alt="{{ $product->name }}"
                    onerror="
                        if (!this.dataset.failed) {
                            this.dataset.failed = 'true';
                            this.src = 'https://via.placeholder.com/600x600/f8f9fa/6c757d?text={{ urlencode($product->name) }}';
                        }
                    ">
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold">{{ $product->name }}</h1>
            <div class="mb-3">
                <span class="badge bg-secondary me-2">{{ $product->brand->name }}</span>
                <span class="text-muted small">SKU: {{ $product->sku }}</span>
            </div>

            <div class="fs-3 mb-4">
                @if($product->price_sale)
                    <span class="text-danger fw-bold me-2">{{ number_format($product->price_sale) }}đ</span>
                    <span class="text-muted text-decoration-line-through fs-5">{{ number_format($product->price) }}đ</span>
                @else
                    <span class="fw-bold">{{ number_format($product->price) }}đ</span>
                @endif
            </div>

            <p class="text-muted mb-4">{{ $product->description }}</p>

            {{-- ✅ Form thêm vào giỏ - STOCK AWARE --}}
            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size" id="selectedSize" required>
                <input type="hidden" name="color" id="selectedColor" required>

                {{-- ✅ CHỌN MÀU TRƯỚC --}}
                <div class="mb-3">
                    <label class="fw-bold mb-2">
                        Chọn Màu: <span class="text-danger">*</span>
                        <span id="selectedColorLabel" class="text-primary"></span>
                    </label>
                    <div class="d-flex gap-2 flex-wrap" id="colorOptions">
                        @foreach($product->variants->unique('color') as $variant)
                            @php
                                // Tính tổng tồn kho của màu này
                                $colorStock = $product->variants
                                    ->where('color', $variant->color)
                                    ->sum('quantity');
                                $isColorOutOfStock = $colorStock == 0;
                            @endphp
                            <button type="button"
                                    class="btn btn-outline-secondary rounded-0 color-btn {{ $isColorOutOfStock ? 'disabled out-of-stock' : '' }}"
                                    data-color="{{ $variant->color }}"
                                    data-stock="{{ $colorStock }}"
                                    {{ $isColorOutOfStock ? 'disabled' : '' }}>
                                {{ $variant->color }}
                                @if($isColorOutOfStock)
                                    <br><small class="text-danger">(Hết)</small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ✅ CHỌN SIZE (Dynamic based on color) --}}
                <div class="mb-3">
                    <label class="fw-bold mb-2">
                        Chọn Size: <span class="text-danger">*</span>
                        <span id="selectedSizeLabel" class="text-primary"></span>
                    </label>
                    <div class="d-flex gap-2 flex-wrap" id="sizeOptions">
                        <div class="text-muted">
                            <i class="bi bi-info-circle me-2"></i>
                            Vui lòng chọn màu trước
                        </div>
                    </div>
                </div>

                {{-- ✅ THÔNG BÁO TỒN KHO --}}
                <div class="alert alert-info d-none mb-3" id="stockInfo">
                    <i class="bi bi-box-seam me-2"></i>
                    Còn lại: <strong id="stockQuantity">0</strong> sản phẩm
                </div>

                {{-- ✅ SỐ LƯỢNG --}}
                <div class="d-flex gap-3 mb-3">
                    <div class="input-group" style="width: 150px;">
                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" 
                               name="quantity" 
                               id="quantityInput"
                               class="form-control text-center" 
                               value="1" 
                               min="1" 
                               max="1"
                               readonly>
                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    
                    <button type="submit" 
                            class="btn btn-dark rounded-0 flex-grow-1 text-uppercase fw-bold"
                            id="addToCartBtn"
                            disabled>
                        <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sản phẩm liên quan --}}
    <div class="mt-5">
        <h3 class="fw-bold text-uppercase border-bottom pb-2 mb-4">Sản phẩm liên quan</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
                <div class="col-6 col-md-3">
                     @include('user.partials.product_card', ['product' => $related])
                </div>
            @endforeach
        </div>
    </div>

</div>

<style>
/* ✅ Color & Size Button Styles */
.color-btn,
.size-btn {
    min-width: 80px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.color-btn:hover:not(.disabled),
.size-btn:hover:not(.disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.color-btn.active {
    background-color: #6c757d !important;
    color: white !important;
    border-color: #6c757d !important;
}

.size-btn.active {
    background-color: #212529 !important;
    color: white !important;
    border-color: #212529 !important;
}

/* ✅ Out of Stock Styles */
.out-of-stock {
    opacity: 0.5;
    cursor: not-allowed !important;
    position: relative;
}

.out-of-stock::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #dc3545;
    transform: translateY(-50%) rotate(-15deg);
}

/* ✅ Stock Alert */
.alert-info {
    border-left: 4px solid #0dcaf0;
}

.alert-warning {
    border-left: 4px solid #ffc107;
}

/* ✅ Quantity Input */
#quantityInput {
    -moz-appearance: textfield;
    font-weight: 600;
}

#quantityInput::-webkit-outer-spin-button,
#quantityInput::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ DATA: Tất cả variants từ server
   const allVariants = @json($product->variants->values()->toArray());
    
    // ✅ STATE
    let selectedColor = null;
    let selectedSize = null;
    let currentStock = 0;
    
    // ✅ ELEMENTS
    const colorButtons = document.querySelectorAll('.color-btn');
    const sizeOptionsContainer = document.getElementById('sizeOptions');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const quantityInput = document.getElementById('quantityInput');
    const stockInfo = document.getElementById('stockInfo');
    const stockQuantity = document.getElementById('stockQuantity');
    const selectedColorLabel = document.getElementById('selectedColorLabel');
    const selectedSizeLabel = document.getElementById('selectedSizeLabel');
    
    // ✅ EVENT: Chọn màu
    colorButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            
            // Remove active từ tất cả màu
            colorButtons.forEach(b => b.classList.remove('active'));
            
            // Set active cho màu đã chọn
            this.classList.add('active');
            selectedColor = this.dataset.color;
            document.getElementById('selectedColor').value = selectedColor;
            selectedColorLabel.textContent = '(' + selectedColor + ')';
            
            // Reset size selection
            selectedSize = null;
            document.getElementById('selectedSize').value = '';
            selectedSizeLabel.textContent = '';
            
            // Render sizes cho màu này
            renderSizesForColor(selectedColor);
            
            // Disable nút thêm giỏ
            addToCartBtn.disabled = true;
            stockInfo.classList.add('d-none');
            
            // Reset quantity
            quantityInput.value = 1;
            quantityInput.max = 1;
        });
    });
    
    // ✅ FUNCTION: Render sizes dựa trên màu đã chọn
    function renderSizesForColor(color) {
        // Lọc variants theo màu và sắp xếp theo size
        const variantsForColor = allVariants
            .filter(v => v.color === color)
            .sort((a, b) => {
                // Sắp xếp số trước, chữ sau
                const aNum = parseInt(a.size);
                const bNum = parseInt(b.size);
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return aNum - bNum;
                }
                return a.size.localeCompare(b.size);
            });
        
        if (variantsForColor.length === 0) {
            sizeOptionsContainer.innerHTML = '<div class="text-danger">Không có size nào cho màu này</div>';
            return;
        }
        
        // Render size buttons
        var html = '';
        variantsForColor.forEach(function(variant) {
            var isOutOfStock = variant.quantity === 0;
            var disabled = isOutOfStock ? 'disabled' : '';
            var outOfStockClass = isOutOfStock ? 'out-of-stock' : '';
            
            html += '<button type="button" ';
            html += 'class="btn btn-outline-dark rounded-0 px-3 size-btn ' + outOfStockClass + '" ';
            html += 'data-size="' + variant.size + '" ';
            html += 'data-color="' + variant.color + '" ';
            html += 'data-stock="' + variant.quantity + '" ';
            html += disabled + '>';
            html += variant.size;
            if (isOutOfStock) {
                html += '<br><small class="text-danger">(Hết)</small>';
            }
            html += '</button>';
        });
        
        sizeOptionsContainer.innerHTML = html;
        
        // Add event listeners cho size buttons
        var sizeButtons = sizeOptionsContainer.querySelectorAll('.size-btn');
        sizeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                
                // Remove active
                sizeButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                
                // Set active
                this.classList.add('active');
                selectedSize = this.dataset.size;
                document.getElementById('selectedSize').value = selectedSize;
                selectedSizeLabel.textContent = '(' + selectedSize + ')';
                
                // Get stock
                currentStock = parseInt(this.dataset.stock);
                
                // Update UI
                updateStockInfo(currentStock);
                updateQuantityLimits(currentStock);
                
                // Enable add to cart
                addToCartBtn.disabled = false;
            });
        });
    }
    
    // ✅ FUNCTION: Update stock info
    function updateStockInfo(stock) {
        stockQuantity.textContent = stock;
        stockInfo.classList.remove('d-none');
        
        if (stock < 5) {
            stockInfo.classList.remove('alert-info');
            stockInfo.classList.add('alert-warning');
        } else {
            stockInfo.classList.remove('alert-warning');
            stockInfo.classList.add('alert-info');
        }
    }
    
    // ✅ FUNCTION: Update quantity limits
    function updateQuantityLimits(stock) {
        quantityInput.max = stock;
        quantityInput.value = 1;
    }
    
    // ✅ EVENT: Increase/Decrease quantity
    document.getElementById('increaseQty').addEventListener('click', function() {
        var qty = parseInt(quantityInput.value);
        var max = parseInt(quantityInput.max);
        
        if (qty < max) {
            quantityInput.value = qty + 1;
        } else {
            alert('Chỉ còn ' + max + ' sản phẩm trong kho!');
        }
    });
    
    document.getElementById('decreaseQty').addEventListener('click', function() {
        var qty = parseInt(quantityInput.value);
        
        if (qty > 1) {
            quantityInput.value = qty - 1;
        }
    });
    
    // ✅ FORM VALIDATION
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        if (!selectedColor || !selectedSize) {
            e.preventDefault();
            alert('Vui lòng chọn màu sắc và size!');
            return false;
        }
        
        var qty = parseInt(quantityInput.value);
        if (qty > currentStock) {
            e.preventDefault();
            alert('Chỉ còn ' + currentStock + ' sản phẩm trong kho!');
            return false;
        }
        
        if (qty < 1) {
            e.preventDefault();
            alert('Số lượng phải lớn hơn 0!');
            return false;
        }
    });
});
</script>
@endsection