---
title: igniter.orange::default.home_title
description: ''
permalink: /
layout: default
bodyClass: home-page

'[igniter-orange::local-search]': []
'[igniter-orange::featured-items]': []
---
{{-- Static Hero Slider - No database dependency --}}
<div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="max-height: 60vh;">
            <div class="hero-slide" style="background: linear-gradient(135deg, #FF4900 0%, #ff6b35 100%); min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center text-white p-4">
                    <h1 class="display-4 fw-bold mb-3">Welcome to TastyIgniter</h1>
                    <p class="lead mb-4">Delicious food delivered to your doorstep</p>
                    <a href="{{ page_url('locations') }}" class="btn btn-light btn-lg px-5">Order Now</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="max-height: 60vh;">
            <div class="hero-slide" style="background: linear-gradient(135deg, #e04000 0%, #FF4900 100%); min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center text-white p-4">
                    <h1 class="display-4 fw-bold mb-3">Fresh & Fast</h1>
                    <p class="lead mb-4">From the best restaurants in Uganda</p>
                    <a href="{{ page_url('locations') }}" class="btn btn-light btn-lg px-5">View Menu</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="max-height: 60vh;">
            <div class="hero-slide" style="background: linear-gradient(135deg, #ff6b35 0%, #e04000 100%); min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center text-white p-4">
                    <h1 class="display-4 fw-bold mb-3">Group Orders</h1>
                    <p class="lead mb-4">Perfect for office lunch & events</p>
                    <a href="{{ url('/account/features#group-orders') }}" class="btn btn-light btn-lg px-5">Learn More</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

{{-- Find Restaurant Section --}}
<div class="border-bottom" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-8 py-5">
                {{-- Search Form - has its own heading --}}
                <livewire:igniter-orange::local-search/>
            </div>
        </div>
    </div>
</div>

<x-igniter-orange::featured-items/>
