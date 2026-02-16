---
title: 'Premium Features'
layout: default
permalink: /premium-features
---
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold"><i class="fa fa-crown text-warning me-3"></i>Premium Features</h1>
        <p class="lead text-muted">Unlock the full potential of your food ordering experience</p>
    </div>
    
    <!-- Feature Cards -->
    <div class="row g-4">
        <!-- Group Orders -->
        <div class="col-md-6 col-lg-4" id="group-orders">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon bg-warning bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-users fa-2x"></i>
                    </div>
                    <h4 class="card-title">Group Orders</h4>
                    <p class="text-muted">Order together with friends, family, or colleagues. Split the bill easily and get everything delivered at once.</p>
                    <ul class="list-unstyled text-start small">
                        <li class="mb-2"><i class="fa fa-check text-success me-2"></i>Invite friends via link or QR code</li>
                        <li class="mb-2"><i class="fa fa-check text-success me-2"></i>Everyone adds their own items</li>
                        <li class="mb-2"><i class="fa fa-check text-success me-2"></i>Split payment automatically</li>
                        <li class="mb-2"><i class="fa fa-check text-success me-2"></i>One delivery, less fees</li>
                    </ul>
                    @if(Auth::isLogged())
                        <a href="{{ page_url('account.features') }}#group-orders" class="btn btn-warning">Start Group Order</a>
                    @else
                        <a href="{{ page_url('account.login') }}" class="btn btn-outline-warning">Login to Start</a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Scheduled Orders -->
        <div class="col-md-6 col-lg-4" id="scheduled-orders">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background-color: #FF4900;">
                        <i class="fa fa-clock fa-2x"></i>
                    </div>
                    <h4 class="card-title">Schedule Orders</h4>
                    <p class="text-muted">Plan your meals ahead. Schedule orders for later today, tomorrow, or any day of the week.</p>
                    <ul class="list-unstyled text-start small">
                        <li class="mb-2"><i class="fa fa-check me-2" style="color: #FF4900;"></i>Order up to 7 days in advance</li>
                        <li class="mb-2"><i class="fa fa-check me-2" style="color: #FF4900;"></i>Choose exact delivery time</li>
                        <li class="mb-2"><i class="fa fa-check me-2" style="color: #FF4900;"></i>Perfect for office lunches</li>
                        <li class="mb-2"><i class="fa fa-check me-2" style="color: #FF4900;"></i>Never miss a meal</li>
                    </ul>
                    @if(Auth::isLogged())
                        <a href="{{ page_url('account.features') }}#scheduled-orders" class="btn" style="background-color: #FF4900; border-color: #FF4900; color: white;">Schedule Now</a>
                    @else
                        <a href="{{ page_url('account.login') }}" class="btn" style="border-color: #FF4900; color: #FF4900;">Login to Schedule</a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Meal Plans / Subscriptions -->
        <div class="col-md-6 col-lg-4" id="subscriptions">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon bg-warning bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-calendar-check fa-2x"></i>
                    </div>
                    <h4 class="card-title">Meal Plans</h4>
                    <p class="text-muted">Subscribe to regular meal deliveries. Save money and never worry about what to eat.</p>
                    <ul class="list-unstyled text-start small">
                        <li class="mb-2"><i class="fa fa-check text-warning me-2"></i>Daily, weekly, or monthly plans</li>
                        <li class="mb-2"><i class="fa fa-check text-warning me-2"></i>Up to 20% savings</li>
                        <li class="mb-2"><i class="fa fa-check text-warning me-2"></i>Customize your menu</li>
                        <li class="mb-2"><i class="fa fa-check text-warning me-2"></i>Skip or pause anytime</li>
                    </ul>
                    @if(Auth::isLogged())
                        <a href="{{ page_url('account.features') }}#subscriptions" class="btn btn-warning">View Plans</a>
                    @else
                        <a href="{{ page_url('account.login') }}" class="btn btn-outline-warning">Login to Subscribe</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Track Order Section -->
    <div class="row mt-5" id="track-order">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body text-center p-5">
                    <i class="fa fa-motorcycle fa-3x mb-3" style="color: #FF4900;"></i>
                    <h3>Track Your Order</h3>
                    <p class="text-muted mb-4">Know exactly where your food is with real-time GPS tracking</p>
                    <a href="{{ page_url('track-order') }}" class="btn btn-lg" style="background-color: #FF4900; border-color: #FF4900; color: white;">
                        <i class="fa fa-map-marker-alt me-2"></i>Track Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    @unless(Auth::isLogged())
    <div class="text-center mt-5 py-5 bg-primary text-white rounded-3">
        <h2>Ready to get started?</h2>
        <p class="lead mb-4">Create an account to access all premium features</p>
        <a href="{{ page_url('account.register') }}" class="btn btn-light btn-lg me-2">
            <i class="fa fa-user-plus me-2"></i>Register Now
        </a>
        <a href="{{ page_url('account.login') }}" class="btn btn-outline-light btn-lg">
            <i class="fa fa-sign-in-alt me-2"></i>Login
        </a>
    </div>
    @endunless
</div>
