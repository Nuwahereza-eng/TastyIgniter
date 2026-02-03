---
title: 'Make a Reservation'
description: 'Book a table at one of our restaurants'
permalink: /reservations
layout: default

'[igniter-orange::location-list]': []
---
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="mb-3"><i class="fa fa-calendar-alt text-primary me-2"></i>Make a Reservation</h1>
        <p class="text-muted lead">Select a restaurant below to book your table</p>
    </div>
    
    <livewire:igniter-orange::location-list />
</div>

<style>
/* Override location card click behavior for reservations */
.location-card {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept location card clicks to redirect to reservation page
    document.querySelectorAll('[data-location-slug]').forEach(function(card) {
        card.addEventListener('click', function(e) {
            var slug = this.getAttribute('data-location-slug');
            if (slug) {
                e.preventDefault();
                window.location.href = '/' + slug + '/reservation';
            }
        });
    });
});
</script>
