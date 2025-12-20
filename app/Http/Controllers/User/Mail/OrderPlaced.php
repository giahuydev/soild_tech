<?php

namespace App\Http\Controllers\User\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Đặt hàng thành công #' . $this->order->id . ' - SOLID TECH',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'user.emails.order-placed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}