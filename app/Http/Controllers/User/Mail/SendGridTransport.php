<?php

namespace App\Http\Controllers\User\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class SendGridTransport extends AbstractTransport
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $payload = [
            'personalizations' => [
                [
                    'to' => array_map(fn($addr) => [
                        'email' => $addr->getAddress(),
                        'name' => $addr->getName()
                    ], $email->getTo()),
                    'subject' => $email->getSubject(),
                ]
            ],
            'from' => [
                'email' => $email->getFrom()[0]->getAddress(),
                'name' => $email->getFrom()[0]->getName(),
            ],
            'content' => []
        ];

        // Debug: Kiểm tra xem có content nào
        Log::info('Email Body Check', [
            'has_text' => !empty($email->getTextBody()),
            'has_html' => !empty($email->getHtmlBody()),
            'text_length' => $email->getTextBody() ? strlen($email->getTextBody()) : 0,
            'html_length' => $email->getHtmlBody() ? strlen($email->getHtmlBody()) : 0,
        ]);

        // QUAN TRỌNG: text/plain TRƯỚC, text/html SAU
        if ($email->getTextBody()) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => $email->getTextBody(),
            ];
        }

        if ($email->getHtmlBody()) {
            $payload['content'][] = [
                'type' => 'text/html',
                'value' => $email->getHtmlBody(),
            ];
        }

        // Debug: Log payload content order
        Log::info('SendGrid Payload Content Order', [
            'content_types' => array_map(fn($c) => $c['type'], $payload['content'])
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.sendgrid.com/v3/mail/send', $payload);

        if (!$response->successful()) {
            Log::error('SendGrid Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload
            ]);
            throw new \Exception('SendGrid API Error: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}