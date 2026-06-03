<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Api\LocationScheduleController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\OrderTrackingController;
use App\Http\Controllers\Web\GroupOrderWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

// AI Chatbot route
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// Location Schedule routes (web routes to maintain session with TastyIgniter)
Route::prefix('ajax/location-schedule')->middleware(['web', 'igniter'])->group(function () {
    Route::get('/current', [LocationScheduleController::class, 'current']);
    Route::post('/set', [LocationScheduleController::class, 'setSchedule']);
    Route::get('/timeslots', [LocationScheduleController::class, 'getTimeslots']);
    Route::post('/clear', [LocationScheduleController::class, 'clearSchedule']);
});

// Subscription routes (web routes to maintain session with TastyIgniter auth)
Route::prefix('ajax/subscriptions')->middleware(['web', 'igniter'])->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'plans']);
    Route::get('/current', [SubscriptionController::class, 'current']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/use-meal', [SubscriptionController::class, 'useMeal']);
    Route::post('/pause', [SubscriptionController::class, 'pause']);
    Route::post('/resume', [SubscriptionController::class, 'resume']);
    Route::post('/cancel', [SubscriptionController::class, 'cancel']);
    Route::post('/toggle-auto-renew', [SubscriptionController::class, 'toggleAutoRenew']);
    Route::get('/history', [SubscriptionController::class, 'history']);
});

// Order Tracking routes (web routes - mostly public, no auth needed)
Route::prefix('ajax/tracking')->group(function () {
    Route::post('/track', [OrderTrackingController::class, 'track']);
    Route::get('/status/{orderId}', [OrderTrackingController::class, 'status']);
    Route::get('/statuses', [OrderTrackingController::class, 'statuses']);
});

// Orders management routes
Route::prefix('ajax/orders')->group(function () {
    Route::get('/list', [OrderTrackingController::class, 'listOrders']);
});

// Group Order routes (web routes to maintain session with TastyIgniter auth)
Route::prefix('ajax/group-orders')->middleware(['web', 'igniter'])->group(function () {
    Route::get('/', [GroupOrderWebController::class, 'index']);
    Route::post('/', [GroupOrderWebController::class, 'store']);
    Route::get('/current', [GroupOrderWebController::class, 'current']);
    Route::post('/join', [GroupOrderWebController::class, 'join']);
    Route::post('/set-active', [GroupOrderWebController::class, 'setActive']);
    Route::post('/clear-active', [GroupOrderWebController::class, 'clearActive']);
    Route::post('/update-items', [GroupOrderWebController::class, 'updateItems']);
    Route::post('/mark-ready', [GroupOrderWebController::class, 'markReady']);
    Route::post('/sync-cart', [GroupOrderWebController::class, 'syncCart']);
    Route::get('/{id}', [GroupOrderWebController::class, 'show']);
    Route::get('/{id}/combined-cart', [GroupOrderWebController::class, 'getCombinedCart']);
    Route::post('/{id}/finalize', [GroupOrderWebController::class, 'finalize']);
    Route::post('/{id}/cancel', [GroupOrderWebController::class, 'cancel']);
    Route::post('/{id}/leave', [GroupOrderWebController::class, 'leave']);

    // Split-payment (Marz) endpoints
    Route::get('/{id}/payment-summary', [\App\Http\Controllers\Web\GroupOrderPaymentController::class, 'summary']);
    Route::post('/{id}/pay-share', [\App\Http\Controllers\Web\GroupOrderPaymentController::class, 'payShare']);
    Route::get('/{id}/share-status/{participantId}', [\App\Http\Controllers\Web\GroupOrderPaymentController::class, 'shareStatus']);
    Route::post('/{id}/place-order', [\App\Http\Controllers\Web\GroupOrderPaymentController::class, 'placeOrder']);
});

// Payment routes - using web middleware only, auth handled in controller
Route::prefix('ajax/payments')->middleware(['web'])->group(function () {
    Route::get('/config', [PaymentController::class, 'config'])->name('payment.config');
    Route::post('/initialize', [PaymentController::class, 'initialize'])->name('payment.initialize');
    Route::get('/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::get('/history', [PaymentController::class, 'history'])->name('payment.history');
});

// Payment callback and webhook (public routes)
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
// Marz Wallet webhook
Route::post('/payment/marz-webhook', [PaymentController::class, 'webhookMarz'])->name('payment.marz-webhook');

// Tasty Wallet routes
Route::prefix('ajax/wallet')->middleware(['web'])->group(function () {
    Route::get('/balance', [WalletController::class, 'balance'])->name('wallet.balance');
    Route::get('/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
    Route::post('/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::get('/verify-topup', [WalletController::class, 'verifyTopup'])->name('wallet.verify-topup');
    Route::post('/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
});

// Reservation routes with commitment fee
Route::prefix('ajax/reservation')->middleware(['web'])->group(function () {
    Route::post('/create', [\App\Http\Controllers\ReservationController::class, 'create'])->name('reservation.create');
    Route::get('/fee', [\App\Http\Controllers\ReservationController::class, 'getFee'])->name('reservation.fee');
    Route::get('/status/{id}', [\App\Http\Controllers\ReservationController::class, 'status'])->name('reservation.status');
});
