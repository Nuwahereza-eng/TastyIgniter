<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Get all available subscription plans
     */
    public function plans(): JsonResponse
    {
        $plans = SubscriptionPlan::active()
            ->orderByPrice()
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'formatted_price' => $plan->getFormattedPrice(),
                    'billing_period' => $plan->billing_period,
                    'billing_period_label' => $plan->getBillingPeriodLabel(),
                    'meals_per_period' => $plan->meals_per_period,
                    'price_per_meal' => $plan->getPricePerMeal(),
                    'features' => $plan->features,
                    'subscribers_count' => $plan->getActiveSubscribersCount(),
                ];
            });

        return response()->json([
            'success' => true,
            'plans' => $plans,
        ]);
    }

    /**
     * Get the current customer's subscription
     */
    public function current(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => true,
                'has_subscription' => false,
                'subscription' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'has_subscription' => true,
            'subscription' => [
                'id' => $subscription->id,
                'plan' => $subscription->plan,
                'status' => $subscription->status,
                'is_active' => $subscription->isActive(),
                'started_at' => $subscription->started_at,
                'expires_at' => $subscription->expires_at,
                'days_remaining' => $subscription->getDaysUntilExpiration(),
                'meals_remaining' => $subscription->meals_remaining,
                'meals_used_percentage' => $subscription->getMealsUsedPercentage(),
                'auto_renew' => $subscription->auto_renew,
                'served_orders' => $subscription->mealUsages()->limit(10)->get()->map(function ($u) {
                    return [
                        'order_id' => $u->order_id,
                        'formatted_id' => 'UGA-' . str_pad((string) $u->order_id, 5, '0', STR_PAD_LEFT),
                        'used_at' => $u->used_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Subscribe to a plan.
     *
     * Flow:
     *   1. Validate plan + payment fields.
     *   2. Reject if customer already has an active subscription.
     *   3. Initialize a Marz payment (subscription type) via PaymentController::initialize().
     *   4. Return tx_ref + (optional) redirect_url. The Subscription is activated
     *      on Marz webhook / callback success by PaymentController::activateSubscription().
     */
    public function subscribe(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|integer|exists:subscription_plans,id',
            'auto_renew' => 'boolean',
            'payment_method' => 'required|in:mtn,airtel,card,marz',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'preferred_delivery_time' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingSubscription = Subscription::byCustomer($customerId)
            ->active()
            ->first();

        if ($existingSubscription) {
            return response()->json([
                'success' => false,
                'error' => 'You already have an active subscription. Please cancel or wait for it to expire before subscribing to a new plan.',
            ], 400);
        }

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        if (!$plan->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'This plan is no longer available',
            ], 400);
        }

        // Pull email/name from the authenticated customer when not supplied.
        $customer = null;
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
        }
        $email = $request->input('email') ?: ($customer->email ?? null);
        $name = $request->input('name')
            ?: trim((string) (($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')))
            ?: 'Customer';

        if (!$email) {
            return response()->json([
                'success' => false,
                'error' => 'Email is required to start a subscription.',
            ], 422);
        }

        // Stash subscription preferences in Payment metadata so the webhook
        // can rebuild the Subscription row on successful capture.
        $paymentRequest = Request::create('/ajax/payments/initialize', 'POST', [
            'amount' => (float) $plan->price,
            'email' => $email,
            'phone' => $request->input('phone'),
            'name' => $name,
            'type' => \App\Models\Payment::TYPE_SUBSCRIPTION,
            'plan_id' => $plan->id,
            'payment_method' => $request->input('payment_method'),
        ]);
        $paymentRequest->setLaravelSession($request->session());
        $paymentRequest->setUserResolver(fn() => $request->user());

        /** @var \App\Http\Controllers\PaymentController $paymentController */
        $paymentController = app(\App\Http\Controllers\PaymentController::class);
        $response = $paymentController->initialize($paymentRequest);
        $payload = json_decode($response->getContent(), true) ?: [];

        // Persist subscription preferences (auto_renew, delivery prefs) so the
        // webhook activator can apply them when creating the Subscription row.
        if (!empty($payload['payment_id'])) {
            $payment = \App\Models\Payment::find($payload['payment_id']);
            if ($payment) {
                $payment->metadata = array_merge($payment->metadata ?? [], [
                    'plan_id' => $plan->id,
                    'auto_renew' => (bool) ($request->input('auto_renew', true)),
                    'delivery_address' => $request->input('delivery_address'),
                    'preferred_delivery_time' => $request->input('preferred_delivery_time'),
                ]);
                $payment->save();
            }
        }

        return response()->json(array_merge($payload, [
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => (float) $plan->price,
                'billing_period' => $plan->billing_period,
            ],
        ]), $response->getStatusCode());
    }

    /**
     * Use a meal from subscription
     */
    public function useMeal(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->active()
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscription found',
            ], 404);
        }

        if (!$subscription->hasMealsRemaining()) {
            return response()->json([
                'success' => false,
                'error' => 'No meals remaining in your subscription',
            ], 400);
        }

        $subscription->useMeal();

        return response()->json([
            'success' => true,
            'meals_remaining' => $subscription->meals_remaining,
            'message' => 'Meal deducted from subscription',
        ]);
    }

    /**
     * Pause subscription
     */
    public function pause(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->where('status', Subscription::STATUS_ACTIVE)
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscription to pause',
            ], 404);
        }

        $subscription->pause();

        return response()->json([
            'success' => true,
            'message' => 'Subscription paused',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Resume subscription
     */
    public function resume(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->where('status', Subscription::STATUS_PAUSED)
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'error' => 'No paused subscription to resume',
            ], 404);
        }

        $subscription->resume();

        return response()->json([
            'success' => true,
            'message' => 'Subscription resumed',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_PAUSED])
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscription to cancel',
            ], 404);
        }

        $subscription->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled. You can still use remaining meals until expiration.',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Toggle auto-renew
     */
    public function toggleAutoRenew(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::byCustomer($customerId)
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_PAUSED])
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'error' => 'No subscription found',
            ], 404);
        }

        $subscription->auto_renew = !$subscription->auto_renew;
        $subscription->save();

        return response()->json([
            'success' => true,
            'auto_renew' => $subscription->auto_renew,
            'message' => $subscription->auto_renew 
                ? 'Auto-renewal enabled' 
                : 'Auto-renewal disabled',
        ]);
    }

    /**
     * Get subscription history
     */
    public function history(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscriptions = Subscription::byCustomer($customerId)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Also surface every subscription-related payment so the user has a
        // full audit trail (renewals, simulated, failed, refunded, etc.) — not
        // just the single active Subscription row.
        $payments = \App\Models\Payment::where('customer_id', $customerId)
            ->where('payment_type', \App\Models\Payment::TYPE_SUBSCRIPTION)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'tx_ref' => $p->tx_ref,
                    'amount' => (float) $p->amount,
                    'currency' => $p->currency,
                    'status' => $p->status,
                    'provider' => $p->provider,
                    'payment_method' => $p->payment_method,
                    'simulated' => (bool) ($p->metadata['simulated'] ?? false),
                    'created_at' => $p->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
            'payments' => $payments,
        ]);
    }

    /**
     * Get the current customer ID
     */
    private function getCustomerId(): ?int
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }

        if (Auth::check()) {
            return Auth::id();
        }

        return null;
    }
}
