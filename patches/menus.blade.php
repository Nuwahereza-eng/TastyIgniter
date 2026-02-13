---
title: igniter.orange::default.menus_title
permalink: '/:location?local/menus/:category?'
description: ''
layout: default
hideFooter: 1

'[igniter-orange::local-header]': []
'[igniter-orange::fulfillment]': []
'[igniter-orange::category-list]': []
'[igniter-orange::menu-item-list]':
    showThumb: true
    menuThumbWidth: 120
    menuThumbHeight: 120
    itemsPerPage: 20
'[igniter-orange::cart-box]': []
'[igniter-orange::fulfillment-modal]': []
---
{{-- Scheduled Order Banner --}}
<div id="scheduledOrderBanner" class="d-none">
    <div class="bg-success text-white py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-clock me-2"></i>
                    <strong>Scheduled Order:</strong>
                    <span id="scheduledOrderInfo">Loading...</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-light" onclick="clearScheduledOrder()">
                    <i class="fa fa-times me-1"></i>Cancel Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for scheduled order in localStorage
    const scheduledOrder = localStorage.getItem('ugaeats_scheduled_order');
    if (scheduledOrder) {
        try {
            const data = JSON.parse(scheduledOrder);
            const setAt = new Date(data.set_at);
            const now = new Date();
            
            // Only show if set within last 2 hours (still relevant)
            if ((now - setAt) < 2 * 60 * 60 * 1000) {
                const orderDate = new Date(data.order_date);
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                let dateStr = orderDate.toLocaleDateString('en-UG', { weekday: 'long', month: 'short', day: 'numeric' });
                if (orderDate.toDateString() === today.toDateString()) {
                    dateStr = 'Today';
                } else if (orderDate.toDateString() === tomorrow.toDateString()) {
                    dateStr = 'Tomorrow';
                }
                
                // Format time nicely
                const [hours, minutes] = data.order_time.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                const timeStr = `${displayHour}:${minutes} ${ampm}`;
                
                document.getElementById('scheduledOrderInfo').textContent = `${dateStr} at ${timeStr}`;
                document.getElementById('scheduledOrderBanner').classList.remove('d-none');
            } else {
                // Expired, remove it
                localStorage.removeItem('ugaeats_scheduled_order');
            }
        } catch (e) {
            console.error('Error parsing scheduled order:', e);
        }
    }
});

function clearScheduledOrder() {
    // Clear localStorage
    localStorage.removeItem('ugaeats_scheduled_order');
    
    // Call API to clear session schedule
    fetch('/ajax/location-schedule/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    }).then(() => {
        // Hide banner
        document.getElementById('scheduledOrderBanner').classList.add('d-none');
        // Reload to refresh fulfillment component
        window.location.reload();
    }).catch(() => {
        // Hide banner anyway
        document.getElementById('scheduledOrderBanner').classList.add('d-none');
    });
}
</script>

<div class="bg-white border-bottom border-1">
    <div class="container py-4">
        <div class="mb-3" wire:ignore>
            <a
                class="text-decoration-none"
                href="{{page_url('locations')}}"
            >
                <i class="fa fa-arrow-left-long"></i>&nbsp;&nbsp;
                @lang('igniter.orange::default.button_back')
            </a>
        </div>
        <div class="row align-items-start">
            <div class="col-lg-8">
                <x-igniter-orange::local-header/>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="d-flex justify-content-end">
                    <div class="local-control p-3 border rounded">
                        <div class="d-inline-block w-100 fw-bold text-sm-left text-md-center">
                            <x-igniter-orange::fulfillment/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sticky-top bg-white border-bottom border-1">
    <div class="container">
        <x-igniter-orange::category-list/>
    </div>
</div>
<div class="container pt-3 pb-5">
    <div class="row">
        <div class="col-lg-8">
            <livewire:igniter-orange::menu-item-list/>
        </div>

        <div class="col-lg-4 d-none d-lg-inline-block">
            <livewire:igniter-orange::cart-box/>
        </div>
    </div>
</div>
<livewire:igniter-orange::fulfillment-modal/>
