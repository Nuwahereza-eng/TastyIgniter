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
{{-- Group Order Banner --}}
<div id="groupOrderBanner" class="d-none">
    <div class="bg-warning text-dark py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <i class="fa fa-users me-2"></i>
                    <strong>Group Order:</strong>
                    <span id="groupOrderInfo">Adding items to group order</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="finishAddingItems()">
                        <i class="fa fa-check me-1"></i>Done Adding
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="exitGroupOrder()">
                        <i class="fa fa-times me-1"></i>Exit Group
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
    // Check for active group order
    checkGroupOrder();
    
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

// ============ GROUP ORDER FUNCTIONS ============
function checkGroupOrder() {
    const activeGroup = localStorage.getItem('ugaeats_active_group_order');
    if (!activeGroup) return;
    
    try {
        const groupData = JSON.parse(activeGroup);
        
        // Check if still valid (within 4 hours)
        if (Date.now() - groupData.timestamp > 4 * 60 * 60 * 1000) {
            localStorage.removeItem('ugaeats_active_group_order');
            return;
        }
        
        // Show group order banner
        document.getElementById('groupOrderInfo').textContent = 'Adding items to group order';
        document.getElementById('groupOrderBanner').classList.remove('d-none');
        
        // Also check URL param
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('group_order')) {
            // Store in localStorage if not already there
            if (!activeGroup) {
                localStorage.setItem('ugaeats_active_group_order', JSON.stringify({
                    id: urlParams.get('group_order'),
                    return_url: '/account/features#group-orders',
                    timestamp: Date.now()
                }));
            }
        }
    } catch (e) {
        console.error('Error checking group order:', e);
    }
}

async function finishAddingItems() {
    const activeGroup = localStorage.getItem('ugaeats_active_group_order');
    if (!activeGroup) {
        alert('No active group order found');
        return;
    }
    
    const groupData = JSON.parse(activeGroup);
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing...';
    
    try {
        // Sync cart to group order
        const response = await fetch('/ajax/group-orders/sync-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            credentials: 'include'
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message
            alert(`Items synced! ${result.items_count || 0} items added to group order (UGX ${(result.subtotal || 0).toLocaleString()})`);
            
            // Redirect back to group order
            const returnUrl = groupData.return_url || '/account/features#group-orders';
            
            // Clear local storage
            localStorage.removeItem('ugaeats_active_group_order');
            
            window.location.href = returnUrl;
        } else {
            throw new Error(result.error || 'Failed to sync cart');
        }
    } catch (error) {
        console.error('Error syncing cart:', error);
        alert('Failed to sync items: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check me-1"></i>Done Adding';
    }
}

function exitGroupOrder() {
    if (!confirm('Are you sure you want to exit the group order? Your items will not be added to the group.')) {
        return;
    }
    
    // Clear localStorage
    localStorage.removeItem('ugaeats_active_group_order');
    
    // Call API to clear active group order
    fetch('/ajax/group-orders/clear-active', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    }).finally(() => {
        // Hide banner
        document.getElementById('groupOrderBanner').classList.add('d-none');
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
        {{-- Category navigation placed here below the location card --}}
        <div class="mt-3">
            <x-igniter-orange::category-list/>
        </div>
    </div>
</div>
<div class="container pt-3 pb-5">
    <div class="row">
        <div class="col-lg-8">
            <livewire:igniter-orange::menu-item-list/>
        </div>

        <div class="col-lg-4 d-none d-lg-inline-block">
            <div style="position: sticky; top: 80px;">
                <livewire:igniter-orange::cart-box/>
            </div>
        </div>
    </div>
</div>
<livewire:igniter-orange::fulfillment-modal/>
