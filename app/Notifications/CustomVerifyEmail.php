<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmailBase
{
    /**
     * Tạo URL xác thực có hạn (60 phút)
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Email xác thực tiếng Việt - Đẹp và chuyên nghiệp
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🔐 Xác thực Email - SOLID TECH')
            ->greeting('Xin chào ' . $notifiable->name . '! 👋')
            ->line('Cảm ơn bạn đã đăng ký tài khoản tại **SOLID TECH** - Cửa hàng giày chính hãng.')
            ->line('Để hoàn tất đăng ký và có thể đăng nhập, vui lòng xác thực địa chỉ email của bạn bằng cách nhấp vào nút bên dưới:')
            ->action('✅ Xác thực Email ngay', $verificationUrl)
            ->line('**Link xác thực này sẽ hết hạn sau 60 phút.**')
            ->line('---')
            ->line('🔒 **Lưu ý bảo mật:**')
            ->line('• Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.')
            ->line('• Không chia sẻ link này với bất kỳ ai.')
            ->line('---')
            ->line('📞 **Cần hỗ trợ?**')
            ->line('Hotline: **1900.633.349** | Email: support@solidtech.com')
            ->salutation('Trân trọng,  
**Đội ngũ SOLID TECH** 🏃‍♂️👟');
    }
}