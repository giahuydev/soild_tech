<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white;">
                            <h1 style="margin: 0; font-size: 28px;">✅ ĐẶT HÀNG THÀNH CÔNG!</h1>
                            <p style="margin: 10px 0 0 0;">Cảm ơn bạn đã mua hàng tại SOLID TECH</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333;">
                                Xin chào <strong>{{ $order->user_name }}</strong>,
                            </p>
                            
                            <p style="color: #666;">
                                Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý. 
                                Dưới đây là thông tin chi tiết đơn hàng:
                            </p>

                            <!-- Order Info -->
                            <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0;">
                                <tr>
                                    <td style="color: #666;"><strong>Mã đơn hàng:</strong></td>
                                    <td style="text-align: right; color: #667eea; font-weight: bold;">#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Ngày đặt:</strong></td>
                                    <td style="text-align: right;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Trạng thái:</strong></td>
                                    <td style="text-align: right;">
                                        <span style="background: #ffc107; color: #000; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Phương thức:</strong></td>
                                    <td style="text-align: right;">{{ $order->payment_method_label }}</td>
                                </tr>
                            </table>

                            <!-- Products -->
                            <h3 style="color: #333; margin-top: 30px;">📦 Sản phẩm đã đặt:</h3>
                            <table width="100%" cellpadding="10" cellspacing="0" style="border: 1px solid #dee2e6;">
                                <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6;">Sản phẩm</th>
                                        <th style="text-align: center; padding: 12px; border-bottom: 2px solid #dee2e6;">SL</th>
                                        <th style="text-align: right; padding: 12px; border-bottom: 2px solid #dee2e6;">Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">
                                            <strong>{{ $item->product_name }}</strong><br>
                                            <small style="color: #666;">Size: {{ $item->variant_size_name }} | Màu: {{ $item->variant_color_name }}</small>
                                        </td>
                                        <td style="text-align: center; padding: 12px; border-bottom: 1px solid #dee2e6;">{{ $item->quantity }}</td>
                                        <td style="text-align: right; padding: 12px; border-bottom: 1px solid #dee2e6;">
                                            <strong>{{ number_format($item->item_total) }}đ</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Total -->
                            <table width="100%" cellpadding="15" cellspacing="0" style="background-color: #fff3cd; margin: 20px 0; border-radius: 5px;">
                                <tr>
                                    <td style="text-align: right;">
                                        <div style="font-size: 18px; color: #333; margin-bottom: 10px;">Tổng cộng:</div>
                                        <div style="font-size: 28px; color: #dc3545; font-weight: bold;">{{ number_format($order->total_price) }}đ</div>
                                        <small style="color: #666;">🚚 Miễn phí vận chuyển</small>
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping Info -->
                            <table width="100%" cellpadding="15" cellspacing="0" style="background-color: #e7f3ff; margin: 20px 0; border-radius: 5px;">
                                <tr>
                                    <td>
                                        <h3 style="color: #0066cc; margin-top: 0;">📍 Thông tin nhận hàng</h3>
                                        <p style="margin: 5px 0; color: #333;"><strong>Họ tên:</strong> {{ $order->user_name }}</p>
                                        <p style="margin: 5px 0; color: #333;"><strong>Điện thoại:</strong> {{ $order->user_phone }}</p>
                                        <p style="margin: 5px 0; color: #333;"><strong>Địa chỉ:</strong> {{ $order->user_address }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Note -->
                            <table width="100%" cellpadding="15" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; margin: 20px 0;">
                                <tr>
                                    <td>
                                        <strong>⚠️ Lưu ý:</strong> 
                                        Đơn hàng của bạn đang được xử lý. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #666; margin-top: 30px;">
                                Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi:<br>
                                📞 Hotline: <strong>1900.633.349</strong><br>
                                📧 Email: <strong>support@solidtech.com</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #2c3e50; color: white; padding: 30px; text-align: center;">
                            <h3 style="margin: 0 0 10px 0;">SOLID TECH</h3>
                            <p style="margin: 0; font-size: 14px;">Chuyên cung cấp giày thể thao chính hãng</p>
                            <p style="margin: 20px 0 0 0; font-size: 12px; color: #95a5a6;">
                                © 2025 SOLID TECH. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>