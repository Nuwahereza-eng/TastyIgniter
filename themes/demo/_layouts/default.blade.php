---
description: Default layout
---
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ App::getLocale() }}" class="h-100">
<head>
    @include('igniter-orange::includes.head')
    
    <!-- Uganda Custom Styles -->
    <link rel="stylesheet" href="/themes/demo/assets/css/uganda-custom.css?v={{ time() }}">
    
    <!-- AI Chatbot Styles -->
    <link rel="stylesheet" href="/themes/demo/assets/css/chatbot.css">
    
    <!-- Flatpickr Calendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        /* Header Fix - Compact Size & Sticky positioning */
        .header { 
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
            background: linear-gradient(135deg, #FF4900 0%, #e04000 100%); 
        }
        /* Home page uses absolute positioning for hero overlay */
        body.home-page .header {
            position: absolute !important;
            background: transparent !important;
        }
        .header .navbar { padding: 0.5rem 0 !important; min-height: auto !important; background: transparent !important; }
        .header .navbar-brand .img-logo { max-height: 40px !important; }
        .header .nav-link { color: white !important; padding: 0.5rem 1rem !important; }
        .header .nav-link:hover { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .header .navbar-toggler-icon { filter: invert(1); }
        
        /* Dropdown Menu Styling */
        .header .dropdown-menu { 
            background: white; 
            border: 1px solid rgba(0,0,0,0.1); 
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); 
            border-radius: 8px;
            overflow: hidden;
            padding: 0.5rem 0;
            min-width: 200px;
        }
        .header .dropdown-menu .dropdown-item { 
            color: #333 !important; 
            padding: 0.5rem 1rem;
            margin: 0;
            border-radius: 0;
        }
        .header .dropdown-menu .dropdown-item:hover { 
            background: #f5f5f5 !important; 
            color: #FF4900 !important; 
        }
        .header .dropdown-menu .dropdown-divider {
            margin: 0.5rem 0;
        }
        
        /* Dropdown Fix - CSS hover fallback */
        .header .nav-item.dropdown:hover > .dropdown-menu { display: block; margin-top: 0; }
        .header .dropdown-menu { margin-top: 0; }
        
        /* Consolidated FAB Menu */
        .fab-container {
            position: fixed; bottom: 20px; right: 20px; z-index: 1040;
            display: flex; flex-direction: column-reverse; align-items: flex-end; gap: 12px;
        }
        .fab-main {
            width: 60px; height: 60px; background: linear-gradient(135deg, #FF4900, #FF6B35);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 26px; box-shadow: 0 4px 20px rgba(255,73,0,0.5);
            cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none; outline: none;
        }
        .fab-main:hover { transform: scale(1.1); box-shadow: 0 6px 25px rgba(255,73,0,0.6); }
        .fab-main.active { transform: rotate(45deg); background: #333; }
        .fab-main .fab-icon-open { display: block; }
        .fab-main .fab-icon-close { display: none; }
        .fab-main.active .fab-icon-open { display: none; }
        .fab-main.active .fab-icon-close { display: block; }
        
        .fab-options {
            display: flex; flex-direction: column; gap: 10px; opacity: 0;
            transform: translateY(20px) scale(0.8); pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .fab-container.active .fab-options {
            opacity: 1; transform: translateY(0) scale(1); pointer-events: all;
        }
        .fab-option {
            display: flex; align-items: center; gap: 10px; text-decoration: none;
            background: white; padding: 10px 16px; border-radius: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.15); transition: all 0.3s ease;
            color: #333; font-weight: 600; font-size: 14px; white-space: nowrap;
            cursor: pointer; border: none;
        }
        .fab-option:hover { transform: translateX(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.2); color: #333; text-decoration: none; }
        .fab-option i { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
        .fab-option.whatsapp i { background: #25D366; }
        .fab-option.call i { background: #007bff; }
        .fab-option.chat i { background: #FF4900; }
        
        /* Mobile optimization */
        @media (max-width: 576px) {
            .fab-container { bottom: 15px; right: 15px; }
            .fab-main { width: 55px; height: 55px; font-size: 24px; }
            .fab-option { padding: 8px 14px; font-size: 13px; }
            .fab-option i { width: 28px; height: 28px; font-size: 14px; }
        }
        body.modal-open .fab-container { z-index: 1000 !important; pointer-events: none; opacity: 0.3; }
        
        /* Footer Styling with Food Photo Background */
        .footer { 
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('/images/footer-bg.jpg') center/cover no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            color: #ccc !important; 
        }
        .footer h6 { color: white !important; }
        .footer a:hover { color: #FF4900 !important; }
    </style>
    
    @livewireStyles
</head>
<body class="d-flex flex-column h-100 {{ $this->page->bodyClass ?? '' }}">

<!-- HEADER -->
<header class="header">
    <nav class="navbar navbar-expand-md py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-end" href="{{ page_url('home') }}">
                @if(($this->page->bodyClass ?? '') === 'home-page')
                    <img class="img-logo" alt="TastyIgniter" src="/storage/media/uploads/tastyigniter-logo-only.svg" style="height: 50px; width: auto;">
                    <span class="fw-bold d-none d-sm-inline" style="font-size: 1.1rem; margin-left: 6px; line-height: 1; padding-bottom: 2px; color: #FF4900;">TastyIgniter</span>
                @else
                    <img class="img-logo" alt="TastyIgniter" src="{{ asset('themes/demo/assets/images/tastyigniter-white-logo.svg') }}" style="height: 50px; width: auto;">
                @endif
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainHeader">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMainHeader">
                <!-- Main Navigation -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ page_url('locations') }}">
                            <i class="fa fa-utensils me-1"></i> View Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ page_url('reservations') }}">
                            <i class="fa fa-calendar-alt me-1"></i> Reservations
                        </a>
                    </li>
                    <!-- Premium Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ page_url('premium-features') }}">
                            <i class="fa fa-crown me-1"></i> Premium
                        </a>
                    </li>
                    @if(Auth::isLogged())
                        <!-- Account Dropdown for Logged In Users -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user-circle me-1"></i> My Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ page_url('account.account') }}">
                                        <i class="fa fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/account/wallet">
                                        <i class="fa fa-wallet me-2"></i>Tasty Wallet
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ page_url('account.orders') }}">
                                        <i class="fa fa-receipt me-2"></i>My Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ page_url('account.reservations') }}">
                                        <i class="fa fa-calendar me-2"></i>My Reservations
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ page_url('account.address') }}">
                                        <i class="fa fa-map-marker-alt me-2"></i>Addresses
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="fa fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Login/Register for Guests -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ page_url('account.login') }}">
                                <i class="fa fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="{{ page_url('account.register') }}">
                                <i class="fa fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main role="main">
    <div id="page-wrapper">
        @themePage
    </div>
</main>

<!-- FOOTER -->
@unless($this->page->hideFooter ?? false)
<footer class="footer mt-auto py-4" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.7)), url('/images/footer-bg.jpg') center/cover no-repeat !important; background-size: cover !important;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ page_url('home') }}" class="text-muted text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ page_url('locations') }}" class="text-muted text-decoration-none">Locations</a></li>
                    <li class="mb-2"><a href="{{ page_url('reservation.reservation') }}" class="text-muted text-decoration-none">Reservation</a></li>
                    <li class="mb-2"><a href="{{ page_url('contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-3">Premium Features</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ page_url('premium-features') }}#group-orders" class="text-muted text-decoration-none"><i class="fa fa-users me-2"></i>Group Orders</a></li>
                    <li class="mb-2"><a href="{{ page_url('premium-features') }}#scheduled-orders" class="text-muted text-decoration-none"><i class="fa fa-clock me-2"></i>Schedule Order</a></li>
                    <li class="mb-2"><a href="{{ page_url('premium-features') }}#subscriptions" class="text-muted text-decoration-none"><i class="fa fa-calendar-check me-2"></i>Meal Plans</a></li>
                    <li class="mb-2"><a href="{{ page_url('track-order') }}" class="text-muted text-decoration-none"><i class="fa fa-motorcycle me-2"></i>Track Order</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-3">Follow Us</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="https://facebook.com/tastyigniter" target="_blank" class="social-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: white; border-radius: 8px;">
                        <i class="fab fa-facebook-f" style="color: #1877F2; font-size: 20px;"></i>
                    </a>
                    <a href="https://x.com/tastyigniter" target="_blank" class="social-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: white; border-radius: 8px;">
                        <i class="fab fa-x-twitter" style="color: #000; font-size: 20px;"></i>
                    </a>
                    <a href="https://tiktok.com/@tastyigniter" target="_blank" class="social-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: white; border-radius: 8px;">
                        <i class="fab fa-tiktok" style="color: #000; font-size: 20px;"></i>
                    </a>
                    <a href="https://instagram.com/tastyigniter" target="_blank" class="social-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: white; border-radius: 8px;">
                        <i class="fab fa-instagram" style="color: #E4405F; font-size: 20px;"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-3">Newsletter</h6>
                <livewire:igniter-orange::newsletter-subscribe-form />
            </div>
        </div>
        <hr class="my-3" style="border-color: #444;">
        <div class="text-center">
            <p class="mb-0 text-muted small">&copy; {{ date('Y') }} TastyIgniter. All rights reserved.</p>
        </div>
    </div>
</footer>
@endunless

<livewire:igniter-orange::utils.modal/>
<livewire:igniter-orange::utils.flash-message/>
@include('igniter-orange::includes.eucookiebanner')
@livewireScripts

{{-- Use custom scripts partial that bypasses asset combiner --}}
@include('demo::_partials.scripts')

<!-- Uganda Custom JavaScript -->
<script src="/themes/demo/assets/js/uganda-enhancements.js"></script>

<!-- AI Chatbot -->
<script src="/themes/demo/assets/js/chatbot.js"></script>

<!-- Initialize Bootstrap Dropdowns -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Initialize Flatpickr for reservation calendar
    function initReservationCalendar() {
        var datePickerEl = document.querySelector('[data-control="datepicker"]');
        if (datePickerEl && typeof flatpickr !== 'undefined') {
            // Check if already initialized
            if (datePickerEl._flatpickr) return;
            
            var container = document.querySelector('[data-control="booking"]');
            var options = {
                inline: true,
                static: true,
                dateFormat: 'Y-m-d',
                minDate: container ? container.dataset.minDate : 'today',
                maxDate: container ? container.dataset.maxDate : null,
                disable: [],
                onChange: function(selectedDates, dateStr) {
                    datePickerEl.value = dateStr;
                    // Trigger Livewire update
                    if (window.Livewire) {
                        var wireEl = datePickerEl.closest('[wire\\:id]');
                        if (wireEl) {
                            var wireId = wireEl.getAttribute('wire:id');
                            Livewire.find(wireId).set('date', dateStr);
                        }
                    }
                }
            };
            
            // Parse disabled dates
            if (container && container.dataset.disable) {
                try {
                    options.disable = JSON.parse(container.dataset.disable);
                } catch(e) {}
            }
            
            flatpickr(datePickerEl, options);
            console.log('Flatpickr initialized for reservation calendar');
        }
    }
    
    // Run on page load
    initReservationCalendar();
    
    // Also run after Livewire updates (for dynamic content)
    if (window.Livewire) {
        Livewire.hook('message.processed', function() {
            setTimeout(initReservationCalendar, 100);
        });
    }
});
</script>
</body>
</html>
