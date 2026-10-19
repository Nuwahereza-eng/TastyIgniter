<?php

namespace Igniter\UgandaPayments\Payments;

use Igniter\Cart\Models\Order;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\PayRegister\Classes\BasePaymentGateway;
use Igniter\PayRegister\Models\Payment;
use Override;

class Flutterwave extends BasePaymentGateway
{
    public static ?string $paymentFormView = 'igniter.ugandapayments::_partials.flutterwave.payment_form';

    #[Override]
    public function defineFieldsConfig(): string
    {
        return 'igniter.ugandapayments::/models/flutterwave';
    }

    public function gatewayDetails(): array
    {
        return [
            'name' => 'Flutterwave',
            'description' => 'Accept payments via Flutterwave (Cards, Mobile Money)',
        ];
    }

    /**
     * Process the payment
     */
    public function processPaymentForm($data, $host, $order): void
    {
        \Log::info('Flutterwave processPaymentForm called', [
            'data' => $data,
            'host_code' => $host->code ?? 'unknown',
            'order_id' => $order->order_id ?? 'unknown',
        ]);

        // Get order status with fallback to status 1 (Received)
        $orderStatus = $host->order_status ?? 1;

        // Process payment (test mode - just mark as processed)
        $order->updateOrderStatus($orderStatus, ['notify' => false]);
        $order->markAsPaymentProcessed();
        
        \Log::info('Flutterwave payment processed successfully');
    }

    protected function isTestMode(): bool
    {
        return (bool) ($this->model->test_mode ?? true);
    }
}
