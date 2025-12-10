<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoMoService
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;
    private $redirectUrl;
    private $ipnUrl;

    public function __construct()
    {
        $this->partnerCode = config('services.momo.partner_code');
        $this->accessKey = config('services.momo.access_key');
        $this->secretKey = config('services.momo.secret_key');
        $this->endpoint = config('services.momo.endpoint');
        $this->redirectUrl = config('services.momo.redirect_url');
        $this->ipnUrl = config('services.momo.ipn_url');
    }

    /**
     * Tạo thanh toán MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo, $extraData = '')
    {
        $requestId = $orderId . time();
        $orderIdUnique = $orderId . time();
        $requestType = 'captureWallet';

        // Tạo chữ ký (signature)
        $rawSignature = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$this->ipnUrl}&orderId={$orderIdUnique}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$this->redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        
        $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);

        // Request body gửi đến MoMo
        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => (string)$amount,
            'orderId' => $orderIdUnique,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl' => $this->ipnUrl,
            'requestType' => $requestType,
            'extraData' => $extraData,
            'lang' => 'vi',
            'signature' => $signature
        ];

        try {
            // Gửi request đến MoMo
            $response = Http::timeout(30)
                          ->withHeaders(['Content-Type' => 'application/json'])
                          ->post($this->endpoint, $data);

            $result = $response->json();
            
            Log::info('MoMo Payment Created', [
                'orderId' => $orderIdUnique,
                'amount' => $amount,
                'result' => $result
            ]);

            if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                return [
                    'success' => true,
                    'data' => $result,
                    'orderId' => $orderIdUnique,
                    'requestId' => $requestId,
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Không thể tạo thanh toán MoMo'
            ];

        } catch (\Exception $e) {
            Log::error('MoMo Payment Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Lỗi kết nối đến MoMo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify signature từ MoMo callback/IPN
     */
    public function verifySignature($data)
    {
        $rawSignature = "accessKey={$this->accessKey}&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$this->partnerCode}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}&transId={$data['transId']}";
        
        $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);

        return $signature === $data['signature'];
    }

    /**
     * Kiểm tra trạng thái giao dịch
     */
    public function checkTransactionStatus($orderId, $requestId)
    {
        $rawSignature = "accessKey={$this->accessKey}&orderId={$orderId}&partnerCode={$this->partnerCode}&requestId={$requestId}";
        $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'orderId' => $orderId,
            'lang' => 'vi',
            'signature' => $signature
        ];

        try {
            $response = Http::timeout(30)
                          ->withHeaders(['Content-Type' => 'application/json'])
                          ->post('https://test-payment.momo.vn/v2/gateway/api/query', $data);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('MoMo Check Transaction Error: ' . $e->getMessage());
            return null;
        }
    }
}
