<nav class="navbar navbar-light navbar-top navbar-expand-md py-2">
    <div class="container">
        <a class="navbar-brand" href="{{ page_url('home') }}">
            @if($theme->logo_image ?? false)
                <img
                    class="img-logo"
                    alt="{{ setting('site_name') }}"
                    src="{{ media_url($theme->logo_image) }}"
                    style="max-height: 45px;"
                />
            @elseif($theme->logo_text ?? false)
                <span class="text-logo">{{ $theme->logo_text }}</span>
            @else
                <img
                    class="img-logo"
                    alt="{{ $site_name ?? 'UgaEats' }}"
                    src="{{ asset('vendor/igniter-orange/images/favicon.ico') }}"
                    style="max-height: 45px;"
                />
            @endif
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
            
            <!-- Premium Features Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="premiumFeaturesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-crown me-1"></i> Premium
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="premiumFeaturesDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#group-orders') }}">
                                <i class="fa fa-users text-primary me-2"></i>Group Orders
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#scheduled-orders') }}">
                                <i class="fa fa-clock text-warning me-2"></i>Schedule Order
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#subscriptions') }}">
                                <i class="fa fa-calendar-check text-success me-2"></i>Meal Plans
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/track-order') }}">
                                <i class="fa fa-motorcycle text-info me-2"></i>Track Order
                            </a>
                        </li>
                    </ul>
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
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ page_url('account.logout') }}"><i class="fa fa-sign-out me-2"></i>Logout</a></li>
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
