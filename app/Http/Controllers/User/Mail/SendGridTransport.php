<?php

namespace App\Http\Controllers\User\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

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
        // ✅ FIX: Lấy email object đúng cách
        $email = $message->getOriginalMessage();
        
        if (!$email instanceof Email) {
            Log::error('SendGrid: Message is not an Email instance');
            throw new \Exception('Invalid email message');
        }

        // ✅ Build payload an toàn
        $payload = $this->buildPayload($email);
        
        // ✅ Gửi request với error handling
        $this->sendToSendGrid($payload);
    }

    /**
     * ✅ Build SendGrid payload từ Symfony Email
     */
    private function buildPayload(Email $email): array
    {
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

        // ✅ QUAN TRỌNG: text/plain TRƯỚC, text/html SAU
        // Xử lý text body
        $textBody = $email->getTextBody();
        if ($textBody) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => $textBody,
            ];
        }

        // Xử lý HTML body
        $htmlBody = $email->getHtmlBody();
        if ($htmlBody) {
            $payload['content'][] = [
                'type' => 'text/html',
                'value' => $htmlBody,
            ];
        }

        // ✅ Fallback nếu không có content
        if (empty($payload['content'])) {
            $payload['content'][] = [
                'type' => 'text/plain',
                'value' => 'Email verification from SOLID TECH',
            ];
        }

        // Debug log
        Log::info('SendGrid Payload Built', [
            'to' => $payload['personalizations'][0]['to'],
            'subject' => $payload['personalizations'][0]['subject'],
            'content_types' => array_column($payload['content'], 'type'),
            'has_text' => !empty($textBody),
            'has_html' => !empty($htmlBody),
        ]);

        return $payload;
    }

    /**
     * ✅ Format multiple addresses
     */
    private function formatAddresses(array $addresses): array
    {
        return array_map(function($address) {
            return $this->formatAddress($address);
        }, $addresses);
    }

    /**
     * ✅ Format single address
     */
    private function formatAddress(Address $address): array
    {
        return [
            'email' => $address->getAddress(),
            'name' => $address->getName() ?: null,
        ];
    }

    /**
     * ✅ Gửi request đến SendGrid với retry
     */
    private function sendToSendGrid(array $payload): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->retry(3, 1000) // Retry 3 lần, mỗi lần cách 1s
            ->post('https://api.sendgrid.com/v3/mail/send', $payload);

            if (!$response->successful()) {
                $error = $response->json();
                
                Log::error('SendGrid API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                    'payload_to' => $payload['personalizations'][0]['to'] ?? null,
                ]);
                
                throw new \Exception(
                    'SendGrid Error: ' . ($error['errors'][0]['message'] ?? $response->body())
                );
            }

            Log::info('SendGrid: Email sent successfully', [
                'to' => $payload['personalizations'][0]['to'],
                'message_id' => $response->header('X-Message-Id'),
            ]);
            
        } catch (\Exception $e) {
            Log::error('SendGrid Request Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}