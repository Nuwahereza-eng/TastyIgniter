<?php

namespace Igniter\UgandaPayments\Payments;

use App\Models\TastyWallet;
use Igniter\Cart\Models\Order;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\PayRegister\Classes\BasePaymentGateway;
use Igniter\PayRegister\Models\Payment;
use Igniter\User\Facades\Auth;
use Override;

class TastyWalletGateway extends BasePaymentGateway
{
    public static ?string $paymentFormView = 'igniter.ugandapayments::_partials.tastywallet.payment_form';

    #[Override]
    public function defineFieldsConfig(): string
    {
        return 'igniter.ugandapayments::/models/tastywallet';
    }

    public function gatewayDetails(): array
    {
        return [
            'name' => 'Tasty Wallet',
            'description' => 'Pay using your Tasty Wallet balance and earn 5% cashback!',
        ];
    }

    /**
     * Check if payment method is applicable
     */
    public function isApplicable(float $total, ?Payment $host = null): bool
    {
        // Always show Tasty Wallet as an option
        return true;
    }

    /**
     * Get the customer's wallet balance
     */
    public function getWalletBalance(): float
    {
        $customer = Auth::customer();
        if (!$customer) {
            return 0;
        }
        
        $wallet = TastyWallet::where('customer_id', $customer->customer_id)->first();
        return $wallet ? (float)$wallet->balance : 0;
    }

    /**
     * Process the payment
     */
    public function processPaymentForm($data, $host, $order): void
    {
        \Log::info('TastyWallet processPaymentForm called', [
            'data' => $data,
            'host_code' => $host->code ?? 'unknown',
            'order_id' => $order->order_id ?? 'unknown',
        ]);

        $customer = Auth::customer();
        if (!$customer) {
            throw new ApplicationException('You must be logged in to pay with Tasty Wallet');
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
        $orderTotal = (float) $order->order_total;

        // Check sufficient balance
        if (!$wallet->hasSufficientBalance($orderTotal)) {
            $balance = number_format($wallet->balance, 0);
            $needed = number_format($orderTotal, 0);
            throw new ApplicationException("Insufficient wallet balance. You have UGX {$balance} but need UGX {$needed}. Please top up your wallet first.");
        }

        try {
            // Deduct from wallet
            $transaction = $wallet->pay(
                $orderTotal,
                $order->order_id,
                'Payment for Order #' . $order->order_id
            );

            \Log::info('TastyWallet payment deducted', [
                'transaction_id' => $transaction->id,
                'amount' => $orderTotal,
                'new_balance' => $wallet->fresh()->balance,
            ]);

            // Add 5% cashback
            $cashbackRate = 0.05;
            $cashbackAmount = $orderTotal * $cashbackRate;
            $cashbackTx = $wallet->addCashback($cashbackAmount, $order->order_id);

            \Log::info('TastyWallet cashback added', [
                'cashback_amount' => $cashbackAmount,
                'new_balance' => $wallet->fresh()->balance,
            ]);

            // Update order status
            $orderStatus = $host->order_status ?? 1;
            $order->updateOrderStatus($orderStatus, ['notify' => false]);
            $order->markAsPaymentProcessed();
            
            \Log::info('TastyWallet payment processed successfully');

        } catch (\Exception $e) {
            \Log::error('TastyWallet payment error: ' . $e->getMessage());
            throw new ApplicationException('Payment failed: ' . $e->getMessage());
        }
    }
}
