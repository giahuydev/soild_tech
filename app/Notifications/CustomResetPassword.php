<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPasswordNotification
{
    public function toMail($notifiable)
    {
        // Tạo URL reset password đầy đủ
        $url = url(route('password.reset', [
            'token' => $this->token,
        ], false)) . '?email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('🔐 Yêu cầu đặt lại mật khẩu - SOLID TECH')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
            ->action('Đặt lại mật khẩu ngay', $url)
            ->line('Link này sẽ hết hạn sau **60 phút**.')
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.')
            ->line('---')
            ->line('📞 Hotline hỗ trợ: **1900.633.349**')
            ->salutation('Trân trọng,')
            ->salutation('Đội ngũ SOLID TECH');
    }
}