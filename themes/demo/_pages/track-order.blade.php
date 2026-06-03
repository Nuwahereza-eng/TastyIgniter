---
title: 'Track Your Order'
layout: default
permalink: /track-order
---
<!-- Features CSS -->
<link rel="stylesheet" href="{{ asset('themes/demo/assets/css/features.css') }}">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-6"><i class="fa fa-motorcycle text-primary me-3"></i>Track Your Order</h1>
                <p class="text-muted lead">Enter your order number to see real-time delivery status</p>
            </div>
            
            <!-- Order Search -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Order Number</label>
                            <input type="text" class="form-control form-control-lg" id="trackingOrderId" 
                                   placeholder="e.g., UGA-00042, 42, or your order hash" autofocus>
                        </div>
                        <div class="col-md-4 d-flex align-items-end mt-3 mt-md-0">
                            <button class="btn btn-primary btn-lg w-100" onclick="trackOrderPublic()">
                                <i class="fa fa-search me-2"></i>Track Order
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fa fa-info-circle me-1"></i>
                            Your order number was sent via SMS and email when you placed your order
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- No Order Found -->
            <div id="noOrderFound" class="d-none">
                <div class="card shadow-sm border-warning">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-search fa-4x text-warning mb-4"></i>
                        <h4 class="text-warning">Order Not Found</h4>
                        <p class="error-message text-muted mb-4">We couldn't find an order with that number. Please check and try again.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-primary" onclick="resetTracking()">
                                <i class="fa fa-redo me-2"></i>Try Again
                            </button>
                            <a href="{{ url('/account/orders') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-list me-2"></i>My Orders
                            </a>
                        </div>
                        <hr class="my-4">
                        <p class="text-muted mb-0 small">
                            <i class="fa fa-info-circle me-1"></i>
                            Can't find your order? <a href="{{ url('/contact') }}">Contact Support</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Tracking Result -->
            <div id="trackingResult" class="d-none">
                <!-- Order Status Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fa fa-receipt me-2"></i>Order <span id="displayOrderId">UGA-00042</span></h5>
                            <span class="badge bg-warning text-dark" id="orderStatusBadge">Out for Delivery</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <!-- Map Placeholder -->
                                <div class="tracking-map-public" id="orderMap">
                                    <div class="map-placeholder">
                                        <div class="map-route">
                                            <div class="map-point restaurant">
                                                <i class="fa fa-store"></i>
                                                <span>Restaurant</span>
                                            </div>
                                            <div class="map-route-line"></div>
                                            <div class="map-point rider active">
                                                <i class="fa fa-motorcycle"></i>
                                                <span>Rider</span>
                                            </div>
                                            <div class="map-route-line pending"></div>
                                            <div class="map-point destination">
                                                <i class="fa fa-home"></i>
                                                <span>You</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- ETA Banner -->
                                <div class="eta-banner mt-3">
                                    <div class="eta-time">
                                        <i class="fa fa-clock me-2"></i>
                                        Estimated Arrival: <strong id="etaTime">15 mins</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <!-- Order Timeline -->
                                <div class="tracking-timeline-public">
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker"><i class="fa fa-check"></i></div>
                                        <div class="timeline-content">
                                            <h6>Order Placed</h6>
                                            <small class="text-muted" id="orderPlacedTime">2:30 PM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker"><i class="fa fa-check"></i></div>
                                        <div class="timeline-content">
                                            <h6>Restaurant Confirmed</h6>
                                            <small class="text-muted" id="orderConfirmedTime">2:32 PM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker"><i class="fa fa-check"></i></div>
                                        <div class="timeline-content">
                                            <h6>Preparing Food</h6>
                                            <small class="text-muted" id="orderPreparingTime">2:35 PM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item active">
                                        <div class="timeline-marker pulse"><i class="fa fa-motorcycle"></i></div>
                                        <div class="timeline-content">
                                            <h6>Out for Delivery</h6>
                                            <small class="text-muted" id="orderDeliveryTime">2:55 PM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item pending">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Delivered</h6>
                                            <small class="text-muted" id="orderDeliveredTime">--</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rider & Order Info -->
                <div class="row">
                    <!-- Rider Card -->
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fa fa-user me-2"></i>Your Delivery Rider</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rider-avatar-lg">
                                        <img src="https://ui-avatars.com/api/?name=Dennis+K&background=ff6600&color=fff&size=80" 
                                             alt="Rider" class="rounded-circle">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h5 class="mb-1" id="riderName">Dennis K.</h5>
                                        <div class="mb-1">
                                            <i class="fa fa-star text-warning"></i>
                                            <span id="riderRating">4.9</span>
                                            <span class="text-muted ms-1" id="riderDeliveries">(2,340 deliveries)</span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa fa-motorcycle me-1"></i>Honda Boda • <span id="riderPlate">UBD 123A</span>
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <a href="tel:+256779081600" class="btn btn-outline-primary flex-grow-1">
                                        <i class="fa fa-phone me-2"></i>Call Rider
                                    </a>
                                    <a href="https://wa.me/256779081600?text=Hi,%20I'm%20tracking%20my%20order" 
                                       class="btn btn-success flex-grow-1" target="_blank">
                                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Details Card -->
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fa fa-shopping-bag me-2"></i>Order Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="order-detail-item">
                                    <span class="order-detail-label"><i class="fa fa-store text-muted me-2"></i>Restaurant</span>
                                    <span class="order-detail-value" id="trackRestaurant">Café Javas - Oasis Mall</span>
                                </div>
                                <div class="order-detail-item">
                                    <span class="order-detail-label"><i class="fa fa-utensils text-muted me-2"></i>Items</span>
                                    <span class="order-detail-value" id="trackItems">2x Chicken Burger, 1x Fries, 2x Soda</span>
                                </div>
                                <div class="order-detail-item">
                                    <span class="order-detail-label"><i class="fa fa-map-marker-alt text-muted me-2"></i>Delivery</span>
                                    <span class="order-detail-value" id="trackAddress">Kyebando Road, Kampala</span>
                                </div>
                                <hr>
                                <div class="order-detail-item total">
                                    <span class="order-detail-label"><strong>Total Paid</strong></span>
                                    <span class="order-detail-value"><strong id="trackTotal">UGX 65,000</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Help Section -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body text-center py-4">
                        <h6>Need Help?</h6>
                        <p class="text-muted mb-3">Contact our customer support team</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="tel:+256779081600" class="btn btn-outline-primary">
                                <i class="fa fa-phone me-2"></i>Call Support
                            </a>
                            <a href="https://wa.me/256779081600?text=Hi,%20I%20need%20help%20with%20my%20order" 
                               class="btn btn-success" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features Promo -->
            <div class="card bg-light mt-5">
                <div class="card-body text-center py-4">
                    <h5><i class="fa fa-star text-warning me-2"></i>Discover More Features</h5>
                    <p class="text-muted mb-3">Group ordering, scheduled deliveries, and subscription meal plans!</p>
                    @if(Auth::check())
                        <a href="{{ page_url('account.features') }}" class="btn btn-primary">
                            <i class="fa fa-rocket me-2"></i>Explore Features
                        </a>
                    @else
                        <a href="{{ page_url('account.login') }}" class="btn btn-primary">
                            <i class="fa fa-sign-in-alt me-2"></i>Login to Explore
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tracking-map-public {
    height: 250px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.map-placeholder {
    width: 100%;
    padding: 2rem;
}

.map-route {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.map-point {
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 2;
}

.map-point i {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.map-point.restaurant i {
    background: #28a745;
    color: white;
}

.map-point.rider i {
    background: #ff6600;
    color: white;
    animation: pulse 2s infinite;
}

.map-point.rider.active i {
    box-shadow: 0 0 0 15px rgba(255, 102, 0, 0.2);
}

.map-point.destination i {
    background: #6c757d;
    color: white;
}

.map-point span {
    font-size: 0.85rem;
    color: #495057;
    font-weight: 500;
}

.map-route-line {
    flex: 1;
    height: 4px;
    background: #28a745;
    position: relative;
}

.map-route-line.pending {
    background: #dee2e6;
    background: repeating-linear-gradient(
        90deg,
        #dee2e6,
        #dee2e6 10px,
        transparent 10px,
        transparent 15px
    );
}

.eta-banner {
    background: linear-gradient(135deg, #ff6600 0%, #ff8533 100%);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    text-align: center;
}

.eta-time {
    font-size: 1.1rem;
}

.tracking-timeline-public {
    position: relative;
}

.tracking-timeline-public .timeline-item {
    display: flex;
    align-items: flex-start;
    padding-bottom: 1.5rem;
    position: relative;
}

.tracking-timeline-public .timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 35px;
    width: 2px;
    height: calc(100% - 35px);
    background: #dee2e6;
}

.tracking-timeline-public .timeline-item.completed:not(:last-child)::after {
    background: #28a745;
}

.tracking-timeline-public .timeline-item.active:not(:last-child)::after {
    background: linear-gradient(to bottom, #28a745 50%, #dee2e6 50%);
}

.tracking-timeline-public .timeline-marker {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 0.75rem;
}

.tracking-timeline-public .timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
}

.tracking-timeline-public .timeline-item.active .timeline-marker {
    background: #ff6600;
    color: white;
}

.tracking-timeline-public .timeline-marker.pulse {
    animation: pulse 2s infinite;
}

.tracking-timeline-public .timeline-content h6 {
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.tracking-timeline-public .timeline-item.pending .timeline-content {
    opacity: 0.5;
}

.rider-avatar-lg img {
    width: 70px;
    height: 70px;
    object-fit: cover;
}

.order-detail-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f1f1;
}

.order-detail-item:last-child {
    border-bottom: none;
}

.order-detail-item.total {
    font-size: 1.1rem;
    color: #ff6600;
}

.order-detail-label {
    color: #6c757d;
}

.order-detail-value {
    text-align: right;
    max-width: 60%;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@media (max-width: 768px) {
    .map-point i {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .tracking-map-public {
        height: 200px;
    }
}
</style>

<script>
// API configuration - Version 4
console.log('Track Order Script Loaded - v4');
const API_BASE = '/ajax';
console.log('API_BASE:', API_BASE);
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Parse the order number entered by the customer. The TastyIgniter checkout
// surfaces three things customers might paste in:
//   1. The bare numeric `order_id` (e.g. 42)
//   2. The display form `UGA-00042` (zero-padded, what our controller emits)
//   3. A multi-segment label like `UGA-2026-00042` (legacy/example format)
//   4. The order `hash` (long alphanumeric, used by /checkout/success/:hash)
// We return a payload ready to POST to /ajax/tracking/track which accepts
// either `order_id` or `order_hash`.
function parseOrderInput(raw) {
    if (!raw) return null;
    const trimmed = raw.trim();
    const upper = trimmed.toUpperCase();

    if (upper.startsWith('UGA')) {
        const groups = upper.match(/\d+/g);
        if (groups && groups.length) {
            const num = parseInt(groups[groups.length - 1], 10);
            if (!isNaN(num) && num > 0) return { order_id: num };
        }
    }

    if (/^\d+$/.test(trimmed)) {
        return { order_id: parseInt(trimmed, 10) };
    }

    if (/^[A-Za-z0-9_-]{8,}$/.test(trimmed)) {
        return { order_hash: trimmed };
    }

    return null;
}

// Legacy compatibility shim used by other helpers below.
function parseOrderId(orderId) {
    const parsed = parseOrderInput(orderId);
    return parsed && parsed.order_id ? parsed.order_id : null;
}

// Format order ID to UGA-XXXXX format
function formatOrderId(orderId) {
    const num = parseInt(orderId, 10);
    if (isNaN(num)) return orderId;
    return 'UGA-' + String(num).padStart(5, '0');
}

// API helper function
async function apiCall(endpoint, method = 'GET', data = null) {
    const fullUrl = `${API_BASE}${endpoint}`;
    console.log('apiCall - Full URL:', fullUrl, 'Method:', method, 'Data:', data);
    
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        credentials: 'same-origin',
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    const response = await fetch(fullUrl, options);
    const jsonResponse = await response.json();
    console.log('apiCall - Response:', jsonResponse);
    return jsonResponse;
}

async function trackOrderPublic() {
    const rawOrderId = document.getElementById('trackingOrderId').value.trim();
    
    if (!rawOrderId) {
        alert('Please enter an order number');
        document.getElementById('trackingOrderId').focus();
        return;
    }
    
    const payload = parseOrderInput(rawOrderId);
    
    if (!payload) {
        alert('Invalid order number. Enter the number shown on your receipt (e.g. UGA-00042, 42, or the order hash from your confirmation email).');
        document.getElementById('trackingOrderId').focus();
        return;
    }
    
    // Hide any previous results
    document.getElementById('noOrderFound').classList.add('d-none');
    document.getElementById('trackingResult').classList.add('d-none');
    
    // Show loading state
    const btn = document.querySelector('button[onclick="trackOrderPublic()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Searching...';
    btn.disabled = true;
    
    try {
        const fullUrl = API_BASE + '/tracking/track';
        console.log('Making API call to:', fullUrl, 'with payload:', payload);
        const response = await apiCall('/tracking/track', 'POST', payload);
        
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        console.log('API Response:', response);
        if (response.success && response.tracking) {
            const displayId = (response.order && response.order.formatted_id)
                || (payload.order_id ? formatOrderId(payload.order_id) : rawOrderId);
            console.log('Calling showOrderTrackingReal with:', {
                orderId: displayId,
                tracking: response.tracking,
                order: response.order,
                location: response.location
            });
            showOrderTrackingReal(displayId, response.tracking, response.order, response.location);
        } else if (response.success === false && response.error) {
            showNoOrderFound(response.error);
        } else {
            showNoOrderFound('Order not found. Please check your order number.');
        }
    } catch (error) {
        console.error('Tracking error:', error);
        btn.innerHTML = originalText;
        btn.disabled = false;
        showNoOrderFound('Unable to track order. Please try again.');
    }
}

function showOrderTrackingReal(orderId, tracking, order, location) {
    console.log('showOrderTrackingReal called with:', { orderId, tracking, order, location });
    document.getElementById('trackingResult').classList.remove('d-none');
    
    // Update Order ID
    console.log('Setting displayOrderId to:', order?.formatted_id || orderId.toUpperCase());
    document.getElementById('displayOrderId').textContent = order?.formatted_id || orderId.toUpperCase();
    
    // Update status badge
    const statusBadge = document.getElementById('orderStatusBadge');
    statusBadge.textContent = tracking.status_label || order?.status_name || 'Processing';
    statusBadge.className = 'badge ' + (tracking.is_delivered ? 'bg-success' : 'bg-warning text-dark');
    
    // Update ETA
    if (tracking.eta_minutes) {
        document.getElementById('etaTime').textContent = tracking.eta_minutes + ' mins';
    } else if (tracking.is_delivered) {
        document.getElementById('etaTime').textContent = 'Delivered!';
    } else {
        document.getElementById('etaTime').textContent = '30-45 mins';
    }
    
    // Update Order Details
    if (order) {
        console.log('Updating order details from:', order);
        // Restaurant name
        const restaurantEl = document.getElementById('trackRestaurant');
        console.log('trackRestaurant element:', restaurantEl, 'location:', location);
        if (restaurantEl && location) {
            restaurantEl.textContent = location.name || 'Restaurant';
            console.log('Set restaurant to:', location.name);
        }
        
        // Order items
        const itemsEl = document.getElementById('trackItems');
        console.log('trackItems element:', itemsEl, 'items_summary:', order.items_summary);
        if (itemsEl) {
            itemsEl.textContent = order.items_summary || 'Order items';
            console.log('Set items to:', order.items_summary);
        }
        
        // Delivery address
        const addressEl = document.getElementById('trackAddress');
        console.log('trackAddress element:', addressEl, 'delivery_address:', order.delivery_address);
        if (addressEl) {
            addressEl.textContent = order.delivery_address || 'Delivery address';
            console.log('Set address to:', order.delivery_address);
        }
        
        // Total
        const totalEl = document.getElementById('trackTotal');
        console.log('trackTotal element:', totalEl, 'total_formatted:', order.total_formatted);
        if (totalEl) {
            totalEl.textContent = order.total_formatted || 'UGX ' + (order.total || 0).toLocaleString();
            console.log('Set total to:', order.total_formatted);
        }
        
        // Order time
        const placedTimeEl = document.getElementById('orderPlacedTime');
        if (placedTimeEl && order.order_time) {
            placedTimeEl.textContent = order.order_time;
        }
    }
    
    // Update rider info if available
    if (tracking.rider) {
        document.getElementById('riderName').textContent = tracking.rider.name || 'Rider Assigned';
        document.getElementById('riderRating').textContent = tracking.rider.rating || '4.8';
        document.getElementById('riderDeliveries').textContent = '(' + (tracking.rider.total_deliveries || 0) + ' deliveries)';
        
        // Update call/WhatsApp links
        if (tracking.rider.phone) {
            const callBtn = document.querySelector('a[href^="tel:"]');
            if (callBtn) callBtn.href = 'tel:' + tracking.rider.phone;
            
            const waBtn = document.querySelector('a[href^="https://wa.me"]');
            if (waBtn) {
                const phone = tracking.rider.phone.replace(/[^0-9]/g, '');
                waBtn.href = 'https://wa.me/' + phone + '?text=Hi,%20I\'m%20tracking%20my%20order%20' + (order?.formatted_id || orderId);
            }
        }
    } else {
        // No rider assigned yet
        document.getElementById('riderName').textContent = 'Awaiting Assignment';
        document.getElementById('riderRating').textContent = '--';
        document.getElementById('riderDeliveries').textContent = '';
    }
    
    // Update timeline based on status
    updateTimelineFromStatus(tracking.status);
    
    // Scroll to results
    document.getElementById('trackingResult').scrollIntoView({ behavior: 'smooth' });
    
    // Start live updates (poll every 30 seconds)
    startLiveUpdates(order?.id);
}

function updateTimelineFromStatus(status) {
    const statuses = ['confirmed', 'preparing', 'ready', 'picked_up', 'on_the_way', 'nearby', 'delivered'];
    const currentIndex = statuses.indexOf(status);
    
    const timelineItems = document.querySelectorAll('.tracking-timeline-public .timeline-item');
    timelineItems.forEach((item, index) => {
        item.classList.remove('completed', 'active', 'pending');
        
        if (index < currentIndex) {
            item.classList.add('completed');
        } else if (index === currentIndex) {
            item.classList.add('active');
        } else {
            item.classList.add('pending');
        }
    });
}

function showNoOrderFound(message = 'Order not found. Please check your order number.') {
    const noOrderEl = document.getElementById('noOrderFound');
    noOrderEl.classList.remove('d-none');
    
    // Update the message if there's a message container
    const msgEl = noOrderEl.querySelector('.error-message');
    if (msgEl) {
        msgEl.textContent = message;
    }
    
    noOrderEl.scrollIntoView({ behavior: 'smooth' });
}

function resetTracking() {
    document.getElementById('noOrderFound').classList.add('d-none');
    document.getElementById('trackingResult').classList.add('d-none');
    document.getElementById('trackingOrderId').value = '';
    document.getElementById('trackingOrderId').focus();
    if (updateInterval) clearInterval(updateInterval);
}

let updateInterval;
let currentOrderId = null;

function startLiveUpdates(orderId) {
    // Clear any existing interval
    if (updateInterval) clearInterval(updateInterval);
    
    currentOrderId = orderId;
    
    // Poll for updates every 30 seconds
    updateInterval = setInterval(async () => {
        if (!currentOrderId) return;
        
        try {
            const response = await apiCall('/tracking/track', 'POST', {
                order_id: currentOrderId,
            });
            
            if (response.success && response.tracking) {
                // Update ETA
                if (response.tracking.eta_minutes) {
                    document.getElementById('etaTime').textContent = response.tracking.eta_minutes + ' mins';
                }
                
                // Update status
                const statusBadge = document.getElementById('orderStatusBadge');
                statusBadge.textContent = response.tracking.status_label;
                
                if (response.tracking.is_delivered) {
                    statusBadge.className = 'badge bg-success';
                    document.getElementById('etaTime').textContent = 'Delivered!';
                    clearInterval(updateInterval);
                    markAsDelivered();
                }
                
                // Update timeline
                updateTimelineFromStatus(response.tracking.status);
                
                // Update rider info if changed
                if (response.tracking.rider) {
                    document.getElementById('riderName').textContent = response.tracking.rider.name || 'Rider Assigned';
                }
            }
        } catch (error) {
            console.log('Live update failed, will retry');
        }
    }, 30000); // Update every 30 seconds
}

function markAsDelivered() {
    document.getElementById('orderStatusBadge').textContent = 'Delivered';
    document.getElementById('orderStatusBadge').className = 'badge bg-success';
    document.getElementById('etaTime').textContent = 'Delivered!';
    
    // Update timeline
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach(item => {
        item.classList.remove('pending', 'active');
        item.classList.add('completed');
    });
    
    const deliveredTimeEl = document.getElementById('orderDeliveredTime');
    if (deliveredTimeEl) {
        deliveredTimeEl.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
}

// Handle Enter key in search box and URL parameter
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('trackingOrderId').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            trackOrderPublic();
        }
    });
    
    // Check for order ID in URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const orderFromUrl = urlParams.get('order');
    if (orderFromUrl) {
        document.getElementById('trackingOrderId').value = orderFromUrl;
        // Auto-track after a short delay
        setTimeout(() => trackOrderPublic(), 500);
    }
});
</script>
