<div class="py-4">
    <div class="container">
        <div class="row">
            <!-- Footer Menu -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-title text-uppercase fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ page_url('home') }}" class="text-muted text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ page_url('locations') }}" class="text-muted text-decoration-none">Locations</a></li>
                    <li class="mb-2"><a href="{{ page_url('reservation.reservation') }}" class="text-muted text-decoration-none">Reservation</a></li>
                    <li class="mb-2"><a href="{{ page_url('contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Premium Features -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-title text-uppercase fw-bold mb-3">Premium Features</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ url('/account/features#group-orders') }}" class="text-muted text-decoration-none">
                            <i class="fa fa-users me-2"></i>Group Orders
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/account/features#scheduled-orders') }}" class="text-muted text-decoration-none">
                            <i class="fa fa-clock me-2"></i>Schedule Order
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/account/features#subscriptions') }}" class="text-muted text-decoration-none">
                            <i class="fa fa-calendar-check me-2"></i>Meal Plans
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/track-order') }}" class="text-muted text-decoration-none">
                            <i class="fa fa-motorcycle me-2"></i>Track Order
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Social Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-title text-uppercase fw-bold mb-3">Follow Us</h6>
                <div class="d-flex gap-3">
                    <a href="https://facebook.com/ugaeats" target="_blank" class="text-muted">
                        <img src="/themes/demo/assets/images/social/icons8-facebook-logo.svg" alt="Facebook" width="32" height="32">
                    </a>
                    <a href="https://x.com/ugaeats" target="_blank" class="text-muted">
                        <img src="/themes/demo/assets/images/social/icons8-x-logo-100.svg" alt="X" width="32" height="32">
                    </a>
                    <a href="https://tiktok.com/@ugaeats" target="_blank" class="text-muted">
                        <img src="/themes/demo/assets/images/social/icons8-tiktok-logo.svg" alt="TikTok" width="32" height="32">
                    </a>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-title text-uppercase fw-bold mb-3">Newsletter</h6>
                <p class="text-muted small">Subscribe for updates and offers</p>
                <livewire:igniter-orange::newsletter-subscribe-form />
            </div>
        </div>
    </div>

    <div class="container">
        <hr class="my-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted small">&copy; {{ date('Y') }} UgaEats. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted small">Powered by TastyIgniter</p>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/256779081600?text=Hi%20UgaEats!%20I%20would%20like%20to%20order%20food." 
   class="whatsapp-float" 
   target="_blank" 
   title="Order via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- AI Chatbot Button -->
<div class="chatbot-float" id="chatbotToggle" title="Chat with AI">
    <i class="fa fa-comments"></i>
</div>
