<?php

namespace App\Http\Controllers\User\Mail;

use Illuminate\Support\Facades\Http;
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

        // Add HTML content
        if ($email->getHtmlBody()) {
            $payload['content'][] = [
                'type' => 'text/html',
                'value' => $email->getHtmlBody(),
            ];
        }

        // Add plain text content
        if ($email->getTextBody()) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => $email->getTextBody(),
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.sendgrid.com/v3/mail/send', $payload);

        if (!$response->successful()) {
            throw new \Exception('SendGrid API Error: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}