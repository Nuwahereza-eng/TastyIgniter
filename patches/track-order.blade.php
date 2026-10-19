---
title: 'Track Your Order'
layout: default
permalink: /track-order
---
<!-- Features CSS -->
<link rel="stylesheet" href="{{ asset('themes/demo/assets/css/features.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

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
                                <!-- Live map: restaurant, rider and delivery point -->
                                <div class="tracking-map-public" id="orderMap"></div>
                                <div class="small text-muted mt-2"><span id="mapNotice"></span> <span id="routeInfo"></span></div>

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
            
            <!-- No Order Found -->
            <div id="noOrderFound" class="d-none">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-search fa-4x text-muted mb-4"></i>
                        <h4>Order Not Found</h4>
                        <p class="text-muted mb-4">We couldn't find an order with that number. Please check and try again.</p>
                        <button class="btn btn-primary" onclick="resetTracking()">
                            <i class="fa fa-redo me-2"></i>Try Again
                        </button>
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
    height: 320px;
    background: #e9ecef;
    border-radius: 1rem;
    position: relative;
    overflow: hidden;
    z-index: 0;
}

.map-pin {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
}

.map-pin.restaurant { background: #ff4900; }
.map-pin.rider { background: #198754; animation: pulse 2s infinite; }
.map-pin.destination { background: #0d6efd; }

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
    background: #ff4900;
    color: white;
}

.map-point.rider i {
    background: #ff4900;
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
    background: #ff4900;
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
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
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
    background: #ff4900;
}

.tracking-timeline-public .timeline-item.active:not(:last-child)::after {
    background: linear-gradient(to bottom, #ff4900 50%, #dee2e6 50%);
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
    background: #ff4900;
    color: white;
}

.tracking-timeline-public .timeline-item.active .timeline-marker {
    background: #ff4900;
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
    border-bottom: 1px solid #f8f9fa;
}

.order-detail-item:last-child {
    border-bottom: none;
}

.order-detail-item.total {
    font-size: 1.1rem;
    color: #ff4900;
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
        height: 260px;
    }
}
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// API configuration
const API_BASE = '/ajax';
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

    // UGA-... — take the LAST numeric run (handles UGA-00042 and UGA-2026-00042)
    if (upper.startsWith('UGA')) {
        const groups = upper.match(/\d+/g);
        if (groups && groups.length) {
            const num = parseInt(groups[groups.length - 1], 10);
            if (!isNaN(num) && num > 0) return { order_id: num };
        }
    }

    // Bare numeric → order_id
    if (/^\d+$/.test(trimmed)) {
        return { order_id: parseInt(trimmed, 10) };
    }

    // Anything else → treat as the order hash and let the server look it up
    if (/^[A-Za-z0-9_-]{8,}$/.test(trimmed)) {
        return { order_hash: trimmed };
    }

    return null;
}

// Legacy compatibility: callers that only wanted the numeric id.
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
    
    const response = await fetch(`${API_BASE}${endpoint}`, options);
    return await response.json();
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
        const response = await apiCall('/tracking/track', 'POST', payload);

        btn.innerHTML = originalText;
        btn.disabled = false;

        if (response.success && response.tracking) {
            // Polling re-sends the same payload so the hash/phone that proved
            // ownership keeps the live map visible.
            lastPayload = payload;
            const displayId = (response.order && response.order.formatted_id)
                || (payload.order_id ? formatOrderId(payload.order_id) : rawOrderId);
            showOrderTrackingReal(displayId, response.tracking, response.order, response.location, response.map);
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

function showOrderTrackingReal(orderId, tracking, order, location, mapData) {
    document.getElementById('trackingResult').classList.remove('d-none');
    updateLiveMap(mapData, tracking, order);
    
    // Update Order ID
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
        // Restaurant name
        const restaurantEl = document.getElementById('trackRestaurant');
        if (restaurantEl && location) {
            restaurantEl.textContent = location.name || 'Restaurant';
        }
        
        // Order items
        const itemsEl = document.getElementById('trackItems');
        if (itemsEl) {
            itemsEl.textContent = order.items_summary || 'Order items';
        }
        
        // Delivery address
        const addressEl = document.getElementById('trackAddress');
        if (addressEl) {
            addressEl.textContent = order.delivery_address || 'Delivery address';
        }
        
        // Total
        const totalEl = document.getElementById('trackTotal');
        if (totalEl) {
            totalEl.textContent = order.total_formatted || 'UGX ' + (order.total || 0).toLocaleString();
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
        const avatar = document.querySelector('.rider-avatar-lg img');
        if (avatar) {
            avatar.src = 'https://ui-avatars.com/api/?background=ff6600&color=fff&size=80&name=' + encodeURIComponent(tracking.rider.name || 'Rider');
        }
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
    
    // Start live updates
    startLiveUpdates(order?.id);
}

function updateTimelineFromStatus(status) {
    // Timeline steps: 0 Placed, 1 Confirmed, 2 Preparing, 3 Out for Delivery, 4 Delivered
    const stepForStatus = {
        placed: 0,
        confirmed: 1,
        preparing: 2,
        ready: 2,
        picked_up: 3,
        on_the_way: 3,
        nearby: 3,
        arriving: 3,
        delivered: 5, // past the last step: everything completed
    };
    const currentIndex = stepForStatus[status] ?? 1;
    
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
    lastPayload = null;
    mapFitted = false;
    lastRouteFrom = null;
}

let updateInterval;
let currentOrderId = null;
let lastPayload = null;

// Live map (Leaflet + OpenStreetMap). Shows the restaurant, the rider's last
// shared GPS position and the delivery point, with the road route from the
// rider (or the restaurant before pickup) to the customer.
// Note: never put three dashes in a row in this file; TastyIgniter splits
// theme pages into sections on that separator.
const KAMPALA_CENTER = [0.3476, 32.5825];
const ROUTE_MIN_MOVE_METERS = 75;
let liveMap = null;
let mapLayers = null;
let routeLine = null;
let lastRouteFrom = null;
let routeEtaMinutes = null;
let mapFitted = false;

function pinIcon(type, faIcon) {
    return L.divIcon({
        className: '',
        html: `<div class="map-pin ${type}"><i class="fa ${faIcon}"></i></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
    });
}

function ensureMap() {
    if (liveMap || typeof L === 'undefined') return liveMap;
    liveMap = L.map('orderMap', {scrollWheelZoom: false}).setView(KAMPALA_CENTER, 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(liveMap);
    mapLayers = L.layerGroup().addTo(liveMap);
    return liveMap;
}

function setMapNotice(text) {
    document.getElementById('mapNotice').innerHTML = text || '';
}

function updateLiveMap(mapData, tracking, order) {
    const mapEl = document.getElementById('orderMap');
    if (order && order.order_type && order.order_type !== 'delivery') {
        mapEl.classList.add('d-none');
        setMapNotice('<i class="fa fa-store me-1"></i>This is a pickup order — collect it at the restaurant.');
        return;
    }
    mapEl.classList.remove('d-none');

    if (!ensureMap() || !mapData) return;
    // The container may have been hidden when the map was created.
    setTimeout(() => liveMap.invalidateSize(), 50);

    mapLayers.clearLayers();
    const points = [];

    const restaurant = mapData.restaurant ? [mapData.restaurant.lat, mapData.restaurant.lng] : null;
    const delivery = mapData.delivery ? [mapData.delivery.lat, mapData.delivery.lng] : null;
    const rider = tracking && tracking.location ? [tracking.location.lat, tracking.location.lng] : null;

    if (restaurant) {
        L.marker(restaurant, {icon: pinIcon('restaurant', 'fa-store')}).bindPopup('Restaurant').addTo(mapLayers);
        points.push(restaurant);
    }
    if (delivery) {
        L.marker(delivery, {icon: pinIcon('destination', 'fa-home')}).bindPopup('Delivery point').addTo(mapLayers);
        points.push(delivery);
    }
    const riderActive = rider && tracking && !tracking.is_delivered;
    if (riderActive) {
        const seen = tracking.location.updated_at ? new Date(tracking.location.updated_at) : null;
        const ago = seen ? Math.max(0, Math.round((Date.now() - seen.getTime()) / 60000)) : null;
        L.marker(rider, {icon: pinIcon('rider', 'fa-motorcycle'), zIndexOffset: 1000})
            .bindPopup('Your rider' + (ago !== null ? ` · updated ${ago === 0 ? 'just now' : ago + ' min ago'}` : ''))
            .addTo(mapLayers);
        points.push(rider);
    }

    if (!mapData.allowed) {
        setMapNotice('<i class="fa fa-lock me-1"></i>Log in, or open the tracking link from your order confirmation, to see your rider live on the map.');
    } else if (!delivery) {
        setMapNotice('<i class="fa fa-info-circle me-1"></i>We could not place your delivery address on the map.');
    } else if (!riderActive && !(tracking && tracking.is_delivered)) {
        setMapNotice('<i class="fa fa-clock me-1"></i>Your rider\'s live position appears here once they set off.');
    } else if (mapData.delivery_source === 'geocoded') {
        setMapNotice('<i class="fa fa-info-circle me-1"></i>Delivery pin is approximate (from your address).');
    } else {
        setMapNotice('');
    }

    const from = riderActive ? rider : restaurant;
    if (from && delivery && !(tracking && tracking.is_delivered)) {
        drawRoute(from, delivery);
    } else if (routeLine) {
        routeLine.remove();
        routeLine = null;
        document.getElementById('routeInfo').textContent = '';
    }

    // Fit once, then keep the customer's zoom/pan while the rider moves.
    if (!mapFitted && points.length) {
        if (points.length > 1) liveMap.fitBounds(points, {padding: [40, 40], maxZoom: 16});
        else liveMap.setView(points[0], 15);
        mapFitted = true;
    }
}

function distanceMeters(a, b) {
    return liveMap ? liveMap.distance(a, b) : Infinity;
}

async function drawRoute(from, to) {
    if (lastRouteFrom && routeLine && distanceMeters(lastRouteFrom, from) < ROUTE_MIN_MOVE_METERS) {
        if (!liveMap.hasLayer(routeLine)) routeLine.addTo(liveMap);
        return;
    }
    lastRouteFrom = from;

    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${from[1]},${from[0]};${to[1]},${to[0]}?overview=full&geometries=geojson`;
        const res = await fetch(url);
        const data = await res.json();
        const route = data.routes && data.routes[0];
        if (!route) throw new Error('no route');

        const latLngs = route.geometry.coordinates.map(c => [c[1], c[0]]);
        if (routeLine) routeLine.remove();
        routeLine = L.polyline(latLngs, {color: '#ff4900', weight: 5, opacity: 0.8}).addTo(liveMap);
        // Boda-bodas beat car times in Kampala traffic; keep the car estimate as an upper bound.
        routeEtaMinutes = Math.max(1, Math.round(route.duration / 60));
        document.getElementById('routeInfo').textContent = '· ' + (route.distance / 1000).toFixed(1) + ' km by road';
    } catch (e) {
        // Routing is optional: fall back to a straight dashed line.
        if (routeLine) routeLine.remove();
        routeLine = L.polyline([from, to], {color: '#ff4900', weight: 3, dashArray: '6 8'}).addTo(liveMap);
        routeEtaMinutes = null;
        document.getElementById('routeInfo').textContent = '';
    }
}

function startLiveUpdates(orderId) {
    // Clear any existing interval
    if (updateInterval) clearInterval(updateInterval);

    currentOrderId = orderId;

    // Poll every 10 seconds so the rider moves smoothly on the map
    updateInterval = setInterval(async () => {
        if (!currentOrderId) return;

        try {
            const response = await apiCall('/tracking/track', 'POST', lastPayload || {
                order_id: currentOrderId,
            });

            if (response.success && response.tracking) {
                updateLiveMap(response.map, response.tracking, response.order);

                // Update ETA
                if (response.tracking.eta_minutes) {
                    document.getElementById('etaTime').textContent = response.tracking.eta_minutes + ' mins';
                } else if (routeEtaMinutes && response.tracking.location) {
                    document.getElementById('etaTime').textContent = 'about ' + routeEtaMinutes + ' mins';
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
    }, 10000);
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
