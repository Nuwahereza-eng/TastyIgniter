@php
    $wallet = $wallet ?? null;
@endphp

<div class="tasty-wallet-payment">
    @if($wallet && $wallet->is_active)
        <div class="wallet-balance-card bg-gradient p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #FF4900 0%, #FF6B35 100%);">
            <div class="d-flex align-items-center text-white">
                <div class="wallet-icon me-3">
                    <i class="fa fa-wallet" style="font-size: 2rem;"></i>
                </div>
                <div>
                    <div class="small opacity-75">Tasty Wallet Balance</div>
                    <div class="fw-bold fs-4">{{ $wallet->getFormattedBalance() }}</div>
                </div>
            </div>
        </div>
        
        @if($wallet->balance > 0)
            <div class="alert alert-success small mb-0">
                <i class="fa fa-check-circle me-1"></i>
                Pay with your wallet and get <strong>5% cashback</strong> on this order!
            </div>
        @else
            <div class="alert alert-warning small mb-0">
                <i class="fa fa-exclamation-triangle me-1"></i>
                Your wallet is empty. <a href="/account/wallet" class="alert-link">Top up now</a>
            </div>
        @endif
    @else
        <div class="alert alert-info small mb-0">
            <i class="fa fa-info-circle me-1"></i>
            Create a Tasty Wallet to enjoy cashback rewards! <a href="/account/wallet" class="alert-link">Get Started</a>
        </div>
    @endif
</div>
