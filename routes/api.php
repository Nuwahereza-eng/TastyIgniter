<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroupOrderController;
use App\Http\Controllers\Api\ScheduledOrderController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\OrderTrackingController;
use App\Http\Controllers\Api\LocationScheduleController;
use App\Http\Controllers\Api\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*
|--------------------------------------------------------------------------
| Premium Features API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// GROUP ORDERS
// ==========================================
Route::prefix('group-orders')->group(function () {
    // Public route - join group order
    Route::post('/join', [GroupOrderController::class, 'join']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [GroupOrderController::class, 'index']);
        Route::post('/', [GroupOrderController::class, 'store']);
        Route::get('/{groupOrder}', [GroupOrderController::class, 'show']);
        Route::put('/{groupOrder}/items', [GroupOrderController::class, 'updateItems']);
        Route::post('/{groupOrder}/finalize', [GroupOrderController::class, 'finalize']);
        Route::post('/{groupOrder}/cancel', [GroupOrderController::class, 'cancel']);
    });
});

// ==========================================
// SCHEDULED ORDERS
// ==========================================
Route::prefix('scheduled-orders')->group(function () {
    // Public route - get time slots
    Route::get('/time-slots', [ScheduledOrderController::class, 'getTimeSlots']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ScheduledOrderController::class, 'index']);
        Route::post('/', [ScheduledOrderController::class, 'store']);
        Route::get('/{scheduledOrder}', [ScheduledOrderController::class, 'show']);
        Route::put('/{scheduledOrder}', [ScheduledOrderController::class, 'update']);
        Route::post('/{scheduledOrder}/cancel', [ScheduledOrderController::class, 'cancel']);
        Route::delete('/{scheduledOrder}', [ScheduledOrderController::class, 'destroy']);
        Route::post('/{scheduledOrder}/skip-next', [ScheduledOrderController::class, 'skipNext']);
    });
});

// ==========================================
// SUBSCRIPTIONS
// ==========================================
Route::prefix('subscriptions')->group(function () {
    // Public routes
    Route::get('/plans', [SubscriptionController::class, 'plans']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/current', [SubscriptionController::class, 'current']);
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
        Route::post('/use-meal', [SubscriptionController::class, 'useMeal']);
        Route::post('/pause', [SubscriptionController::class, 'pause']);
        Route::post('/resume', [SubscriptionController::class, 'resume']);
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
        Route::post('/toggle-auto-renew', [SubscriptionController::class, 'toggleAutoRenew']);
        Route::get('/history', [SubscriptionController::class, 'history']);
    });
});

// ==========================================
// ORDER TRACKING
// ==========================================
Route::prefix('tracking')->group(function () {
    // Public routes - customers can track without auth
    Route::post('/track', [OrderTrackingController::class, 'track']);
    Route::get('/status/{orderId}', [OrderTrackingController::class, 'status']);
    Route::get('/statuses', [OrderTrackingController::class, 'statuses']);
    
    // Admin/Rider routes (should have additional middleware in production)
    Route::put('/{orderId}/status', [OrderTrackingController::class, 'updateStatus']);
    Route::post('/{orderId}/assign-rider', [OrderTrackingController::class, 'assignRider']);
    Route::put('/{orderId}/location', [OrderTrackingController::class, 'updateLocation']);
    Route::post('/{orderId}/picked-up', [OrderTrackingController::class, 'markPickedUp']);
    Route::post('/{orderId}/delivered', [OrderTrackingController::class, 'markDelivered']);
    Route::get('/active', [OrderTrackingController::class, 'activeDeliveries']);
    Route::get('/available-riders', [OrderTrackingController::class, 'availableRiders']);
});

// ==========================================
// LOCATION SCHEDULE (for setting order timeslot)
// ==========================================
Route::prefix('location-schedule')->group(function () {
    // Public routes - these use session to store schedule
    Route::get('/current', [LocationScheduleController::class, 'current']);
    Route::post('/set', [LocationScheduleController::class, 'setSchedule']);
    Route::get('/timeslots', [LocationScheduleController::class, 'getTimeslots']);
    Route::post('/clear', [LocationScheduleController::class, 'clearSchedule']);
});

// ==========================================
// TASTY WALLET
// ==========================================
Route::prefix('wallet')->group(function () {
    Route::get('/', [WalletController::class, 'getWallet']);
    Route::get('/transactions', [WalletController::class, 'getTransactions']);
    Route::post('/deposit', [WalletController::class, 'deposit']);
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
});
