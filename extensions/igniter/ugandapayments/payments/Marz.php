<?php

namespace Igniter\UgandaPayments\Payments;

use Igniter\Flame\Exception\ApplicationException;
use Igniter\PayRegister\Classes\BasePaymentGateway;
use Illuminate\Support\Facades\Redirect;
use Override;

class Marz extends BasePaymentGateway
{
    public static ?string $paymentFormView = 'igniter.ugandapayments::_partials.marz.payment_form';

    #[Override]
    public function defineFieldsConfig(): string
    {
        return 'igniter.ugandapayments::/models/marz';
    }

    public function gatewayDetails(): array
    {
        return [
            'name' => 'Marz Wallet',
            'description' => 'Accept payments via Marz Wallet',
        ];
    }

    /**
     * Process the payment.
     *
     * If Marz keys are configured we initialize a Marz checkout and redirect.
     * Otherwise we simulate (demo mode) so testing still works.
     */
    public function processPaymentForm($data, $host, $order)
    {
        $method = strtolower((string) ($data['marz_method'] ?? request()->input('marz_method', 'card')));
        $phone = trim((string) ($data['marz_phone'] ?? request()->input('marz_phone', $order->telephone ?? '')));

        // Map UI choice -> MarzPay API method
        $apiMethod = in_array($method, ['mtn', 'airtel', 'mobile_money'], true) ? 'mobile_money' : 'card';

        if ($apiMethod === 'mobile_money' && empty($phone)) {
            throw new ApplicationException('Please enter the Mobile Money phone number to receive the payment prompt.');
        }

        \Log::info('Marz processPaymentForm called', [
            'host_code' => $host->code ?? 'unknown',
            'order_id' => $order->order_id ?? 'unknown',
            'method' => $apiMethod,
        ]);

        $paymentService = new \App\Services\PaymentService();

        $init = $paymentService->initializeMarzPayment([
            'amount' => $order->order_total,
            'currency' => $this->model->currency ?? 'UGX',
            'country' => 'UG',
            'method' => $apiMethod,
            'email' => $order->email,
            'phone' => $apiMethod === 'mobile_money' ? $phone : null,
            'name' => trim($order->first_name . ' ' . $order->last_name),
            'description' => 'Order #' . $order->order_id . ' at ' . config('app.name'),
            'redirect_url' => route('payment.callback'),
            'meta' => [
                'type' => 'order',
                'order_id' => $order->order_id,
                'order_hash' => $order->hash,
            ],
        ]);

        // Record pending payment row (used by webhook to mark order paid)
        $recordPending = function () use ($order, $init, $apiMethod, $method, $phone) {
            try {
                \App\Models\Payment::create([
                    'customer_id' => $order->customer_id,
                    'tx_ref' => $init['tx_ref'] ?? null,
                    'amount' => $order->order_total,
                    'currency' => $this->model->currency ?? 'UGX',
                    'payment_method' => 'marz',
                    'payment_type' => \App\Models\Payment::TYPE_ORDER,
                    'reference_id' => $order->order_id,
                    'status' => \App\Models\Payment::STATUS_PENDING,
                    'provider' => 'marz',
                    'metadata' => [
                        'order_id' => $order->order_id,
                        'order_hash' => $order->hash,
                        'method' => $method,
                        'api_method' => $apiMethod,
                        'phone' => $phone,
                        'transaction_uuid' => $init['transaction_uuid'] ?? null,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to record pending Marz payment', ['error' => $e->getMessage()]);
            }
        };

        // Card flow: Marz returned a checkout URL -> redirect customer.
        if (!empty($init['success']) && !empty($init['link'])) {
            $recordPending();
            $order->logPaymentAttempt('Marz card checkout initiated', 1, [], $init);
            return Redirect::to($init['link']);
        }

        // Mobile money flow: USSD push sent. The order stays unpaid until the
        // customer approves, so send them to the waiting page (the success page
        // only shows paid orders and would bounce them back to checkout).
        if (!empty($init['success']) && $apiMethod === 'mobile_money') {
            $recordPending();
            $order->logPaymentAttempt('Marz mobile money prompt sent to ' . $phone, 1, [], $init);
            return Redirect::to(url('/checkout/pay/' . $order->hash));
        }

        // Demo / simulate fallback (no Marz key configured)
        if (!empty($init['success'])) {
            $orderStatus = $host->order_status ?? 1;
            $order->updateOrderStatus($orderStatus, ['notify' => false]);
            $order->markAsPaymentProcessed();
            $order->logPaymentAttempt('Marz payment simulated (demo mode)', 1, [], $init);
            return;
        }

        $order->logPaymentAttempt('Marz payment error -> ' . ($init['message'] ?? 'unknown'), 0, [], $init);
        throw new ApplicationException($init['message'] ?? 'Marz payment failed. Please try again.');
    }

    protected function isTestMode(): bool
    {
        return (bool) ($this->model->test_mode ?? true);
    }
}
