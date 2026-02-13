<div class="nav flex-column">
    <a
        href="{{ page_url('account.account') }}"
        class="nav-item nav-link fw-medium {{ ($activePage == 'account-account') ? 'active' : 'text-reset' }}"
    ><span class="fa fa-user text-muted me-3"></span>@lang('igniter.orange::default.menu_my_account')</a>
    <a
        href="{{ page_url('account.address') }}"
        class="nav-item nav-link fw-medium {{ ($activePage == 'account-address') ? 'active' : 'text-reset' }}"
    ><span class="fa fa-book text-muted me-3"></span>@lang('igniter.orange::default.menu_address')</a>
    <a
        href="/account/wallet"
        class="nav-item nav-link fw-medium {{ ($activePage == 'account-wallet') ? 'active' : 'text-reset' }}"
    ><span class="fa fa-wallet text-warning me-3"></span>Tasty Wallet
        @php
            $customer = \Igniter\User\Facades\Auth::customer();
            $walletBalance = 0;
            if ($customer) {
                $wallet = \App\Models\TastyWallet::where('customer_id', $customer->customer_id)->first();
                $walletBalance = $wallet ? $wallet->balance : 0;
            }
        @endphp
        <span class="badge bg-warning text-dark ms-2">UGX {{ number_format($walletBalance, 0) }}</span>
    </a>
    <a
        href="{{ page_url('account.orders') }}"
        class="nav-item nav-link fw-medium {{ in_array($activePage, ['account-order', 'account-orders']) ? 'active' : 'text-reset' }}"
    ><span class="fa fa-receipt text-muted me-3"></span>@lang('igniter.orange::default.menu_recent_order')</a>
    <a
        href="{{ page_url('account.reservations') }}"
        class="nav-item nav-link fw-medium {{ in_array($activePage, ['account-reservation', 'account-reservations']) ? 'active' : 'text-reset' }}"
    ><span class="fa fa-calendar text-muted me-3"></span>@lang('igniter.orange::default.menu_recent_reservation')</a>
</div>
