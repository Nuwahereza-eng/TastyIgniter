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
            ],
        ]);
    }

    /**
     * Subscribe to a plan
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if customer already has an active subscription
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

        // Calculate expiration based on billing period
        $expiresAt = match($plan->billing_period) {
            SubscriptionPlan::PERIOD_WEEKLY => now()->addWeek(),
            SubscriptionPlan::PERIOD_MONTHLY => now()->addMonth(),
            SubscriptionPlan::PERIOD_YEARLY => now()->addYear(),
            default => now()->addMonth(),
        };

        $subscription = Subscription::create([
            'customer_id' => $customerId,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_ACTIVE,
            'started_at' => now(),
            'expires_at' => $expiresAt,
            'meals_remaining' => $plan->meals_per_period,
            'auto_renew' => $request->auto_renew ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to ' . $plan->name . ' plan',
            'subscription' => $subscription->load('plan'),
        ], 201);
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

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
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
