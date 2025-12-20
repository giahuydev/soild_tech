<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .email-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .success-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        .email-body {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .order-summary {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .order-info:last-child {
            border-bottom: none;
        }
        
        .order-info strong {
            color: #333;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #000;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .product-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }
        
        .product-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        
        .total-section {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: right;
        }
        
        .total-label {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .total-price {
            font-size: 28px;
            color: #dc3545;
            font-weight: bold;
        }
        
        .customer-info {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .customer-info h3 {
            color: #0066cc;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .info-row {
            margin-bottom: 10px;
            color: #333;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        
        .cta-button:hover {
            opacity: 0.9;
        }
        
        .note-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .email-footer {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer-links {
            margin: 20px 0;
        }
        
        .footer-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: white;
            text-decoration: none;
        }
        
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
            
            .product-table {
                font-size: 14px;
            }
            
            .product-img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="success-icon">✅</div>
            <h1>ĐẶT HÀNG THÀNH CÔNG!</h1>
            <p>Cảm ơn bạn đã mua hàng tại SOLID TECH</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">
                Xin chào <strong>{{ $order->user_name }}</strong>,
            </p>
            
            <p>
                Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý. 
                Dưới đây là thông tin chi tiết đơn hàng:
            </p>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="order-info">
                    <span><strong>Mã đơn hàng:</strong></span>
                    <span style="color: #667eea; font-weight: bold;">#{{ $order->id }}</span>
                </div>
                <div class="order-info">
                    <span><strong>Ngày đặt:</strong></span>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="order-info">
                    <span><strong>Trạng thái:</strong></span>
                    <span>
                        <span class="badge badge-warning">{{ $order->status_label }}</span>
                    </span>
                </div>
                <div class="order-info">
                    <span><strong>Thanh toán:</strong></span>
                    <span>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </span>
                </div>
                <div class="order-info">
                    <span><strong>Phương thức:</strong></span>
                    <span>{{ $order->payment_method_label }}</span>
                </div>
            </div>

            <!-- Products -->
            <h3 style="color: #333; margin: 30px 0 15px;">📦 Sản phẩm đã đặt:</h3>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Màu</th>
                        <th>SL</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @php
                                    $imgSrc = 'https://via.placeholder.com/60x60/f8f9fa/6c757d?text=No+Image';
                                    
                                    if (!empty($item->product_img_thumbnail)) {
                                        if (str_starts_with($item->product_img_thumbnail, 'http')) {
                                            $imgSrc = $item->product_img_thumbnail;
                                        } elseif (str_starts_with($item->product_img_thumbnail, '/uploads')) {
                                            $imgSrc = asset($item->product_img_thumbnail);
                                        } else {
                                            $imgSrc = asset('uploads/products/' . $item->product_img_thumbnail);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $item->product_name }}" class="product-img">
                                <div>
                                    <strong>{{ $item->product_name }}</strong>
                                    <br>
                                    <small style="color: #666;">SKU: {{ $item->product_sku }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->variant_size_name }}</td>
                        <td>{{ $item->variant_color_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td><strong>{{ number_format($item->item_total) }}đ</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total -->
            <div class="total-section">
                <div class="total-label">Tổng cộng:</div>
                <div class="total-price">{{ number_format($order->total_price) }}đ</div>
                <small style="color: #666;">🚚 Miễn phí vận chuyển</small>
            </div>

            <!-- Customer Info -->
            <div class="customer-info">
                <h3>📍 Thông tin nhận hàng</h3>
                <div class="info-row">
                    <strong>Họ tên:</strong> {{ $order->user_name }}
                </div>
                <div class="info-row">
                    <strong>Điện thoại:</strong> {{ $order->user_phone }}
                </div>
                <div class="info-row">
                    <strong>Email:</strong> {{ $order->user_email }}
                </div>
                <div class="info-row">
                    <strong>Địa chỉ:</strong> {{ $order->user_address }}
                </div>
                @if($order->user_note)
                <div class="info-row">
                    <strong>Ghi chú:</strong> {{ $order->user_note }}
                </div>
                @endif
            </div>

            <!-- Note -->
            <div class="note-box">
                <strong>⚠️ Lưu ý:</strong> 
                Đơn hàng của bạn đang được xử lý. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất 
                để xác nhận và giao hàng. Thời gian giao hàng dự kiến: <strong>2-3 ngày làm việc</strong>.
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('user.orders.show', $order->id) }}" class="cta-button">
                    Xem chi tiết đơn hàng
                </a>
            </div>

            <p style="color: #666; margin-top: 30px;">
                Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi:
            </p>
            <p style="color: #333;">
                📞 Hotline: <strong>1900.633.349</strong><br>
                📧 Email: <strong>support@solidtech.com</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <h3>SOLID TECH</h3>
            <p>Chuyên cung cấp giày thể thao chính hãng</p>
            
            <div class="footer-links">
                <a href="{{ url('/') }}">Trang chủ</a> |
                <a href="{{ url('/products') }}">Sản phẩm</a> |
                <a href="{{ url('/contact') }}">Liên hệ</a>
            </div>

            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
            </div>

            <p style="font-size: 12px; color: #95a5a6; margin-top: 20px;">
                © 2025 SOLID TECH. All rights reserved.<br>
                Email này được gửi tự động, vui lòng không trả lời.
            </p>
        </div>
    </div>
</body>
</html>