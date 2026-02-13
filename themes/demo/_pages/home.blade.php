---
title: igniter.orange::default.home_title
description: ''
permalink: /
layout: default
bodyClass: home-page

'[igniter-orange::local-search]': []
'[igniter-orange::featured-items]': []
'[igniter-orange::slider]':
    code: home-slider
    height: 60vh
    effect: fade
    delayInterval: 5000
    hideControls: false
    hideIndicators: false
    hideCaptions: true
---
{{-- Dynamic Hero Slider - Uses images from admin panel --}}
@php
    $slider = \Igniter\Frontend\Models\Slider::where('code', 'home-slider')->first();
    $slides = $slider ? $slider->images : collect();
@endphp

@if($slides->count() > 0)
<div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        @foreach($slides as $index => $slide)
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @php
            // Dynamic feature highlights for each slide
            $slideFeatures = [
                ['title' => 'Delicious Food, Delivered Fast', 'desc' => 'Order from the best restaurants near you', 'icon' => 'fa-utensils', 'link' => page_url('locations'), 'btn' => 'Order Now'],
                ['title' => 'Group Orders Made Easy', 'desc' => 'Order together with friends & split the bill', 'icon' => 'fa-users', 'link' => '/account/features#group-orders', 'btn' => 'Start Group Order'],
                ['title' => 'Schedule Your Meals', 'desc' => 'Pre-order for later today or any day this week', 'icon' => 'fa-calendar-alt', 'link' => '/account/features#scheduled-orders', 'btn' => 'Schedule Order'],
                ['title' => 'Meal Subscriptions', 'desc' => 'Save up to 20% with weekly meal plans', 'icon' => 'fa-box', 'link' => '/account/features#subscriptions', 'btn' => 'View Plans'],
                ['title' => 'Tasty Wallet', 'desc' => 'Pay faster & earn 5% cashback on every order', 'icon' => 'fa-wallet', 'link' => '/account/wallet', 'btn' => 'Top Up Wallet'],
            ];
        @endphp
        @foreach($slides as $index => $slide)
        @php
            $feature = $slideFeatures[$index % count($slideFeatures)];
            $title = $slide->getCustomProperty('title') ?: $feature['title'];
            $description = $slide->getCustomProperty('description') ?: $feature['desc'];
            $link = $slide->getCustomProperty('link') ?: $feature['link'];
            $buttonText = $slide->getCustomProperty('button_text') ?: $feature['btn'];
            $icon = $feature['icon'];
        @endphp
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="max-height: 60vh;">
            <div class="hero-slide position-relative" style="min-height: 400px; background: url('{{ $slide->getThumb(['width' => 1920, 'height' => 800]) }}') center/cover no-repeat;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(0,0,0,0.2)); pointer-events: none;"></div>
                <div class="position-relative text-center text-white p-4 d-flex align-items-center justify-content-center" style="min-height: 400px; z-index: 10;">
                    <div>
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(255,73,0,0.9); box-shadow: 0 4px 20px rgba(255,73,0,0.5);">
                                <i class="fa {{ $icon }} fa-2x"></i>
                            </span>
                        </div>
                        <h1 class="display-5 fw-bold mb-2" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">{{ $title }}</h1>
                        <p class="lead mb-4 px-3" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.6); max-width: 600px; margin: 0 auto;">{{ $description }}</p>
                        <a href="{{ $link }}" class="btn btn-lg px-5 fw-bold" style="background: linear-gradient(135deg, #FF4900, #ff6b35); border: none; color: white; box-shadow: 0 4px 15px rgba(255,73,0,0.4); text-decoration: none;">
                            <i class="fa {{ $icon }} me-2"></i>{{ $buttonText }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($slides->count() > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    @endif
</div>
@else
{{-- Fallback Static Slider when no images in admin --}}
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
                    <h1 class="display-4 fw-bold mb-3">Welcome to UgaEats</h1>
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
@endif

{{-- Find Restaurant Section --}}
<div class="find-restaurant-section" style="background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem 0;">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-8 py-4">
                {{-- Search Form - has its own heading --}}
                <livewire:igniter-orange::local-search/>
            </div>
        </div>
    </div>
</div>

<x-igniter-orange::featured-items/>
