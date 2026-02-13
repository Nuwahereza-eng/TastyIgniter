<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    /**
     * Get payment configuration for frontend
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'public_key' => $this->paymentService->getPublicKey(),
            'is_test_mode' => $this->paymentService->isTestMode(),
            'supported_methods' => $this->paymentService->getSupportedMethods(),
            'currency' => 'UGX',
        ]);
    }
    
    /**
     * Initialize a payment
     */
    public function initialize(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'name' => 'nullable|string',
            'type' => 'required|in:order,subscription',
            'reference_id' => 'nullable|integer',
            'plan_id' => 'nullable|numeric', // For subscription payments
            'payment_method' => 'required|in:mtn,airtel,card',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $data = $validator->validated();
        $customerId = $this->getCustomerId();
        
        Log::info('Payment initialization', [
            'customer_id' => $customerId,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'method' => $data['payment_method'],
        ]);
        
        // If test mode or no API keys, use simulation
        if ($this->paymentService->isTestMode()) {
            try {
                $result = $this->paymentService->simulatePayment($data);
                
                // Record the payment
                $payment = Payment::create([
                    'customer_id' => $customerId,
                    'tx_ref' => $result['tx_ref'],
                    'amount' => $data['amount'],
                    'currency' => 'UGX',
                    'payment_method' => $data['payment_method'],
                    'payment_type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'status' => 'successful',
                    'provider' => 'demo',
                    'metadata' => [
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? null,
                        'name' => $data['name'] ?? null,
                        'plan_id' => $request->input('plan_id'),
                        'simulated' => true,
                    ],
                ]);
                
                Log::info('Payment recorded', ['payment_id' => $payment->id, 'tx_ref' => $result['tx_ref']]);
                
                // For subscriptions, create/activate subscription after successful payment
                if ($data['type'] === 'subscription' && $customerId) {
                    $this->createSubscriptionFromPayment($payment, $request);
                }
                
                return response()->json([
                    'success' => true,
                    'tx_ref' => $result['tx_ref'],
                    'status' => 'successful',
                    'message' => 'Payment processed successfully (Demo Mode)',
                    'payment_id' => $payment->id,
                    'redirect_url' => null,
                ]);
            } catch (\Exception $e) {
                Log::error('Payment processing error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Payment processing error: ' . $e->getMessage(),
                ], 500);
            }
        }
        
        // Real payment processing
        if ($data['payment_method'] === 'card') {
            // Card payments use redirect flow
            $result = $this->paymentService->initializePayment([
                'amount' => $data['amount'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'name' => $data['name'] ?? 'Customer',
                'payment_options' => 'card',
                'redirect_url' => route('payment.callback'),
                'title' => $data['type'] === 'subscription' ? 'UgaEats Subscription' : 'UgaEats Order',
                'description' => $data['type'] === 'subscription' 
                    ? 'Meal Plan Subscription Payment' 
                    : 'Food Order Payment',
                'meta' => [
                    'type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'customer_id' => $customerId,
                ],
            ]);
            
            if ($result['success']) {
                // Record pending payment
                Payment::create([
                    'customer_id' => $customerId,
                    'tx_ref' => $result['tx_ref'],
                    'amount' => $data['amount'],
                    'currency' => 'UGX',
                    'payment_method' => 'card',
                    'payment_type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'status' => 'pending',
                    'provider' => 'flutterwave',
                    'metadata' => [
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? null,
                    ],
                ]);
                
                return response()->json([
                    'success' => true,
                    'tx_ref' => $result['tx_ref'],
                    'redirect_url' => $result['link'],
                    'message' => 'Redirecting to payment page...',
                ]);
            }
        } else {
            // Mobile money - direct charge
            $result = $this->paymentService->chargeMobileMoney([
                'amount' => $data['amount'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'network' => $data['payment_method'] === 'mtn' ? 'MTN' : 'AIRTEL',
                'meta' => [
                    'type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'customer_id' => $customerId,
                ],
            ]);
            
            if ($result['success']) {
                // Record pending payment
                Payment::create([
                    'customer_id' => $customerId,
                    'tx_ref' => $result['tx_ref'],
                    'flw_ref' => $result['flw_ref'] ?? null,
                    'amount' => $data['amount'],
                    'currency' => 'UGX',
                    'payment_method' => $data['payment_method'],
                    'payment_type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'status' => 'pending',
                    'provider' => 'flutterwave',
                    'metadata' => [
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                    ],
                ]);
                
                return response()->json([
                    'success' => true,
                    'tx_ref' => $result['tx_ref'],
                    'status' => $result['status'],
                    'message' => 'A payment prompt has been sent to your phone. Please approve to complete payment.',
                ]);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed',
        ], 400);
    }
    
    /**
     * Handle payment callback (for card payments)
     */
    public function callback(Request $request)
    {
        $status = $request->get('status');
        $txRef = $request->get('tx_ref');
        $transactionId = $request->get('transaction_id');
        
        if ($status === 'successful' && $transactionId) {
            // Verify the transaction
            $verification = $this->paymentService->verifyTransaction($transactionId);
            
            if ($verification['success']) {
                // Update payment record
                $payment = Payment::where('tx_ref', $txRef)->first();
                
                if ($payment) {
                    $payment->update([
                        'status' => 'successful',
                        'flw_ref' => $verification['flw_ref'] ?? null,
                        'verified_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'verification' => $verification['data'],
                        ]),
                    ]);
                    
                    // Handle subscription activation
                    if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                        $this->activateSubscription($payment);
                    }
                }
                
                return redirect('/account/features#subscriptions')
                    ->with('success', 'Payment successful! Your subscription has been activated.');
            }
        }
        
        // Payment failed or was cancelled
        if ($txRef) {
            Payment::where('tx_ref', $txRef)->update(['status' => 'failed']);
        }
        
        return redirect('/account/features#subscriptions')
            ->with('error', 'Payment was not completed. Please try again.');
    }
    
    /**
     * Verify a payment
     */
    public function verify(Request $request): JsonResponse
    {
        $txRef = $request->get('tx_ref');
        
        if (!$txRef) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction reference required',
            ], 400);
        }
        
        $payment = Payment::where('tx_ref', $txRef)->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }
        
        // If already verified
        if ($payment->status === 'successful') {
            return response()->json([
                'success' => true,
                'status' => 'successful',
                'payment' => $payment,
            ]);
        }
        
        // Check with payment provider
        if ($payment->provider === 'flutterwave') {
            $verification = $this->paymentService->verifyByReference($txRef);
            
            if ($verification['success']) {
                $payment->update([
                    'status' => 'successful',
                    'verified_at' => now(),
                ]);
                
                // Handle subscription activation
                if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                    $this->activateSubscription($payment);
                }
                
                return response()->json([
                    'success' => true,
                    'status' => 'successful',
                    'message' => 'Payment verified successfully',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'status' => $verification['status'] ?? 'pending',
                'message' => 'Payment not yet completed',
            ]);
        }
        
        // Demo payment - already successful
        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'payment' => $payment,
        ]);
    }
    
    /**
     * Get payment history for current customer
     */
    public function history(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $payments = Payment::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'tx_ref' => $payment->tx_ref,
                    'amount' => $payment->amount,
                    'formatted_amount' => 'UGX ' . number_format($payment->amount),
                    'type' => $payment->payment_type,
                    'method' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->format('M d, Y H:i'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'payments' => $payments,
        ]);
    }
    
    /**
     * Webhook handler for Flutterwave
     */
    public function webhook(Request $request): JsonResponse
    {
        // Verify webhook signature
        $signature = $request->header('verif-hash');
        $secretHash = env('FLUTTERWAVE_SECRET_HASH');
        
        if ($secretHash && $signature !== $secretHash) {
            Log::warning('Invalid webhook signature', ['signature' => $signature]);
            return response()->json(['status' => 'error'], 401);
        }
        
        $payload = $request->all();
        
        Log::info('Flutterwave webhook received', $payload);
        
        if (isset($payload['event']) && $payload['event'] === 'charge.completed') {
            $data = $payload['data'];
            $txRef = $data['tx_ref'] ?? null;
            
            if ($txRef) {
                $payment = Payment::where('tx_ref', $txRef)->first();
                
                if ($payment && $data['status'] === 'successful') {
                    $payment->update([
                        'status' => 'successful',
                        'flw_ref' => $data['flw_ref'] ?? null,
                        'verified_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'webhook_data' => $data,
                        ]),
                    ]);
                    
                    // Handle subscription activation
                    if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                        $this->activateSubscription($payment);
                    }
                }
            }
        }
        
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Activate subscription after successful payment
     */
    protected function activateSubscription(Payment $payment): void
    {
        $subscription = Subscription::find($payment->reference_id);
        
        if ($subscription && $subscription->status !== 'active') {
            $subscription->update([
                'status' => 'active',
                'started_at' => now(),
                'amount_paid' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_reference' => $payment->tx_ref,
            ]);
            
            Log::info('Subscription activated', [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
            ]);
        }
    }
    
    /**
     * Create subscription from successful payment
     */
    protected function createSubscriptionFromPayment(Payment $payment, Request $request): void
    {
        $planId = $request->input('plan_id') ?? ($payment->metadata['plan_id'] ?? null);
        $customerId = $payment->customer_id;
        
        if (!$planId || !$customerId) {
            Log::warning('Cannot create subscription: missing plan_id or customer_id', [
                'plan_id' => $planId,
                'customer_id' => $customerId,
            ]);
            return;
        }
        
        // Check if customer already has active subscription
        $existingSubscription = Subscription::where('customer_id', $customerId)
            ->where('status', 'active')
            ->first();
            
        if ($existingSubscription) {
            Log::info('Customer already has active subscription', [
                'customer_id' => $customerId,
                'existing_subscription_id' => $existingSubscription->id,
            ]);
            return;
        }
        
        // Get the plan
        $plan = \App\Models\SubscriptionPlan::find($planId);
        
        if (!$plan) {
            Log::warning('Plan not found for subscription creation', ['plan_id' => $planId]);
            return;
        }
        
        // Calculate expiration based on billing period
        $expiresAt = match($plan->billing_period) {
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };
        
        // Create the subscription with correct table columns
        $subscription = Subscription::create([
            'customer_id' => $customerId,
            'plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => $expiresAt,
            'meals_remaining' => $plan->meals_per_period ?? 0,
            'meals_used' => 0,
            'amount_paid' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_reference' => $payment->tx_ref,
        ]);
        
        // Update payment with subscription reference
        $payment->update(['reference_id' => $subscription->id]);
        
        Log::info('Subscription created from payment', [
            'subscription_id' => $subscription->id,
            'plan_id' => $plan->id,
            'customer_id' => $customerId,
            'payment_id' => $payment->id,
        ]);
    }
    
    /**
     * Get current customer ID
     */
    protected function getCustomerId(): ?int
    {
        // Check for TastyIgniter customer session first (most common case)
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }
        
        // Fallback to Laravel Auth
        if (Auth::check()) {
            return Auth::id();
        }
        
        return null;
    }
}
