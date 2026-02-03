---
description: Default layout
---
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ App::getLocale() }}" class="h-100">
<head>
    @include('igniter-orange::includes.head')
    
    <!-- Uganda Custom Styles -->
    <link rel="stylesheet" href="/themes/demo/assets/css/uganda-custom.css">
    
    <!-- AI Chatbot Styles -->
    <link rel="stylesheet" href="/themes/demo/assets/css/chatbot.css">
    
    <!-- Flatpickr Calendar (for reservations) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        /* Header Fix - Compact Size */
        .header { background: linear-gradient(135deg, #FF4900 0%, #e04000 100%); }
        .header .navbar { padding: 0.5rem 0 !important; min-height: auto !important; background: transparent !important; }
        .header .navbar-brand .img-logo { max-height: 40px !important; }
        .header .nav-link { color: white !important; padding: 0.5rem 1rem !important; }
        .header .nav-link:hover { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .header .navbar-toggler-icon { filter: invert(1); }
        .header .dropdown-menu { background: white; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); }
        .header .dropdown-menu .dropdown-item { color: #333 !important; padding: 0.5rem 1rem; }
        .header .dropdown-menu .dropdown-item:hover { background: #f5f5f5; color: #FF4900 !important; }
        
        /* Dropdown Fix - CSS hover fallback */
        .header .nav-item.dropdown:hover > .dropdown-menu { display: block; margin-top: 0; }
        .header .dropdown-menu { margin-top: 0; }
        
        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed; bottom: 90px; right: 20px; z-index: 9999;
            width: 55px; height: 55px; background: #25D366; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 28px; box-shadow: 0 4px 15px rgba(37,211,102,0.4);
            transition: all 0.3s ease; text-decoration: none;
        }
        .whatsapp-float:hover { transform: scale(1.1); color: white; }
        
        /* Chatbot Float */
        .chatbot-float {
            position: fixed; bottom: 20px; right: 20px; z-index: 9999;
            width: 55px; height: 55px; background: #FF4900; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 24px; box-shadow: 0 4px 15px rgba(255,73,0,0.4);
            cursor: pointer; transition: all 0.3s ease;
        }
        .chatbot-float:hover { transform: scale(1.1); }
        
        /* Footer Styling */
        .footer { background: #1a1a1a; color: #ccc; }
        .footer h6 { color: white; }
        .footer a:hover { color: #FF4900 !important; }
    </style>
    
    @livewireStyles
</head>
<body class="d-flex flex-column h-100 {{ $this->page->bodyClass ?? '' }}">

<!-- HEADER -->
<header class="header">
    <nav class="navbar navbar-expand-md py-2">
        <div class="container">
            <a class="navbar-brand" href="{{ page_url('home') }}">
                <img class="img-logo" alt="TastyIgniter" src="{{ asset('vendor/igniter-orange/images/favicon.ico') }}" style="max-height: 40px;">
                <span class="ms-2 fw-bold text-white d-none d-sm-inline">TastyIgniter</span>
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
                    <!-- Premium Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-crown me-1"></i> Premium
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ page_url('account.features') }}#group-orders">
                                    <i class="fa fa-users me-2 text-primary"></i>Group Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ page_url('account.features') }}#scheduled-orders">
                                    <i class="fa fa-clock me-2 text-warning"></i>Schedule Order
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ page_url('account.features') }}#subscriptions">
                                    <i class="fa fa-calendar-check me-2 text-success"></i>Meal Plans
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ page_url('track-order') }}">
                                    <i class="fa fa-motorcycle me-2 text-info"></i>Track Order
                                </a>
                            </li>
                        </ul>
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
                                    <a class="dropdown-item text-danger" href="{{ page_url('account.logout') }}">
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
<footer class="footer mt-auto py-4">
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
                    <li class="mb-2"><a href="{{ url('/account/features#group-orders') }}" class="text-muted text-decoration-none"><i class="fa fa-users me-2"></i>Group Orders</a></li>
                    <li class="mb-2"><a href="{{ url('/account/features#scheduled-orders') }}" class="text-muted text-decoration-none"><i class="fa fa-clock me-2"></i>Schedule Order</a></li>
                    <li class="mb-2"><a href="{{ url('/account/features#subscriptions') }}" class="text-muted text-decoration-none"><i class="fa fa-calendar-check me-2"></i>Meal Plans</a></li>
                    <li class="mb-2"><a href="{{ url('/track-order') }}" class="text-muted text-decoration-none"><i class="fa fa-motorcycle me-2"></i>Track Order</a></li>
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

<!-- WhatsApp Float -->
<a href="https://wa.me/256779081600?text=Hi%20TastyIgniter!%20I%20would%20like%20to%20order%20food." class="whatsapp-float" target="_blank" title="Order via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Chatbot Float -->
<div class="chatbot-float" id="chatbotToggle" title="Chat with AI">
    <i class="fa fa-comments"></i>
</div>

<livewire:igniter-orange::utils.modal/>
<livewire:igniter-orange::utils.flash-message/>
@include('igniter-orange::includes.eucookiebanner')
@livewireScripts
@include('igniter-orange::includes.scripts')

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
    var datePickerEl = document.querySelector('[data-control="datepicker"]');
    if (datePickerEl && typeof flatpickr !== 'undefined') {
        var container = document.querySelector('[data-control="booking"]');
        var options = {
            inline: true,
            static: true,
            dateFormat: 'Y-m-d',
            minDate: container ? container.dataset.minDate : 'today',
            maxDate: container ? container.dataset.maxDate : null,
            disable: container && container.dataset.disable ? JSON.parse(container.dataset.disable) : [],
            onChange: function(selectedDates, dateStr) {
                datePickerEl.value = dateStr;
                datePickerEl.dispatchEvent(new Event('change'));
            }
        };
        flatpickr(datePickerEl, options);
    }
});
</script>
</body>
</html>
