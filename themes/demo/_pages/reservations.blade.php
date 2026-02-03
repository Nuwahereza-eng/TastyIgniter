---
title: 'Make a Reservation'
description: 'Book a table at one of our restaurants'
permalink: /reservations
layout: default
---
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="mb-3"><i class="fa fa-calendar-alt text-primary me-2"></i>Make a Reservation</h1>
        <p class="text-muted lead">Select a restaurant below to book your table</p>
    </div>
    
    <div class="row">
        @php
            $locations = \Igniter\Local\Models\Location::where('location_status', true)->get();
        @endphp
        
        @forelse($locations as $location)
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="/{{ $location->permalink_slug }}/reservation" class="card h-100 text-decoration-none shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            @if($location->thumb)
                                <img src="{{ $location->thumb->getThumb() }}" alt="{{ $location->location_name }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fa fa-store fa-2x text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1 text-dark">{{ $location->location_name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="fa fa-map-marker-alt me-1"></i>
                                    {{ $location->location_address }}
                                </p>
                                @if($location->location_telephone)
                                    <p class="text-muted small mb-0">
                                        <i class="fa fa-phone me-1"></i>
                                        {{ $location->location_telephone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-primary text-white text-center">
                        <i class="fa fa-calendar-check me-2"></i>Book a Table
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fa fa-info-circle me-2"></i>No restaurants available for reservations at this time.
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
}
.card {
    transition: all 0.2s ease;
}
</style>
