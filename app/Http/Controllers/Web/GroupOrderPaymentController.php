<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GroupOrder;
use App\Models\GroupOrderParticipant;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupOrderPaymentController extends Controller
{
    /**
     * Resolve the current customer (logged-in user) or null.
     */
    private function currentCustomer(): ?\Igniter\User\Models\Customer
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)
            && \Igniter\User\Facades\Auth::check()) {
            return \Igniter\User\Facades\Auth::customer();
        }
        return null;
    }

    /**
     * Build a per-participant summary (split breakdown + paid status).
     */
    public function summary(Request $request, int $id): JsonResponse
    {
        $group = GroupOrder::with('participants')->find($id);
        if (!$group) {
            return response()->json(['success' => false, 'error' => 'Group order not found'], 404);
        }

        $customer = $this->currentCustomer();
        $customerId = $customer?->customer_id;

        // Optionally persist a split method change (host-only).
        $newSplit = $request->query('split_method');
        if ($newSplit && $customerId === $group->host_customer_id) {
            // Map UI aliases to DB enum values.
            $map = [
                'by_item' => GroupOrder::SPLIT_BY_ITEM,
                'host_pays' => GroupOrder::SPLIT_HOST_PAYS,
                'individual' => GroupOrder::SPLIT_BY_ITEM,
                'equal' => GroupOrder::SPLIT_EQUAL,
                'host' => GroupOrder::SPLIT_HOST_PAYS,
            ];
            $normalized = $map[$newSplit] ?? null;
            if ($normalized) {
                $group->split_method = $normalized;
                $group->save();
            }
        }

        // Always recompute and persist shares so the breakdown reflects the
        // latest carts / split method.
        $group->applyShares();
        $group->refresh()->load('participants');

        $myParticipant = $group->participants
            ->firstWhere('customer_id', $customerId);

        $participants = $group->participants->map(function ($p) use ($customerId) {
            return [
                'id' => $p->id,
                'customer_id' => $p->customer_id,
                'name' => $p->getDisplayName(),
                'is_host' => (bool) $p->is_host,
                'is_me' => $customerId && $p->customer_id === $customerId,
                'subtotal' => (float) $p->subtotal,
                'share_amount' => (float) $p->share_amount,
                'has_paid' => (bool) $p->has_paid,
                'paid_at' => $p->paid_at,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'group_order' => [
                'id' => $group->id,
                'code' => $group->code,
                'name' => $group->name,
                'status' => $group->status,
                'split_method' => $group->split_method,
                'total_amount' => (float) $group->total_amount,
                'is_fully_paid' => $group->isFullyPaid(),
            ],
            'me' => $myParticipant ? [
                'participant_id' => $myParticipant->id,
                'share_amount' => (float) $myParticipant->share_amount,
                'has_paid' => (bool) $myParticipant->has_paid,
            ] : null,
            'participants' => $participants,
        ]);
    }

    /**
     * Initialize a Marz payment for the current customer's share.
     *
     * Body: { participant_id?, payment_method: mtn|airtel|card|marz, phone?: string }
     * The participant_id is optional — by default the caller pays their own share,
     * but the host can pay any unpaid share (useful for SPLIT_HOST_PAYS).
     */
    public function payShare(Request $request, int $id): JsonResponse
    {
        $customer = $this->currentCustomer();
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Please login to pay your share'], 401);
        }

        $request->validate([
            'participant_id' => 'nullable|integer',
            'payment_method' => 'required|in:mtn,airtel,card,marz',
            'phone' => 'nullable|string',
        ]);

        $group = GroupOrder::with('participants')->find($id);
        if (!$group) {
            return response()->json(['success' => false, 'error' => 'Group order not found'], 404);
        }

        if (!in_array($group->status, [GroupOrder::STATUS_OPEN, GroupOrder::STATUS_CLOSED], true)) {
            return response()->json(['success' => false, 'error' => 'Group order is not open for payment'], 400);
        }

        // Recompute shares before billing so split changes are honoured.
        $group->applyShares();
        $group->refresh()->load('participants');

        $isHost = $group->host_customer_id === $customer->customer_id;
        $targetParticipantId = $request->input('participant_id');

        if ($targetParticipantId) {
            $participant = $group->participants->firstWhere('id', $targetParticipantId);
            if (!$participant) {
                return response()->json(['success' => false, 'error' => 'Participant not found'], 404);
            }
            if (!$isHost && $participant->customer_id !== $customer->customer_id) {
                return response()->json(['success' => false, 'error' => 'You can only pay your own share'], 403);
            }
        } else {
            $participant = $group->participants->firstWhere('customer_id', $customer->customer_id);
            if (!$participant) {
                return response()->json(['success' => false, 'error' => 'You are not a participant in this group'], 403);
            }
        }

        if ($participant->has_paid) {
            return response()->json(['success' => false, 'error' => 'This share has already been paid'], 400);
        }

        $amount = (float) $participant->share_amount;
        if ($amount <= 0) {
            // Nothing to pay (e.g. SPLIT_HOST_PAYS for non-hosts). Auto-mark as paid.
            $participant->update(['has_paid' => true, 'paid_at' => now()]);
            return response()->json([
                'success' => true,
                'auto_paid' => true,
                'message' => 'No payment required for your share.',
            ]);
        }

        $method = $request->input('payment_method');
        $isMobileMoney = in_array($method, ['mtn', 'airtel'], true);
        $phone = $request->input('phone') ?: $customer->telephone;
        if ($isMobileMoney && empty($phone)) {
            return response()->json([
                'success' => false,
                'error' => 'A Mobile Money phone number is required for ' . strtoupper($method) . ' payments.',
            ], 422);
        }

        $service = app(PaymentService::class);
        // Only fall back to the dev-mode simulation when Marz credentials are
        // genuinely absent. If Marz is configured we must always charge the
        // participant for real, regardless of the legacy Flutterwave
        // isTestMode() heuristic.
        if (!$service->hasMarzCredentials()) {
            // Dev simulation: instantly mark the share as paid.
            $tx = 'SIM-GS-' . strtoupper(\Illuminate\Support\Str::random(8));
            $payment = Payment::create([
                'customer_id' => $customer->customer_id,
                'tx_ref' => $tx,
                'amount' => $amount,
                'currency' => 'UGX',
                'payment_method' => $method,
                'payment_type' => Payment::TYPE_GROUP_SHARE,
                'reference_id' => $participant->id,
                'status' => 'successful',
                'provider' => 'demo',
                'metadata' => ['simulated' => true],
            ]);
            $participant->update([
                'has_paid' => true,
                'payment_tx_ref' => $tx,
                'paid_at' => now(),
            ]);
            $group->refresh();
            if ($group->isFullyPaid() && $group->status === GroupOrder::STATUS_OPEN) {
                $group->update(['status' => GroupOrder::STATUS_CLOSED]);
            }
            return response()->json([
                'success' => true,
                'simulated' => true,
                'tx_ref' => $tx,
                'message' => 'Share paid (demo mode).',
            ]);
        }

        $init = $service->initializeMarzPayment([
            'amount' => $amount,
            'currency' => 'UGX',
            'country' => 'UG',
            'method' => $isMobileMoney ? 'mobile_money' : 'card',
            'email' => $customer->email,
            'phone' => $isMobileMoney ? $phone : null,
            'name' => $customer->full_name,
            'description' => 'Group order share — ' . ($group->name ?: ('#' . $group->id)),
            'redirect_url' => route('payment.callback'),
            'meta' => [
                'type' => 'group_share',
                'reference_id' => $participant->id,
                'group_order_id' => $group->id,
                'customer_id' => $customer->customer_id,
            ],
        ]);

        if (empty($init['success'])) {
            return response()->json([
                'success' => false,
                'error' => $init['message'] ?? 'Could not start the payment. Please try again.',
            ], 400);
        }

        Payment::create([
            'customer_id' => $customer->customer_id,
            'tx_ref' => $init['tx_ref'],
            'amount' => $amount,
            'currency' => 'UGX',
            'payment_method' => $method,
            'payment_type' => Payment::TYPE_GROUP_SHARE,
            'reference_id' => $participant->id,
            'status' => Payment::STATUS_PENDING,
            'provider' => 'marz',
            'metadata' => [
                'group_order_id' => $group->id,
                'api_method' => $isMobileMoney ? 'mobile_money' : 'card',
                'phone' => $phone,
                'transaction_uuid' => $init['transaction_uuid'] ?? null,
            ],
        ]);

        $participant->update(['payment_tx_ref' => $init['tx_ref']]);

        return response()->json([
            'success' => true,
            'tx_ref' => $init['tx_ref'],
            'amount' => $amount,
            'redirect_url' => $init['link'] ?? null,
            'pending' => empty($init['link']),
            'message' => !empty($init['link'])
                ? 'Redirecting to Marz checkout...'
                : 'A Mobile Money prompt has been sent to ' . $phone . '. Approve it with your PIN.',
        ]);
    }

    /**
     * Poll the status of a participant's share. Actively verifies with Marz
     * so it works even if the webhook hasn't fired yet.
     */
    public function shareStatus(Request $request, int $id, int $participantId): JsonResponse
    {
        $participant = GroupOrderParticipant::where('group_order_id', $id)
            ->where('id', $participantId)
            ->first();
        if (!$participant) {
            return response()->json(['success' => false, 'error' => 'Participant not found'], 404);
        }

        if (!$participant->has_paid && $participant->payment_tx_ref) {
            $payment = Payment::where('tx_ref', $participant->payment_tx_ref)->first();
            if ($payment && $payment->provider === 'marz' && $payment->status !== 'successful') {
                try {
                    $verification = app(PaymentService::class)
                        ->verifyMarzTransaction($participant->payment_tx_ref);
                    if (!empty($verification['success'])) {
                        $payment->update([
                            'status' => 'successful',
                            'verified_at' => now(),
                            'metadata' => array_merge($payment->metadata ?? [], [
                                'verification' => $verification['data'] ?? null,
                            ]),
                        ]);
                        $participant->update([
                            'has_paid' => true,
                            'paid_at' => now(),
                        ]);
                        $group = $participant->groupOrder()->first();
                        if ($group && $group->isFullyPaid() && $group->status === GroupOrder::STATUS_OPEN) {
                            $group->update(['status' => GroupOrder::STATUS_CLOSED]);
                        }
                    } elseif (in_array(strtolower($verification['status'] ?? ''), ['failed', 'cancelled'], true)) {
                        $payment->update(['status' => strtolower($verification['status'])]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Group share verification poll failed', [
                        'participant_id' => $participantId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $payment = Payment::where('tx_ref', $participant->payment_tx_ref ?? '')->first();
        return response()->json([
            'success' => true,
            'participant_id' => $participant->id,
            'share_amount' => (float) $participant->share_amount,
            'has_paid' => (bool) $participant->has_paid,
            'paid_at' => $participant->paid_at,
            'payment_status' => $payment->status ?? ($participant->has_paid ? 'successful' : 'pending'),
        ]);
    }

    /**
     * Host places the final food order after every share has been paid.
     * Marks the group as ORDERED, clears the session, and returns a summary
     * payload the front-end uses to render a confirmation.
     */
    public function placeOrder(int $id): JsonResponse
    {
        $customer = $this->currentCustomer();
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'You must be logged in'], 401);
        }

        $group = GroupOrder::with('participants')->find($id);
        if (!$group) {
            return response()->json(['success' => false, 'error' => 'Group order not found'], 404);
        }

        if ((int) $group->host_customer_id !== (int) $customer->customer_id) {
            return response()->json(['success' => false, 'error' => 'Only the host can place the final order'], 403);
        }

        if ($group->status === GroupOrder::STATUS_ORDERED) {
            return response()->json([
                'success' => true,
                'message' => 'Order already placed.',
                'group_order' => $group,
            ]);
        }

        if (!in_array($group->status, [GroupOrder::STATUS_OPEN, GroupOrder::STATUS_CLOSED], true)) {
            return response()->json(['success' => false, 'error' => 'Group order is not in a placeable state'], 400);
        }

        // Refresh shares so we work with the latest totals.
        $group->applyShares();
        $group->refresh();

        if (!$group->isFullyPaid()) {
            return response()->json([
                'success' => false,
                'error' => 'Not all participants have paid their share yet.',
            ], 400);
        }

        $group->update(['status' => GroupOrder::STATUS_ORDERED]);

        if ((int) session('active_group_order_id') === (int) $group->id) {
            session()->forget('active_group_order_id');
        }

        Log::info('Group order placed by host', [
            'group_order_id' => $group->id,
            'host_customer_id' => $customer->customer_id,
            'total' => (float) $group->total_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order placed! The restaurant has been notified.',
            'group_order' => $group->load('participants'),
        ]);
    }
}
