<div class="py-5 uganda-footer">
    <div class="container">
        <div class="row">
            <!-- Restaurant Info -->
            <div class="col-lg-3 col-md-6 mt-4 mt-lg-0">
                <h6 class="footer-title">RESTAURANT</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/cafe-javas/menus') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-utensils me-2"></i>View Menu
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/cafe-javas/reservation') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-calendar me-2"></i>Reservation
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/locations') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-map-marker-alt me-2"></i>Our Locations
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Premium Features Quick Links -->
            <div class="col-lg-3 col-md-6 mt-4 mt-lg-0">
                <h6 class="footer-title">
                    <i class="fa fa-crown text-warning me-1"></i>PREMIUM FEATURES
                </h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/account/features#group-orders') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-users me-2"></i>Group Orders
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/account/features#scheduled-orders') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-clock me-2"></i>Schedule Order
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/account/features#subscriptions') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-calendar-check me-2"></i>Meal Plans
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/track-order') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-motorcycle me-2"></i>Track Order
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/account/features#loyalty') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-star me-2"></i>Loyalty Points
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Information -->
            <div class="col-lg-2 col-md-6 mt-4 mt-lg-0">
                <h6 class="footer-title">INFORMATION</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/contact') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-envelope me-2"></i>Contact Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/about-us') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-info-circle me-2"></i>About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/about-us') }}" class="text-decoration-none text-muted">
                            <i class="fa fa-shield-alt me-2"></i>Privacy Policy
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Social & Newsletter -->
            <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                <div class="social-bottom mb-4">
                    <h6 class="footer-title">FOLLOW US ON</h6>
                    <div class="social-icons d-flex gap-3 mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/256779081600" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div id="newsletter-box">
                    <h5 class="mb-3">Subscribe to our newsletter</h5>
                    <form class="d-flex gap-2">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="my-4">
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} UgaEats - Powered by TastyIgniter</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="payment-icons">
                    <span class="me-2">We accept:</span>
                    <i class="fab fa-cc-visa fa-lg me-2"></i>
                    <i class="fab fa-cc-mastercard fa-lg me-2"></i>
                    <span class="badge bg-success">MTN MoMo</span>
                    <span class="badge bg-danger ms-1">Airtel Money</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Floating Button -->
<div class="whatsapp-float" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999;">
    <a href="https://wa.me/256779081600?text=Hi%20UgaEats!%20I%20would%20like%20to%20order%20food." 
       class="whatsapp-btn d-flex align-items-center justify-content-center" 
       target="_blank" 
       title="Order via WhatsApp"
       style="width: 60px; height: 60px; background: #25D366; border-radius: 50%; color: white; font-size: 30px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); transition: all 0.3s ease;">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
