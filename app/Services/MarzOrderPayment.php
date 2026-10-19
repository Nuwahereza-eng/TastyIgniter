<?php

namespace App\Services;

use App\Models\Payment;
use Igniter\Cart\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Lifecycle of a Marz Pay mobile money payment for a checkout order:
 * prompt sent -> paid, or no approval within the timeout -> expired.
 *
 * The Marz webhook normally confirms payments; refresh() also asks Marz
 * directly so payments complete even when the webhook cannot reach us.
 */
class MarzOrderPayment
{
    public const MAX_ATTEMPTS = 3;

    /** Seconds the customer has to approve the prompt on their phone. */
    public static function promptTimeout(): int
    {
        return max(60, (int)env('MARZ_PROMPT_TIMEOUT_SECONDS', 180));
    }

    /** Minutes after which an order with no completed payment is cancelled. */
    public static function abandonAfterMinutes(): int
    {
        return max(10, (int)env('MARZ_ABANDON_ORDER_MINUTES', 30));
    }

    public function __construct(protected PaymentService $payments) {}

    /** Most recent Marz payment row for the order. */
    public function latestPayment(Order $order): ?Payment
    {
        return Payment::where('payment_type', Payment::TYPE_ORDER)
            ->where('reference_id', $order->order_id)
            ->where('provider', 'marz')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Bring the payment up to date: ask Marz for its status and expire it
     * once the prompt timeout has passed without an approval.
     */
    public function refresh(Payment $payment): Payment
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            return $payment;
        }

        // The waiting page polls every few seconds; ask Marz at most every 10s.
        $timedOut = $this->secondsLeft($payment) <= 0;
        if (!$timedOut && !cache()->add('marz-verify-'.$payment->id, 1, 10)) {
            return $payment;
        }

        $verification = $this->payments->verifyMarzTransaction($payment->tx_ref);
        if (!empty($verification['success'])) {
            $payment->update([
                'status' => Payment::STATUS_SUCCESSFUL,
                'verified_at' => now(),
                'metadata' => array_merge($payment->metadata ?? [], ['verification' => $verification['data'] ?? null]),
            ]);
            $this->markOrderPaid($payment);

            return $payment;
        }

        $reported = strtolower((string)($verification['status'] ?? ''));
        if (in_array($reported, ['failed', 'cancelled', 'canceled', 'rejected', 'expired'], true)) {
            $this->fail($payment, 'declined');
        } elseif ($timedOut) {
            $this->fail($payment, 'timeout');
        }

        return $payment;
    }

    public function secondsLeft(Payment $payment): int
    {
        return max(0, static::promptTimeout() - (int)$payment->created_at->diffInSeconds(now()));
    }

    public function attempts(Order $order): int
    {
        return Payment::where('payment_type', Payment::TYPE_ORDER)
            ->where('reference_id', $order->order_id)
            ->where('provider', 'marz')
            ->count();
    }

    /**
     * Send a new prompt for an unpaid order (after a timeout or decline).
     *
     * @return array{success: bool, message?: string, payment?: Payment}
     */
    public function retry(Order $order): array
    {
        if ($order->isPaymentProcessed() || $this->isCanceled($order)) {
            return ['success' => false, 'message' => 'This order can no longer be paid.'];
        }

        $previous = $this->latestPayment($order);
        if ($previous && $previous->status === Payment::STATUS_PENDING && $this->secondsLeft($previous) > 0) {
            return ['success' => false, 'message' => 'A payment request is still waiting for approval on your phone.'];
        }

        if ($this->attempts($order) >= static::MAX_ATTEMPTS) {
            return ['success' => false, 'message' => 'Too many payment attempts. Please choose cash on delivery or contact us.'];
        }

        $phone = $previous->metadata['phone'] ?? $order->telephone;
        $init = $this->payments->initializeMarzPayment([
            'amount' => $order->order_total,
            'currency' => 'UGX',
            'country' => 'UG',
            'method' => 'mobile_money',
            'email' => $order->email,
            'phone' => $phone,
            'name' => trim($order->first_name.' '.$order->last_name),
            'description' => 'Order #'.$order->order_id.' at '.config('app.name'),
            'redirect_url' => route('payment.callback'),
            'meta' => ['type' => 'order', 'order_id' => $order->order_id, 'order_hash' => $order->hash],
        ]);

        if (empty($init['success'])) {
            return ['success' => false, 'message' => $init['message'] ?? 'Could not send the payment request.'];
        }

        $payment = Payment::create([
            'customer_id' => $order->customer_id,
            'tx_ref' => $init['tx_ref'],
            'amount' => $order->order_total,
            'currency' => 'UGX',
            'payment_method' => 'marz',
            'payment_type' => Payment::TYPE_ORDER,
            'reference_id' => $order->order_id,
            'status' => Payment::STATUS_PENDING,
            'provider' => 'marz',
            'metadata' => [
                'order_id' => $order->order_id,
                'order_hash' => $order->hash,
                'method' => $previous->metadata['method'] ?? 'mobile_money',
                'api_method' => 'mobile_money',
                'phone' => $phone,
                'transaction_uuid' => $init['transaction_uuid'] ?? null,
                'retry' => true,
            ],
        ]);

        $order->logPaymentAttempt('Marz mobile money prompt re-sent to '.$phone, 1, [], $init);

        return ['success' => true, 'payment' => $payment];
    }

    /** Customer chose to pay cash on delivery instead of mobile money. */
    public function switchToCash(Order $order): bool
    {
        if ($order->isPaymentProcessed() || $this->isCanceled($order)) {
            return false;
        }

        Payment::where('payment_type', Payment::TYPE_ORDER)
            ->where('reference_id', $order->order_id)
            ->where('provider', 'marz')
            ->where('status', Payment::STATUS_PENDING)
            ->update(['status' => Payment::STATUS_CANCELLED]);

        $order->payment = 'cod';
        $order->save();
        $order->logPaymentAttempt('Customer switched from Marz Pay to cash on delivery', 1);

        $codStatus = DB::table('payments')->where('code', 'cod')->value('data');
        $orderStatus = $codStatus ? (json_decode($codStatus, true)['order_status'] ?? null) : null;
        $order->updateOrderStatus((int)($orderStatus ?: setting('default_order_status')), ['notify' => false]);
        $order->markAsPaymentProcessed();

        return true;
    }

    /**
     * Cancel orders whose Marz payment never completed. Run every minute
     * from the scheduler (payments:expire-marz).
     */
    public function expireStale(): int
    {
        $count = 0;

        $pending = Payment::where('payment_type', Payment::TYPE_ORDER)
            ->where('provider', 'marz')
            ->where('status', Payment::STATUS_PENDING)
            ->where('created_at', '<', now()->subSeconds(static::promptTimeout()))
            ->where('created_at', '>', now()->subDays(2))
            ->get();
        foreach ($pending as $payment) {
            $this->refresh($payment);
        }

        // Only recent orders: older unpaid ones predate this clean-up and are
        // left for staff to review rather than cancelled in bulk.
        $orderIds = Payment::where('payment_type', Payment::TYPE_ORDER)
            ->where('provider', 'marz')
            ->where('created_at', '<', now()->subMinutes(static::abandonAfterMinutes()))
            ->where('created_at', '>', now()->subDays(2))
            ->distinct()
            ->pluck('reference_id');

        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            if (!$order || $order->payment !== 'marz' || $order->isPaymentProcessed() || $this->isCanceled($order)) {
                continue;
            }

            $paid = Payment::where('payment_type', Payment::TYPE_ORDER)
                ->where('reference_id', $orderId)
                ->where('status', Payment::STATUS_SUCCESSFUL)
                ->exists();
            if ($paid) {
                continue;
            }

            $order->addStatusHistory($this->canceledStatusId(), [
                'comment' => 'Cancelled automatically: Marz Pay payment was not completed.',
                'notify' => false,
            ]);
            $order->logPaymentAttempt('Order cancelled: Marz Pay payment not completed', 0);
            $count++;
        }

        return $count;
    }

    public function isCanceled(Order $order): bool
    {
        return (int)$order->status_id === $this->canceledStatusId();
    }

    /**
     * The "Canceled" order status. Resolved by name because the
     * canceled_order_status setting can point at the wrong status.
     */
    public function canceledStatusId(): int
    {
        $byName = DB::table('statuses')->where('status_for', 'order')
            ->whereIn(DB::raw('LOWER(status_name)'), ['canceled', 'cancelled'])
            ->value('status_id');

        return (int)($byName ?: setting('canceled_order_status'));
    }

    protected function fail(Payment $payment, string $reason): void
    {
        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'metadata' => array_merge($payment->metadata ?? [], ['failure_reason' => $reason]),
        ]);
    }

    /** Mark the order paid and move it to the Marz gateway's configured status. */
    public function markOrderPaid(Payment $payment): void
    {
        try {
            $order = Order::find($payment->reference_id);
            if (!$order || $order->isPaymentProcessed()) {
                return;
            }

            $order->logPaymentAttempt('Marz payment confirmed', 1, [], $payment->metadata ?? [], true);
            $order->markAsPaymentProcessed();

            $gatewayData = DB::table('payments')->where('code', 'marz')->value('data');
            $orderStatus = $gatewayData ? (json_decode($gatewayData, true)['order_status'] ?? null) : null;
            if ($orderStatus) {
                $order->updateOrderStatus((int)$orderStatus, ['notify' => false]);
            }
        } catch (Throwable $e) {
            Log::error('Failed to mark order paid for Marz payment', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
        }
    }
}
