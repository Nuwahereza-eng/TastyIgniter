@php
    $customer = \Igniter\User\Facades\Auth::customer();
    $walletBalance = 0;
    if ($customer) {
        $wallet = \App\Models\TastyWallet::where('customer_id', $customer->customer_id)->first();
        $walletBalance = $wallet ? $wallet->balance : 0;
    }
    $cartTotal = \Igniter\Cart\Facades\Cart::total();
    $hasSufficientBalance = $walletBalance >= $cartTotal;
@endphp

<div class="tasty-wallet-payment mt-3 p-3 bg-light rounded">
    <div class="wallet-balance-card bg-gradient p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #FF4900 0%, #FF6B35 100%);">
        <div class="d-flex align-items-center justify-content-between text-white">
            <div class="d-flex align-items-center">
                <div class="wallet-icon me-3">
                    <i class="fa fa-wallet" style="font-size: 2rem;"></i>
                </div>
                <div>
                    <div class="small opacity-75">Your Wallet Balance</div>
                    <div class="fw-bold fs-4">UGX {{ number_format($walletBalance, 0) }}</div>
                </div>
            </div>
            @if($hasSufficientBalance)
                <span class="badge bg-success px-3 py-2">
                    <i class="fa fa-check me-1"></i> Sufficient
                </span>
            @else
                <span class="badge bg-danger px-3 py-2">
                    <i class="fa fa-exclamation-triangle me-1"></i> Low Balance
                </span>
            @endif
        </div>
    </div>
    
    @if($hasSufficientBalance)
        <div class="alert alert-success small mb-2">
            <i class="fa fa-gift me-2"></i>
            <strong>Bonus!</strong> You'll earn <strong>5% cashback</strong> (UGX {{ number_format($cartTotal * 0.05, 0) }}) on this order!
        </div>
    @else
        <div class="alert alert-warning small mb-2">
            <i class="fa fa-exclamation-triangle me-2"></i>
            <strong>Insufficient Balance:</strong> You need UGX {{ number_format($cartTotal - $walletBalance, 0) }} more.
            <a href="/account/wallet" class="alert-link">Top up your wallet</a>
        </div>
    @endif
    
    <div class="text-muted small">
        <i class="fa fa-info-circle me-1"></i>
        Amount will be deducted from your Tasty Wallet upon confirmation.
    </div>
</div>
