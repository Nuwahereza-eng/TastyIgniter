<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    protected string $baseUrl;
    protected string $secretKey;
    protected string $publicKey;
    protected string $encryptionKey;
    protected string $marzBaseUrl;
    protected string $marzSecretKey;
    protected string $marzPublicKey;
    protected string $marzApiKey;
    
    public function __construct()
    {
        $this->baseUrl = config('services.flutterwave.base_url', 'https://api.flutterwave.com/v3');
        $this->secretKey = config('services.flutterwave.secret_key', env('FLUTTERWAVE_SECRET_KEY', ''));
        $this->publicKey = config('services.flutterwave.public_key', env('FLUTTERWAVE_PUBLIC_KEY', ''));
        $this->encryptionKey = config('services.flutterwave.encryption_key', env('FLUTTERWAVE_ENCRYPTION_KEY', ''));
        // Marz Wallet config (MarzPay API v1)
        $this->marzBaseUrl = rtrim(config('services.marz.base_url', env('MARZ_BASE_URL', 'https://wallet.wearemarz.com/api/v1')), '/');
        $this->marzSecretKey = config('services.marz.secret_key', env('MARZ_SECRET_KEY', ''));
        $this->marzPublicKey = config('services.marz.public_key', env('MARZ_PUBLIC_KEY', ''));
        $this->marzApiKey = config('services.marz.api_key', env('MARZ_API_KEY', ''));
    }
    
    /**
     * Get the public key for frontend use
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
    
    /**
     * Initialize a payment
     */
    public function initializePayment(array $data): array
    {
        $txRef = $this->generateTransactionReference();
        
        $payload = [
            'tx_ref' => $txRef,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'UGX',
            'redirect_url' => $data['redirect_url'] ?? route('payment.callback'),
            'payment_options' => $data['payment_options'] ?? 'card,mobilemoneyuganda',
            'customer' => [
                'email' => $data['email'],
                'phone_number' => $data['phone'] ?? null,
                'name' => $data['name'] ?? 'Customer',
            ],
            'customizations' => [
                'title' => $data['title'] ?? 'UgaEats Payment',
                'description' => $data['description'] ?? 'Payment for food order',
                'logo' => asset('themes/demo/assets/images/logo.png'),
            ],
            'meta' => $data['meta'] ?? [],
        ];
        
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payments", $payload);
            
            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'tx_ref' => $txRef,
                    'link' => $response->json('data.link'),
                    'data' => $response->json('data'),
                ];
            }
            
            Log::error('Flutterwave payment initialization failed', [
                'response' => $response->json(),
                'payload' => $payload,
            ]);
            
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave payment error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Payment service temporarily unavailable',
            ];
        }
    }
    
    /**
     * Charge Mobile Money directly (Uganda)
     */
    public function chargeMobileMoney(array $data): array
    {
        $txRef = $this->generateTransactionReference();
        
        $payload = [
            'tx_ref' => $txRef,
            'amount' => $data['amount'],
            'currency' => 'UGX',
            'email' => $data['email'],
            'phone_number' => $data['phone'],
            'network' => strtoupper($data['network']), // MTN or AIRTEL
            'meta' => $data['meta'] ?? [],
        ];
        
        Log::info('Flutterwave Mobile Money Request', [
            'url' => "{$this->baseUrl}/charges?type=mobile_money_uganda",
            'payload' => $payload,
        ]);
        
        try {
            $response = Http::withToken($this->secretKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/charges?type=mobile_money_uganda", $payload);
            
            Log::info('Flutterwave Mobile Money Response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                return [
                    'success' => $responseData['status'] === 'success',
                    'tx_ref' => $txRef,
                    'flw_ref' => $responseData['data']['flw_ref'] ?? null,
                    'status' => $responseData['data']['status'] ?? 'pending',
                    'message' => $responseData['message'] ?? 'Payment initiated',
                    'data' => $responseData['data'] ?? [],
                ];
            }
            
            Log::error('Flutterwave Mobile Money Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Mobile money charge failed',
            ];
        } catch (\Exception $e) {
            Log::error('Mobile money charge error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment service temporarily unavailable',
            ];
        }
    }
    
    /**
     * Verify a transaction
     */
    public function verifyTransaction(string $transactionId): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");
            
            if ($response->successful()) {
                $data = $response->json('data');
                
                return [
                    'success' => $data['status'] === 'successful',
                    'status' => $data['status'],
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'tx_ref' => $data['tx_ref'],
                    'flw_ref' => $data['flw_ref'],
                    'customer' => $data['customer'] ?? [],
                    'data' => $data,
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Transaction verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Transaction verification error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Verification service temporarily unavailable',
            ];
        }
    }
    
    /**
     * Verify transaction by reference
     */
    public function verifyByReference(string $txRef): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/verify_by_reference", [
                    'tx_ref' => $txRef,
                ]);
            
            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json('data');
                
                return [
                    'success' => $data['status'] === 'successful',
                    'status' => $data['status'],
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'tx_ref' => $data['tx_ref'],
                    'data' => $data,
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Transaction not found',
            ];
        } catch (\Exception $e) {
            Log::error('Reference verification error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Verification failed',
            ];
        }
    }
    
    /**
     * Generate unique transaction reference
     */
    protected function generateTransactionReference(): string
    {
        return 'UGA-' . date('Ymd') . '-' . strtoupper(Str::random(8));
    }
    
    /**
     * Get supported payment methods for Uganda
     */
    public function getSupportedMethods(): array
    {
        return [
            'mobile_money' => [
                'mtn' => [
                    'name' => 'MTN Mobile Money',
                    'code' => 'MTN',
                    'icon' => 'mtn',
                    'prefix' => '+256',
                ],
                'airtel' => [
                    'name' => 'Airtel Money',
                    'code' => 'AIRTEL',
                    'icon' => 'airtel',
                    'prefix' => '+256',
                ],
            ],
            'card' => [
                'visa' => ['name' => 'Visa', 'icon' => 'visa'],
                'mastercard' => ['name' => 'Mastercard', 'icon' => 'mastercard'],
            ],
        ];
    }
    
    /**
     * Create a payment plan for subscriptions
     */
    public function createPaymentPlan(array $data): array
    {
        $payload = [
            'name' => $data['name'],
            'amount' => $data['amount'],
            'interval' => $data['interval'] ?? 'weekly', // daily, weekly, monthly, yearly
            'currency' => 'UGX',
        ];
        
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payment-plans", $payload);
            
            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'plan_id' => $response->json('data.id'),
                    'data' => $response->json('data'),
                ];
            }
            
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to create payment plan',
            ];
        } catch (\Exception $e) {
            Log::error('Payment plan creation error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable',
            ];
        }
    }

    /**
     * Build the Basic auth credentials string for MarzPay.
     *
     * MarzPay uses HTTP Basic auth: base64(api_key:api_secret).
     * If MARZ_PUBLIC_KEY already contains a pre-encoded credential, use it as-is.
     */
    protected function marzAuthCredentials(): string
    {
        // If a pre-encoded credential is provided as the "public key" use it directly.
        if (!empty($this->marzPublicKey) && !str_contains($this->marzPublicKey, ':') && strlen($this->marzPublicKey) > 30) {
            return $this->marzPublicKey;
        }

        $apiKey = $this->marzApiKey ?: $this->marzPublicKey;
        if (empty($apiKey) || empty($this->marzSecretKey)) {
            return '';
        }

        return base64_encode($apiKey . ':' . $this->marzSecretKey);
    }

    /**
     * Initialize a Marz Wallet (MarzPay) collection.
     *
     * Supports method=card (returns a redirect_url) and method=mobile_money (USSD push).
     */
    public function initializeMarzPayment(array $data): array
    {
        $credentials = $this->marzAuthCredentials();

        if (empty($credentials)) {
            // Fallback to simulation when no Marz credentials configured.
            return $this->simulatePayment($data);
        }

        // MarzPay requires a UUID reference; generate one per request.
        $reference = (string) Str::uuid();

        $method = $data['method'] ?? null;
        if (!$method) {
            $method = !empty($data['phone']) ? 'mobile_money' : 'card';
        }

        $payload = [
            'amount' => (int) round((float) $data['amount']),
            'country' => $data['country'] ?? 'UG',
            'reference' => $reference,
            'method' => $method,
            'description' => $data['description'] ?? ($data['title'] ?? 'Payment'),
            'callback_url' => $data['redirect_url'] ?? route('payment.callback'),
        ];

        if ($method === 'mobile_money' && !empty($data['phone'])) {
            $payload['phone_number'] = $this->normalizeUgandaPhone($data['phone']);
        }

        try {
            $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $credentials,
                    'Accept' => 'application/json',
                ])
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post("{$this->marzBaseUrl}/collect-money", $payload);

            $body = $response->json() ?? [];
            Log::info('MarzPay collect-money response', [
                'status' => $response->status(),
                'body' => $body,
                'payload' => $payload,
            ]);

            if ($response->successful() && ($body['status'] ?? null) === 'success') {
                $dataResp = $body['data'] ?? [];
                $tx = $dataResp['transaction'] ?? [];

                return [
                    'success' => true,
                    'tx_ref' => $reference,
                    'transaction_uuid' => $tx['uuid'] ?? null,
                    'status' => $tx['status'] ?? 'pending',
                    'link' => $dataResp['redirect_url'] ?? null,
                    'data' => $dataResp,
                ];
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Marz initialization failed',
                'errors' => $body['errors'] ?? null,
                'data' => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Marz payment initialization error', ['error' => $e->getMessage(), 'payload' => $payload]);
            return [
                'success' => false,
                'message' => 'Payment service temporarily unavailable',
            ];
        }
    }

    /**
     * Verify a MarzPay collection by reference (UUID) or transaction uuid.
     */
    public function verifyMarzTransaction(string $reference): array
    {
        $credentials = $this->marzAuthCredentials();
        if (empty($credentials)) {
            return ['success' => false, 'message' => 'Marz not configured'];
        }

        try {
            $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $credentials,
                    'Accept' => 'application/json',
                ])
                ->acceptJson()
                ->timeout(20)
                ->get("{$this->marzBaseUrl}/collect-money/{$reference}");

            $body = $response->json() ?? [];

            if ($response->successful() && ($body['status'] ?? null) === 'success') {
                $dataResp = $body['data'] ?? [];
                $tx = $dataResp['transaction'] ?? [];
                $collection = $dataResp['collection'] ?? [];
                $status = strtolower($tx['status'] ?? '');
                $isSuccess = in_array($status, ['successful', 'completed', 'success'], true);

                return [
                    'success' => $isSuccess,
                    'status' => $status,
                    'amount' => $collection['amount']['raw'] ?? null,
                    'tx_ref' => $tx['reference'] ?? $reference,
                    'data' => $dataResp,
                ];
            }

            return ['success' => false, 'message' => $body['message'] ?? 'Verification failed', 'data' => $body];
        } catch (\Exception $e) {
            Log::error('Marz verification error', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Verification service temporarily unavailable'];
        }
    }

    /**
     * Send a Marz Wallet payout (disbursement) to a mobile-money number.
     * Used by the Tasty Wallet withdrawal flow. When Marz credentials are
     * missing the call short-circuits with success=false so the caller can
     * fall back to a "pending admin approval" queue.
     */
    public function sendMarzPayout(array $data): array
    {
        $credentials = $this->marzAuthCredentials();
        if (empty($credentials)) {
            return [
                'success' => false,
                'configured' => false,
                'message' => 'Marz payout not configured',
            ];
        }

        $reference = $data['reference'] ?? (string) Str::uuid();
        $payload = [
            'amount' => (int) round((float) $data['amount']),
            'country' => $data['country'] ?? 'UG',
            'reference' => $reference,
            'method' => 'mobile_money',
            'description' => $data['description'] ?? 'UgaEats wallet withdrawal',
            'phone_number' => $this->normalizeUgandaPhone($data['phone']),
        ];

        try {
            $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $credentials,
                    'Accept' => 'application/json',
                ])
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post("{$this->marzBaseUrl}/send-money", $payload);

            $body = $response->json() ?? [];
            Log::info('MarzPay send-money response', [
                'status' => $response->status(),
                'body' => $body,
                'payload' => array_merge($payload, ['phone_number' => '***']),
            ]);

            if ($response->successful() && ($body['status'] ?? null) === 'success') {
                $dataResp = $body['data'] ?? [];
                $tx = $dataResp['transaction'] ?? [];
                $status = strtolower($tx['status'] ?? 'pending');

                return [
                    'success' => true,
                    'configured' => true,
                    'tx_ref' => $reference,
                    'transaction_uuid' => $tx['uuid'] ?? null,
                    'status' => $status,
                    'completed' => in_array($status, ['successful', 'completed', 'success'], true),
                    'data' => $dataResp,
                ];
            }

            return [
                'success' => false,
                'configured' => true,
                'message' => $body['message'] ?? 'Payout initialization failed',
                'errors' => $body['errors'] ?? null,
                'data' => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Marz payout error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'configured' => true,
                'message' => 'Payout service temporarily unavailable',
            ];
        }
    }

    /**
     * Returns true when MarzPay collection/payout API credentials are configured.
     */
    public function hasMarzCredentials(): bool
    {
        return $this->marzAuthCredentials() !== '';
    }

    /**
     * Normalize a Ugandan phone number to +256xxxxxxxxx format expected by MarzPay.
     */
    protected function normalizeUgandaPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '256')) {
            return '+' . $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '+256' . substr($digits, 1);
        }
        if (strlen($digits) === 9) {
            return '+256' . $digits;
        }
        return '+' . $digits;
    }
    
    /**
     * Subscribe customer to a payment plan
     */
    public function subscribeToPaymentPlan(array $data): array
    {
        $txRef = $this->generateTransactionReference();
        
        $payload = [
            'tx_ref' => $txRef,
            'amount' => $data['amount'],
            'currency' => 'UGX',
            'redirect_url' => $data['redirect_url'] ?? route('payment.callback'),
            'payment_plan' => $data['plan_id'],
            'customer' => [
                'email' => $data['email'],
                'phone_number' => $data['phone'] ?? null,
                'name' => $data['name'] ?? 'Customer',
            ],
            'customizations' => [
                'title' => 'UgaEats Subscription',
                'description' => $data['description'] ?? 'Meal Plan Subscription',
                'logo' => asset('themes/demo/assets/images/logo.png'),
            ],
        ];
        
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payments", $payload);
            
            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'tx_ref' => $txRef,
                    'link' => $response->json('data.link'),
                    'data' => $response->json('data'),
                ];
            }
            
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Subscription initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Subscription error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable',
            ];
        }
    }
    
    /**
     * Check if we're in test/demo mode
     * Returns true if no valid Flutterwave keys are configured or using test keys
     * When using TEST keys, we still want to simulate since real API calls need user interaction
     */
    public function isTestMode(): bool
    {
        // Check if secret key is empty
        if (empty($this->secretKey)) {
            return true;
        }
        
        // If using TEST keys, use demo mode to allow immediate completion
        // Real Flutterwave test payments require SMS OTP which doesn't work in automation
        if (str_contains($this->secretKey, '_TEST') || str_contains($this->secretKey, '-TEST')) {
            return true;
        }
        
        // Valid Flutterwave keys start with FLWSECK_ or FLWSECK-
        $isValidFlutterwaveKey = str_starts_with($this->secretKey, 'FLWSECK_') || 
                                  str_starts_with($this->secretKey, 'FLWSECK-');
        
        // If not a valid Flutterwave key format, use demo mode
        return !$isValidFlutterwaveKey;
    }
    
    /**
     * Simulate a payment for demo/test purposes
     */
    public function simulatePayment(array $data): array
    {
        $txRef = $this->generateTransactionReference();
        
        // Simulate processing delay
        usleep(500000); // 0.5 seconds
        
        return [
            'success' => true,
            'tx_ref' => $txRef,
            'status' => 'successful',
            'message' => 'Payment simulated successfully (Demo Mode)',
            'data' => [
                'amount' => $data['amount'],
                'currency' => 'UGX',
                'payment_method' => $data['payment_method'] ?? 'mobile_money',
                'created_at' => now()->toISOString(),
            ],
        ];
    }
}
