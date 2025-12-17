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
        // ✅ FIX: Dùng MessageConverter để convert sang Email object
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        // Build payload
        $payload = [
            'personalizations' => [
                [
                    'to' => $this->formatAddresses($email->getTo()),
                    'subject' => $email->getSubject(),
                ]
            ],
            'from' => $this->formatAddress($email->getFrom()[0]),
            'content' => []
        ];

        // Thêm text body
        if ($email->getTextBody()) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => $email->getTextBody(),
            ];
        }

        // Thêm HTML body
        if ($email->getHtmlBody()) {
            $payload['content'][] = [
                'type' => 'text/html',
                'value' => $email->getHtmlBody(),
            ];
        }

        // Fallback content nếu không có gì
        if (empty($payload['content'])) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => 'Email from SOLID TECH',
            ];
        }

        // Log trước khi gửi
        Log::info('SendGrid: Sending email', [
            'to' => $payload['personalizations'][0]['to'],
            'subject' => $payload['personalizations'][0]['subject'],
        ]);

        // Gửi request
        $this->sendToSendGrid($payload);
    }

    private function formatAddresses(array $addresses): array
    {
        return array_map(fn($addr) => [
            'email' => $addr->getAddress(),
            'name' => $addr->getName() ?: null,
        ], $addresses);
    }

    private function formatAddress($address): array
    {
        return [
            'email' => $address->getAddress(),
            'name' => $address->getName() ?: null,
        ];
    }

    private function sendToSendGrid(array $payload): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post('https://api.sendgrid.com/v3/mail/send', $payload);

            if (!$response->successful()) {
                $error = $response->json();
                
                Log::error('SendGrid API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                ]);
                
                throw new \Exception(
                    'SendGrid Error: ' . ($error['errors'][0]['message'] ?? 'Unknown error')
                );
            }

            Log::info('SendGrid: Email sent successfully');
            
        } catch (\Exception $e) {
            Log::error('SendGrid Request Failed', [
                'message' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}