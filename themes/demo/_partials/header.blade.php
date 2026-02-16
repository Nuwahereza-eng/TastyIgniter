<nav class="navbar navbar-light navbar-top navbar-expand-md py-2">
    <div class="container">
        <a class="navbar-brand" href="{{ page_url('home') }}">
            <img
                class="img-logo"
                alt="{{ setting('site_name') }}"
                src="{{ asset('themes/demo/assets/images/tastyigniter-white-logo.svg') }}"
                style="max-height: 45px;"
            />
        </a>
        
        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMainHeader"
            aria-controls="navbarMainHeader"
            aria-expanded="false"
            aria-label="Toggle navigation"
        ><span class="navbar-toggler-icon"></span></button>

        <div class="justify-content-end collapse navbar-collapse" id="navbarMainHeader">
            <!-- Main Navigation Menu -->
            <ul class="nav navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ page_url('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ page_url('locations') }}">Locations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ page_url('reservation.reservation') }}">Reservation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ page_url('contact') }}">Contact</a>
                </li>
            </ul>
            
            <!-- Premium Link -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ page_url('premium-features') }}">
                        <i class="fa fa-crown me-1"></i> Premium
                    </a>
                </li>
            </ul>

            <!-- Cart -->
            <livewire:igniter-orange::cart-box />
            
            <!-- Account Menu -->
            <ul class="navbar-nav ms-2">
                @if(Auth::isLogged())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-1"></i> Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="{{ page_url('account.account') }}"><i class="fa fa-user me-2"></i>My Account</a></li>
                            <li><a class="dropdown-item" href="{{ page_url('account.orders') }}"><i class="fa fa-list me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ page_url('account.address') }}"><i class="fa fa-map-marker me-2"></i>Address Book</a></li>
                            <li><a class="dropdown-item" href="/account/wallet"><i class="fa fa-wallet me-2 text-warning"></i>Tasty Wallet</a></li>
                            <li><a class="dropdown-item" href="/account/features"><i class="fa fa-star me-2 text-success"></i>Subscriptions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ page_url('account.login') }}">
                            <i class="fa fa-sign-in me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ page_url('account.register') }}">
                            <i class="fa fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
