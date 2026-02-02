---
title: 'UgaEats Premium Features'
layout: default
permalink: /account/features
security: customer
---
<div class="container">
    <div class="row py-5">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu"/>
        </div>

        <div class="col-sm-10">
            <!-- Features CSS -->
            <link rel="stylesheet" href="{{ asset('themes/demo/assets/css/features.css') }}">
            
            <!-- Feature Tabs -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="featureTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="group-orders-tab" data-bs-toggle="tab" data-bs-target="#group-orders" type="button" role="tab">
                                <i class="fa fa-users me-2"></i>Group Orders
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="scheduled-orders-tab" data-bs-toggle="tab" data-bs-target="#scheduled-orders" type="button" role="tab">
                                <i class="fa fa-clock me-2"></i>Schedule Order
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subscriptions-tab" data-bs-toggle="tab" data-bs-target="#subscriptions" type="button" role="tab">
                                <i class="fa fa-calendar-check me-2"></i>Subscriptions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="order-tracking-tab" data-bs-toggle="tab" data-bs-target="#order-tracking" type="button" role="tab">
                                <i class="fa fa-map-marker-alt me-2"></i>Track Order
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content" id="featureTabsContent">
                        
                        <!-- GROUP ORDERS TAB -->
                        <div class="tab-pane fade show active" id="group-orders" role="tabpanel">
                            <div class="group-order-container">
                                <!-- Create New Group Order Section -->
                                <div class="group-order-header">
                                    <h4><i class="fa fa-users text-primary me-2"></i>Group Orders</h4>
                                    <p class="text-muted mb-0">Order together with friends and family - everyone adds their items, you pay together!</p>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card group-order-card h-100" onclick="showCreateGroupModal()">
                                            <div class="card-body text-center py-5">
                                                <div class="group-order-icon">
                                                    <i class="fa fa-plus-circle text-primary"></i>
                                                </div>
                                                <h5 class="mt-3">Create Group Order</h5>
                                                <p class="text-muted">Start a new group order and invite friends</p>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-plus me-2"></i>Create New
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card group-order-card h-100" onclick="showJoinGroupModal()">
                                            <div class="card-body text-center py-5">
                                                <div class="group-order-icon">
                                                    <i class="fa fa-link text-success"></i>
                                                </div>
                                                <h5 class="mt-3">Join Group Order</h5>
                                                <p class="text-muted">Enter a code to join an existing group</p>
                                                <button class="btn btn-success">
                                                    <i class="fa fa-sign-in-alt me-2"></i>Join Now
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Group Orders -->
                                <div class="mt-5" id="activeGroupOrders">
                                    <h5><i class="fa fa-list me-2"></i>Your Active Group Orders</h5>
                                    <div id="groupOrdersList">
                                        <!-- Will be populated by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SCHEDULED ORDERS TAB -->
                        <div class="tab-pane fade" id="scheduled-orders" role="tabpanel">
                            <div class="scheduled-order-container">
                                <div class="scheduled-order-header">
                                    <h4><i class="fa fa-clock text-warning me-2"></i>Schedule Your Order</h4>
                                    <p class="text-muted mb-0">Plan ahead! Schedule your order for later today or future dates.</p>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fa fa-calendar me-2"></i>Select Date</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="date-selector">
                                                    <div class="date-option selected" data-date="today">
                                                        <div class="date-day">Today</div>
                                                        <div class="date-num" id="todayDate"></div>
                                                    </div>
                                                    <div class="date-option" data-date="tomorrow">
                                                        <div class="date-day">Tomorrow</div>
                                                        <div class="date-num" id="tomorrowDate"></div>
                                                    </div>
                                                    <div class="date-option" data-date="custom">
                                                        <div class="date-day">Custom</div>
                                                        <div class="date-num"><i class="fa fa-calendar-plus"></i></div>
                                                    </div>
                                                </div>
                                                <input type="date" class="form-control mt-3 d-none" id="customDatePicker" min="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fa fa-clock me-2"></i>Select Time</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="time-slots" id="timeSlots">
                                                    <!-- Time slots populated by JS -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recurring Order Option -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="recurringSwitch">
                                            <label class="form-check-label" for="recurringSwitch">
                                                <strong>Make this a recurring order</strong>
                                                <span class="text-muted d-block small">Automatically reorder on your schedule</span>
                                            </label>
                                        </div>
                                        
                                        <div class="recurring-options mt-3 d-none" id="recurringOptions">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Repeat Every</label>
                                                    <select class="form-select" id="recurringFrequency">
                                                        <option value="daily">Every Day</option>
                                                        <option value="weekly" selected>Every Week</option>
                                                        <option value="biweekly">Every 2 Weeks</option>
                                                        <option value="monthly">Every Month</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">End After</label>
                                                    <select class="form-select" id="recurringEnd">
                                                        <option value="4">4 orders</option>
                                                        <option value="8">8 orders</option>
                                                        <option value="12">12 orders</option>
                                                        <option value="never">Until I cancel</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="scheduled-order-summary mt-4">
                                    <h4><i class="fa fa-check-circle text-success me-2"></i>Order Summary</h4>
                                    <p class="mb-0">Your order will be delivered on: <strong id="scheduledDateTime">Today at --:--</strong></p>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="proceedToMenuWithSchedule()">
                                        <i class="fa fa-utensils me-2"></i>Choose Restaurant & Menu
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SUBSCRIPTIONS TAB -->
                        <div class="tab-pane fade" id="subscriptions" role="tabpanel">
                            <div class="subscription-container">
                                <div class="subscription-header">
                                    <h4><i class="fa fa-crown text-warning me-2"></i>Meal Subscription Plans</h4>
                                    <p class="text-muted mb-0">Save money with weekly or monthly meal plans. Cancel anytime!</p>
                                </div>
                                
                                <div class="row mt-4 subscription-plans">
                                    <!-- Basic Plan -->
                                    <div class="col-md-4">
                                        <div class="subscription-card">
                                            <div class="subscription-badge">Starter</div>
                                            <div class="subscription-plan">
                                                <h5>Basic Plan</h5>
                                                <div class="subscription-price">
                                                    <span class="amount">UGX 150,000</span>
                                                    <span class="period">/week</span>
                                                </div>
                                                <ul class="subscription-features">
                                                    <li><i class="fa fa-check text-success"></i> 5 meals per week</li>
                                                    <li><i class="fa fa-check text-success"></i> Free delivery</li>
                                                    <li><i class="fa fa-check text-success"></i> Standard restaurants</li>
                                                    <li><i class="fa fa-times text-muted"></i> Premium restaurants</li>
                                                    <li><i class="fa fa-times text-muted"></i> Priority support</li>
                                                </ul>
                                                <button class="btn btn-outline-primary w-100" onclick="selectPlan('basic')">
                                                    Select Plan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Premium Plan -->
                                    <div class="col-md-4">
                                        <div class="subscription-card featured">
                                            <div class="subscription-badge bg-primary">Most Popular</div>
                                            <div class="subscription-plan">
                                                <h5>Premium Plan</h5>
                                                <div class="subscription-price">
                                                    <span class="amount">UGX 280,000</span>
                                                    <span class="period">/week</span>
                                                </div>
                                                <ul class="subscription-features">
                                                    <li><i class="fa fa-check text-success"></i> 10 meals per week</li>
                                                    <li><i class="fa fa-check text-success"></i> Free express delivery</li>
                                                    <li><i class="fa fa-check text-success"></i> All restaurants</li>
                                                    <li><i class="fa fa-check text-success"></i> Premium restaurants</li>
                                                    <li><i class="fa fa-check text-success"></i> Priority support</li>
                                                </ul>
                                                <button class="btn btn-primary w-100" onclick="selectPlan('premium')">
                                                    Select Plan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Family Plan -->
                                    <div class="col-md-4">
                                        <div class="subscription-card">
                                            <div class="subscription-badge bg-success">Best Value</div>
                                            <div class="subscription-plan">
                                                <h5>Family Plan</h5>
                                                <div class="subscription-price">
                                                    <span class="amount">UGX 450,000</span>
                                                    <span class="period">/week</span>
                                                </div>
                                                <ul class="subscription-features">
                                                    <li><i class="fa fa-check text-success"></i> 21 meals per week</li>
                                                    <li><i class="fa fa-check text-success"></i> Free express delivery</li>
                                                    <li><i class="fa fa-check text-success"></i> All restaurants</li>
                                                    <li><i class="fa fa-check text-success"></i> Family sharing (4)</li>
                                                    <li><i class="fa fa-check text-success"></i> VIP support</li>
                                                </ul>
                                                <button class="btn btn-outline-success w-100" onclick="selectPlan('family')">
                                                    Select Plan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Current Subscription Status -->
                                <div class="card mt-5" id="currentSubscription">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fa fa-info-circle me-2"></i>Your Current Subscription</h6>
                                    </div>
                                    <div class="card-body text-center py-4">
                                        <p class="text-muted mb-3">You don't have an active subscription</p>
                                        <p class="small">Subscribe to save up to 20% on your weekly food orders!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ORDER TRACKING TAB -->
                        <div class="tab-pane fade" id="order-tracking" role="tabpanel">
                            <div class="tracking-container">
                                <div class="tracking-header">
                                    <h4><i class="fa fa-motorcycle text-info me-2"></i>Track Your Order</h4>
                                    <p class="text-muted mb-0">Real-time updates on your order status and delivery location</p>
                                </div>
                                
                                <!-- Order Search -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="form-label">Enter Order Number</label>
                                                <input type="text" class="form-control form-control-lg" id="trackingOrderId" placeholder="e.g., UGA-2026-12345">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button class="btn btn-primary btn-lg w-100" onclick="trackOrder()">
                                                    <i class="fa fa-search me-2"></i>Track Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tracking Result (hidden by default) -->
                                <div id="trackingResult" class="d-none">
                                    <div class="row mt-4">
                                        <div class="col-md-7">
                                            <!-- Map Placeholder -->
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div class="tracking-map" id="orderMap">
                                                        <div class="map-placeholder">
                                                            <i class="fa fa-map-marked-alt fa-3x text-muted"></i>
                                                            <p class="mt-3">Live tracking map</p>
                                                            <div class="rider-marker">
                                                                <i class="fa fa-motorcycle"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Rider Info -->
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rider-avatar">
                                                            <i class="fa fa-user-circle fa-3x text-muted"></i>
                                                        </div>
                                                        <div class="ms-3 flex-grow-1">
                                                            <h6 class="mb-0" id="riderName">Dennis K.</h6>
                                                            <small class="text-muted">Your Delivery Rider</small>
                                                            <div class="mt-1">
                                                                <i class="fa fa-star text-warning"></i>
                                                                <span id="riderRating">4.9</span>
                                                                <span class="text-muted ms-2" id="riderDeliveries">• 2,340 deliveries</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="tel:+256779081600" class="btn btn-success btn-sm me-2">
                                                                <i class="fa fa-phone"></i>
                                                            </a>
                                                            <a href="https://wa.me/256779081600" class="btn btn-success btn-sm" target="_blank">
                                                                <i class="fab fa-whatsapp"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-5">
                                            <!-- Order Status Timeline -->
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Order Status</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tracking-timeline">
                                                        <div class="timeline-item completed">
                                                            <div class="timeline-marker"></div>
                                                            <div class="timeline-content">
                                                                <h6>Order Confirmed</h6>
                                                                <small class="text-muted">2:30 PM</small>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-item completed">
                                                            <div class="timeline-marker"></div>
                                                            <div class="timeline-content">
                                                                <h6>Restaurant Preparing</h6>
                                                                <small class="text-muted">2:35 PM</small>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-item active">
                                                            <div class="timeline-marker pulse"></div>
                                                            <div class="timeline-content">
                                                                <h6>Out for Delivery</h6>
                                                                <small class="text-muted">2:55 PM</small>
                                                                <p class="mb-0 mt-1 text-primary small">
                                                                    <i class="fa fa-clock"></i> ETA: 15 mins
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-item">
                                                            <div class="timeline-marker"></div>
                                                            <div class="timeline-content">
                                                                <h6>Delivered</h6>
                                                                <small class="text-muted">Pending</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Order Details -->
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Order Details</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-2"><strong>Restaurant:</strong> <span id="trackRestaurant">Café Javas</span></p>
                                                    <p class="mb-2"><strong>Items:</strong> <span id="trackItems">3 items</span></p>
                                                    <p class="mb-2"><strong>Total:</strong> <span id="trackTotal">UGX 45,000</span></p>
                                                    <p class="mb-0"><strong>Delivery:</strong> <span id="trackAddress">Kyebando Road, Kampala</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Orders for Quick Tracking -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fa fa-history me-2"></i>Recent Active Orders</h6>
                                    </div>
                                    <div class="card-body" id="recentActiveOrders">
                                        <p class="text-muted text-center py-3">No active orders to track</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Group Order Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-users text-primary me-2"></i>Create Group Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Group Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="groupName" placeholder="e.g., Office Lunch, Family Dinner">
                    <small class="text-muted">Give your group order a memorable name</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Order Deadline</label>
                    <input type="datetime-local" class="form-control" id="groupDeadline">
                    <small class="text-muted">When should everyone finish adding their items?</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Split Bill Method</label>
                    <select class="form-select" id="splitMethod">
                        <option value="by_item" selected>Pay for Own Items</option>
                        <option value="equal">Split Equally</option>
                        <option value="host_pays">Host Pays All</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createGroupOrder()">
                    <i class="fa fa-plus me-2"></i>Create Group
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Join Group Order Modal -->
<div class="modal fade" id="joinGroupModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-link text-success me-2"></i>Join Group Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Enter Group Code</label>
                    <input type="text" class="form-control form-control-lg text-center" id="groupCode" placeholder="XXXX-XXXX" maxlength="9" style="text-transform: uppercase; letter-spacing: 2px;">
                </div>
                <p class="text-muted small text-center">Ask the group host for the invite code</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="joinGroupOrder()">
                    <i class="fa fa-sign-in-alt me-2"></i>Join
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Confirmation Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-crown text-warning me-2"></i>Confirm Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="subscription-selected-plan mb-3">
                        <span id="selectedPlanBadge" class="badge bg-primary fs-6">Premium Plan</span>
                    </div>
                    <h4 id="selectedPlanPrice">UGX 280,000/week</h4>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Delivery Address</label>
                    <textarea class="form-control" rows="2" id="subscriptionAddress" placeholder="Enter your default delivery address"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Preferred Delivery Time</label>
                    <select class="form-select" id="subscriptionTime">
                        <option value="12:00">12:00 PM - Lunch</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="18:00">6:00 PM - Dinner</option>
                        <option value="19:00">7:00 PM</option>
                    </select>
                </div>
                
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i>
                    <small>You can cancel anytime. First payment will be charged today.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmSubscription()">
                    <i class="fa fa-credit-card me-2"></i>Subscribe Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ============ API CONFIGURATION ============
const API_BASE = '/ajax';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Helper function for API calls
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
    
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        const json = await response.json();
        
        if (!response.ok) {
            throw new Error(json.error || json.message || 'API request failed');
        }
        
        return json;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// ============ INITIALIZATION ============
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date displays
    initializeDates();
    initializeTimeSlots();
    loadGroupOrders();
    loadSubscriptionPlans();
    updateSubscriptionStatus();
    
    // Check URL hash for direct tab access
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (tab) {
            const bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    }
});

// ============ DATE/TIME FUNCTIONS ============
function initializeDates() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    document.getElementById('todayDate').textContent = today.getDate();
    document.getElementById('tomorrowDate').textContent = tomorrow.getDate();
    
    // Set min date for custom picker
    const minDate = today.toISOString().split('T')[0];
    document.getElementById('customDatePicker').min = minDate;
    
    // Date option click handler
    document.querySelectorAll('.date-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.date-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            
            if (this.dataset.date === 'custom') {
                document.getElementById('customDatePicker').classList.remove('d-none');
            } else {
                document.getElementById('customDatePicker').classList.add('d-none');
            }
            
            updateScheduledSummary();
        });
    });
    
    // Recurring toggle
    document.getElementById('recurringSwitch').addEventListener('change', function() {
        const options = document.getElementById('recurringOptions');
        if (this.checked) {
            options.classList.remove('d-none');
        } else {
            options.classList.add('d-none');
        }
    });
}

function initializeTimeSlots() {
    const slotsContainer = document.getElementById('timeSlots');
    const now = new Date();
    const currentHour = now.getHours();
    
    // Generate time slots from 8 AM to 10 PM
    for (let hour = 8; hour <= 22; hour++) {
        const isUnavailable = hour <= currentHour;
        const displayHour = hour > 12 ? hour - 12 : hour;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        
        const slot = document.createElement('div');
        slot.className = `time-slot${isUnavailable ? ' unavailable' : ''}`;
        slot.dataset.time = `${hour}:00`;
        slot.innerHTML = `${displayHour}:00 ${ampm}`;
        
        if (!isUnavailable) {
            slot.addEventListener('click', function() {
                document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                updateScheduledSummary();
            });
        }
        
        slotsContainer.appendChild(slot);
    }
}

function updateScheduledSummary() {
    const selectedDate = document.querySelector('.date-option.selected');
    const selectedTime = document.querySelector('.time-slot.selected');
    
    let dateStr = 'Today';
    if (selectedDate) {
        if (selectedDate.dataset.date === 'tomorrow') {
            dateStr = 'Tomorrow';
        } else if (selectedDate.dataset.date === 'custom') {
            const customDate = document.getElementById('customDatePicker').value;
            if (customDate) {
                dateStr = new Date(customDate).toLocaleDateString('en-UG', { 
                    weekday: 'long', 
                    month: 'short', 
                    day: 'numeric' 
                });
            }
        }
    }
    
    const timeStr = selectedTime ? selectedTime.textContent : '--:--';
    document.getElementById('scheduledDateTime').textContent = `${dateStr} at ${timeStr}`;
}

/**
 * Get the selected schedule date in YYYY-MM-DD format
 */
function getSelectedScheduleDate() {
    const selectedDate = document.querySelector('.date-option.selected');
    if (!selectedDate) {
        // Default to today
        return new Date().toISOString().split('T')[0];
    }
    
    const dateType = selectedDate.dataset.date;
    const today = new Date();
    
    if (dateType === 'today') {
        return today.toISOString().split('T')[0];
    } else if (dateType === 'tomorrow') {
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        return tomorrow.toISOString().split('T')[0];
    } else if (dateType === 'custom') {
        const customDate = document.getElementById('customDatePicker').value;
        return customDate || today.toISOString().split('T')[0];
    }
    
    return today.toISOString().split('T')[0];
}

/**
 * Get the selected schedule time in HH:MM format
 */
function getSelectedScheduleTime() {
    const selectedTime = document.querySelector('.time-slot.selected');
    if (!selectedTime || !selectedTime.dataset.time) {
        // Default to next available hour
        const now = new Date();
        const nextHour = now.getHours() + 1;
        return `${String(nextHour).padStart(2, '0')}:00`;
    }
    
    const timeData = selectedTime.dataset.time;
    // Ensure HH:MM format
    const parts = timeData.split(':');
    return `${String(parts[0]).padStart(2, '0')}:${parts[1] || '00'}`;
}

/**
 * Proceed to menu after setting the schedule in TastyIgniter's session
 */
async function proceedToMenuWithSchedule() {
    const orderDate = getSelectedScheduleDate();
    const orderTime = getSelectedScheduleTime();
    
    // Validate selection
    if (!orderDate || !orderTime) {
        alert('Please select a date and time for your order');
        return;
    }
    
    // Show loading state
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Setting schedule...';
    
    try {
        // Store in localStorage as backup
        const scheduleData = {
            order_date: orderDate,
            order_time: orderTime,
            is_asap: false,
            set_at: new Date().toISOString()
        };
        localStorage.setItem('ugaeats_scheduled_order', JSON.stringify(scheduleData));
        
        // Call AJAX endpoint to set schedule in TastyIgniter session
        const response = await fetch('/ajax/location-schedule/set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                order_date: orderDate,
                order_time: orderTime,
                is_asap: false,
                order_type: 'delivery' // Default to delivery
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update localStorage with confirmed data
            scheduleData.confirmed = true;
            scheduleData.location_id = result.data?.location_id;
            scheduleData.location_name = result.data?.location_name;
            localStorage.setItem('ugaeats_scheduled_order', JSON.stringify(scheduleData));
            
            // Redirect to menu page
            window.location.href = '{{ page_url("local.menus") }}';
        } else {
            // API failed but we have localStorage backup, proceed anyway
            console.warn('Schedule API failed, using localStorage fallback:', result.message);
            window.location.href = '{{ page_url("local.menus") }}';
        }
    } catch (error) {
        console.error('Error setting schedule:', error);
        // Proceed anyway with localStorage backup
        window.location.href = '{{ page_url("local.menus") }}';
    }
}

// ============ GROUP ORDER FUNCTIONS ============
function showCreateGroupModal() {
    const modal = new bootstrap.Modal(document.getElementById('createGroupModal'));
    
    // Set default deadline to 1 hour from now
    const deadline = new Date();
    deadline.setHours(deadline.getHours() + 1);
    document.getElementById('groupDeadline').value = deadline.toISOString().slice(0, 16);
    
    modal.show();
}

function showJoinGroupModal() {
    const modal = new bootstrap.Modal(document.getElementById('joinGroupModal'));
    document.getElementById('groupCode').value = '';
    modal.show();
}

// Helper function for Group Order AJAX calls (uses web routes with session)
async function groupOrderCall(endpoint, method = 'GET', data = null) {
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'include',
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    const response = await fetch(`/ajax/group-orders${endpoint}`, options);
    
    // Try to parse JSON, handle non-JSON responses
    let json;
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
        json = await response.json();
    } else {
        const text = await response.text();
        console.error('Non-JSON response:', text.substring(0, 500));
        throw new Error('Server returned non-JSON response. You may need to log in again.');
    }
    
    if (!response.ok) {
        throw new Error(json.error || json.message || 'Request failed');
    }
    
    return json;
}

async function createGroupOrder() {
    const name = document.getElementById('groupName').value.trim();
    const deadline = document.getElementById('groupDeadline').value;
    const splitMethod = document.getElementById('splitMethod').value;
    
    if (!name) {
        alert('Please enter a group name');
        return;
    }
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
    
    try {
        const response = await groupOrderCall('', 'POST', {
            title: name,
            deadline_at: deadline || null,
            split_method: splitMethod,
        });
        
        if (response.success) {
            // Close modal and show success
            bootstrap.Modal.getInstance(document.getElementById('createGroupModal')).hide();
            
            // Show invite code with sharing options
            showGroupCreatedAlert(response.invite_code);
            
            // Reload group orders list
            loadGroupOrders();
            
            // Ask if user wants to go to menu
            setTimeout(() => {
                if (confirm('Group created! Would you like to go to the menu to start adding items?')) {
                    window.location.href = '{{ page_url("local.menus") }}';
                }
            }, 500);
        } else {
            throw new Error(response.error || 'Failed to create group order');
        }
    } catch (error) {
        console.error('Create group order error:', error);
        alert('Failed to create group order: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plus me-2"></i>Create Group';
    }
}

function generateGroupCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        if (i === 4) code += '-';
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}

function showGroupCreatedAlert(code) {
    const alertHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fa fa-check-circle me-2"></i>Group Created!</strong>
            <p class="mb-2">Share this code with your friends:</p>
            <div class="d-flex align-items-center gap-2">
                <code class="fs-5 bg-white p-2 rounded">${code}</code>
                <button class="btn btn-sm btn-outline-success" onclick="copyGroupCode('${code}')">
                    <i class="fa fa-copy"></i> Copy
                </button>
                <a href="https://wa.me/?text=Join%20my%20UgaEats%20group%20order!%20Code:%20${code}" 
                   class="btn btn-sm btn-success" target="_blank">
                    <i class="fab fa-whatsapp"></i> Share
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.getElementById('activeGroupOrders').insertAdjacentHTML('afterbegin', alertHtml);
}

function copyGroupCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert('Code copied to clipboard!');
    });
}

async function joinGroupOrder() {
    const code = document.getElementById('groupCode').value.toUpperCase().replace(/-/g, '');
    
    if (!code || code.length < 6) {
        alert('Please enter a valid group code (at least 6 characters)');
        return;
    }
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Joining...';
    
    try {
        const response = await groupOrderCall('/join', 'POST', {
            invite_code: code,
        });
        
        if (response.success) {
            bootstrap.Modal.getInstance(document.getElementById('joinGroupModal')).hide();
            
            const groupName = response.group_order?.title || 'the group order';
            alert(`Successfully joined "${groupName}"! You can now add your items.`);
            loadGroupOrders();
            
            // Ask if user wants to go to menu
            setTimeout(() => {
                if (confirm('Would you like to go to the menu to add your items?')) {
                    window.location.href = '{{ page_url("local.menus") }}';
                }
            }, 500);
        } else {
            throw new Error(response.error || 'Failed to join group order');
        }
    } catch (error) {
        console.error('Join group order error:', error);
        alert('Failed to join: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-sign-in-alt me-2"></i>Join Group';
    }
}

async function loadGroupOrders() {
    const container = document.getElementById('groupOrdersList');
    container.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Loading...</div>';
    
    try {
        const response = await groupOrderCall('/');
        const hosted = response.hosted || [];
        const participating = response.participating || [];
        const groups = [...hosted, ...participating];
        
        if (groups.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fa fa-users fa-2x mb-3 d-block"></i>
                    <p>No active group orders</p>
                    <p class="small">Create a new group or join one with a code!</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        groups.forEach(group => {
            const isHost = hosted.some(h => h.id === group.id);
            const deadline = group.deadline_at ? new Date(group.deadline_at) : null;
            const isExpired = deadline && deadline < new Date();
            const participantCount = group.participants?.length || 0;
            const totalAmount = Number(group.total_amount || 0);
            
            html += `
                <div class="card mb-3 group-order-item ${isExpired ? 'border-danger' : 'border-primary'}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    ${group.title}
                                    ${isHost ? '<span class="badge bg-warning text-dark ms-2"><i class="fa fa-crown"></i> Host</span>' : ''}
                                </h6>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <code class="bg-light p-1 rounded">${formatInviteCode(group.invite_code)}</code>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyGroupCode('${group.invite_code}')">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    <a href="https://wa.me/?text=Join%20my%20UgaEats%20group%20order!%20Code:%20${group.invite_code}" 
                                       class="btn btn-sm btn-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                            <span class="badge ${isExpired ? 'bg-danger' : (group.status === 'open' ? 'bg-success' : 'bg-info')}">
                                ${isExpired ? 'Closed' : (group.status === 'open' ? 'Open' : group.status.charAt(0).toUpperCase() + group.status.slice(1))}
                            </span>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-4 text-center">
                                <i class="fa fa-users text-primary"></i>
                                <div class="small">${participantCount} ${participantCount === 1 ? 'person' : 'people'}</div>
                            </div>
                            <div class="col-4 text-center">
                                <i class="fa fa-clock text-warning"></i>
                                <div class="small">${deadline ? deadline.toLocaleString('en-UG', {dateStyle: 'short', timeStyle: 'short'}) : 'No deadline'}</div>
                            </div>
                            <div class="col-4 text-center">
                                <i class="fa fa-money-bill text-success"></i>
                                <div class="small">UGX ${totalAmount.toLocaleString()}</div>
                            </div>
                        </div>
                        
                        <!-- Participants list -->
                        <div class="mt-3">
                            <small class="text-muted">Participants:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                ${(group.participants || []).map(p => `
                                    <span class="badge ${p.status === 'ready' ? 'bg-success' : 'bg-secondary'}">${p.name} ${p.status === 'ready' ? '✓' : ''}</span>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div class="mt-3 d-flex gap-2">
                            ${!isExpired && group.status === 'open' ? `
                                <a href="${window.location.origin}/local/menus" class="btn btn-sm btn-primary" onclick="setActiveGroupOrder(${group.id})">
                                    <i class="fa fa-plus me-1"></i>Add Items
                                </a>
                            ` : ''}
                            ${isHost && group.status === 'open' ? `
                                <button class="btn btn-sm btn-success" onclick="finalizeGroupOrder(${group.id})">
                                    <i class="fa fa-check me-1"></i>Finalize
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelGroupOrder(${group.id})">
                                    <i class="fa fa-times"></i>
                                </button>
                            ` : ''}
                            ${!isHost ? `
                                <button class="btn btn-sm btn-outline-secondary" onclick="leaveGroupOrder(${group.id})">
                                    <i class="fa fa-sign-out-alt me-1"></i>Leave
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Load group orders error:', error);
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fa fa-users fa-2x mb-3 d-block"></i>
                <p>No active group orders</p>
                <p class="small">Create a new group or join one with a code!</p>
            </div>
        `;
    }
}

function formatInviteCode(code) {
    if (!code) return '';
    // Format as XXXX-XXXX
    if (code.length === 8) {
        return code.substring(0, 4) + '-' + code.substring(4);
    }
    return code;
}

async function setActiveGroupOrder(groupOrderId) {
    try {
        await groupOrderCall('/set-active', 'POST', { group_order_id: groupOrderId });
    } catch (error) {
        console.error('Error setting active group order:', error);
    }
}

async function finalizeGroupOrder(groupOrderId) {
    if (!confirm('Are you sure you want to finalize this group order? No more items can be added after this.')) {
        return;
    }
    
    try {
        const response = await groupOrderCall(`/${groupOrderId}/finalize`, 'POST');
        if (response.success) {
            alert('Group order finalized! You can now proceed to checkout.');
            loadGroupOrders();
        } else {
            throw new Error(response.error || 'Failed to finalize');
        }
    } catch (error) {
        alert('Failed to finalize: ' + error.message);
    }
}

async function cancelGroupOrder(groupOrderId) {
    if (!confirm('Are you sure you want to cancel this group order? This cannot be undone.')) {
        return;
    }
    
    try {
        const response = await groupOrderCall(`/${groupOrderId}/cancel`, 'POST');
        if (response.success) {
            alert('Group order cancelled.');
            loadGroupOrders();
        } else {
            throw new Error(response.error || 'Failed to cancel');
        }
    } catch (error) {
        alert('Failed to cancel: ' + error.message);
    }
}

async function leaveGroupOrder(groupOrderId) {
    if (!confirm('Are you sure you want to leave this group order?')) {
        return;
    }
    
    try {
        const response = await groupOrderCall(`/${groupOrderId}/leave`, 'POST');
        if (response.success) {
            alert('You have left the group order.');
            loadGroupOrders();
        } else {
            throw new Error(response.error || 'Failed to leave');
        }
    } catch (error) {
        alert('Failed to leave: ' + error.message);
    }
}

function deleteGroup(id) {
    cancelGroupOrder(id);
}

// ============ SUBSCRIPTION FUNCTIONS ============
let subscriptionPlans = {};

async function loadSubscriptionPlans() {
    try {
        const response = await apiCall('/subscriptions/plans');
        if (response.success && response.plans) {
            subscriptionPlans = {};
            response.plans.forEach(plan => {
                subscriptionPlans[plan.slug] = {
                    id: plan.id,
                    name: plan.name,
                    price: plan.formatted_price,
                    badge: plan.slug === 'basic' ? 'bg-secondary' : (plan.slug === 'premium' ? 'bg-primary' : 'bg-success')
                };
            });
        }
    } catch (error) {
        console.log('Using default subscription plans');
        subscriptionPlans = {
            basic: { name: 'Basic Plan', price: 'UGX 150,000/week', badge: 'bg-secondary' },
            premium: { name: 'Premium Plan', price: 'UGX 280,000/week', badge: 'bg-primary' },
            family: { name: 'Family Plan', price: 'UGX 450,000/week', badge: 'bg-success' }
        };
    }
}

function selectPlan(plan) {
    const plans = subscriptionPlans.basic ? subscriptionPlans : {
        basic: { name: 'Basic Plan', price: 'UGX 150,000/week', badge: 'bg-secondary' },
        premium: { name: 'Premium Plan', price: 'UGX 280,000/week', badge: 'bg-primary' },
        family: { name: 'Family Plan', price: 'UGX 450,000/week', badge: 'bg-success' }
    };
    
    const selected = plans[plan];
    document.getElementById('selectedPlanBadge').textContent = selected.name;
    document.getElementById('selectedPlanBadge').className = `badge ${selected.badge} fs-6`;
    document.getElementById('selectedPlanPrice').textContent = selected.price;
    document.getElementById('selectedPlanBadge').dataset.planId = selected.id || plan;
    document.getElementById('selectedPlanBadge').dataset.planSlug = plan;
    
    const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
    modal.show();
}

async function confirmSubscription() {
    const address = document.getElementById('subscriptionAddress').value;
    const time = document.getElementById('subscriptionTime').value;
    
    if (!address) {
        alert('Please enter your delivery address');
        return;
    }
    
    const planSlug = document.getElementById('selectedPlanBadge').dataset.planSlug;
    const planId = document.getElementById('selectedPlanBadge').dataset.planId;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    
    try {
        const response = await apiCall('/subscriptions/subscribe', 'POST', {
            plan_id: planId,
            auto_renew: true,
        });
        
        bootstrap.Modal.getInstance(document.getElementById('subscriptionModal')).hide();
        alert(response.message || 'Subscription activated! You will receive your first meal soon.');
        updateSubscriptionStatus();
    } catch (error) {
        // Fallback to localStorage for demo
        const subscription = {
            plan: document.getElementById('selectedPlanBadge').textContent,
            price: document.getElementById('selectedPlanPrice').textContent,
            address: address,
            deliveryTime: time,
            startDate: new Date().toISOString(),
            status: 'active'
        };
        localStorage.setItem('ugaeats_subscription', JSON.stringify(subscription));
        
        bootstrap.Modal.getInstance(document.getElementById('subscriptionModal')).hide();
        alert('Subscription activated! You will receive your first meal soon.');
        updateSubscriptionStatus();
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check me-2"></i>Confirm Subscription';
    }
}

async function updateSubscriptionStatus() {
    const container = document.getElementById('currentSubscription');
    
    try {
        const response = await apiCall('/subscriptions/current');
        
        if (response.has_subscription && response.subscription) {
            const sub = response.subscription;
            container.innerHTML = `
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fa fa-check-circle me-2"></i>Active Subscription</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Plan:</strong> ${sub.plan.name}</p>
                            <p class="mb-1"><strong>Meals Remaining:</strong> ${sub.meals_remaining}</p>
                            <p class="mb-1"><strong>Days Left:</strong> ${sub.days_remaining}</p>
                            <p class="mb-0"><strong>Auto-Renew:</strong> ${sub.auto_renew ? 'Yes' : 'No'}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-warning mb-2" onclick="pauseSubscription()">
                                <i class="fa fa-pause me-1"></i>Pause
                            </button>
                            <button class="btn btn-outline-danger" onclick="cancelSubscription()">
                                Cancel Subscription
                            </button>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-primary" style="width: ${sub.meals_used_percentage}%"></div>
                    </div>
                    <small class="text-muted">${sub.meals_used_percentage}% of meals used</small>
                </div>
            `;
        } else {
            showNoSubscriptionMessage(container);
        }
    } catch (error) {
        // Fallback to localStorage
        const sub = JSON.parse(localStorage.getItem('ugaeats_subscription'));
        
        if (sub && sub.status === 'active') {
            container.innerHTML = `
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fa fa-check-circle me-2"></i>Active Subscription</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Plan:</strong> ${sub.plan}</p>
                            <p class="mb-1"><strong>Price:</strong> ${sub.price}</p>
                            <p class="mb-0"><strong>Delivery Time:</strong> ${sub.deliveryTime}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-danger" onclick="cancelSubscription()">
                                Cancel Subscription
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            showNoSubscriptionMessage(container);
        }
    }
}

function showNoSubscriptionMessage(container) {
    container.innerHTML = `
        <div class="card-body text-center text-muted">
            <i class="fa fa-utensils fa-3x mb-3"></i>
            <p>No active subscription</p>
            <p class="small">Choose a plan above to get started!</p>
        </div>
    `;
}

async function pauseSubscription() {
    if (!confirm('Are you sure you want to pause your subscription?')) return;
    
    try {
        await apiCall('/subscriptions/pause', 'POST');
        alert('Subscription paused');
        updateSubscriptionStatus();
    } catch (error) {
        alert('Could not pause subscription. Please try again.');
    }
}

async function cancelSubscription() {
    if (!confirm('Are you sure you want to cancel your subscription?')) return;
    
    try {
        await apiCall('/subscriptions/cancel', 'POST');
        alert('Subscription cancelled');
        updateSubscriptionStatus();
    } catch (error) {
        // Fallback
        localStorage.removeItem('ugaeats_subscription');
        location.reload();
    }
}

// ============ ORDER TRACKING FUNCTIONS ============
async function trackOrder() {
    const orderId = document.getElementById('trackingOrderId').value.trim();
    
    if (!orderId) {
        alert('Please enter an order number');
        return;
    }
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Tracking...';
    
    try {
        const response = await apiCall('/tracking/track', 'POST', {
            order_id: orderId,
        });
        
        // Show tracking result
        document.getElementById('trackingResult').classList.remove('d-none');
        
        // Update the UI with real data
        if (response.tracking) {
            updateTrackingUI(response.tracking, response.order);
        }
    } catch (error) {
        // Show demo tracking
        document.getElementById('trackingResult').classList.remove('d-none');
        simulateTracking();
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-search me-2"></i>Track Order';
    }
}

function updateTrackingUI(tracking, order) {
    // Update rider info if available
    if (tracking.rider) {
        const riderCard = document.querySelector('.rider-card');
        if (riderCard) {
            riderCard.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${tracking.rider.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(tracking.rider.name)}" 
                         alt="Rider" class="rounded-circle me-3" width="60" height="60">
                    <div>
                        <h6 class="mb-0">${tracking.rider.name}</h6>
                        <small class="text-muted">Delivery Partner</small>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="tel:${tracking.rider.phone}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fa fa-phone"></i> Call
                    </a>
                    <a href="sms:${tracking.rider.phone}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-comment"></i> Message
                    </a>
                </div>
            `;
        }
    }
    
    // Update ETA
    if (tracking.eta_minutes) {
        const etaDisplay = document.querySelector('.eta-display');
        if (etaDisplay) {
            etaDisplay.innerHTML = `<strong>${tracking.eta_minutes}</strong> mins`;
        }
    }
    
    // Update timeline
    updateTrackingTimeline(tracking.status);
}

function updateTrackingTimeline(currentStatus) {
    const statuses = ['confirmed', 'preparing', 'ready', 'picked_up', 'on_the_way', 'nearby', 'delivered'];
    const currentIndex = statuses.indexOf(currentStatus);
    
    document.querySelectorAll('.timeline-item').forEach((item, index) => {
        item.classList.remove('completed', 'active');
        item.querySelector('.timeline-marker')?.classList.remove('pulse');
        
        if (index < currentIndex) {
            item.classList.add('completed');
        } else if (index === currentIndex) {
            item.classList.add('active');
            item.querySelector('.timeline-marker')?.classList.add('pulse');
        }
    });
}

function simulateTracking() {
    // This simulates live updates for demo purposes
    console.log('Tracking order with demo data...');
}

// Initialize subscription status on load
document.addEventListener('DOMContentLoaded', function() {
    updateSubscriptionStatus();
});
</script>

<style>
/* Additional inline styles for this page */
.group-order-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.group-order-card:hover {
    border-color: var(--bs-primary);
    transform: translateY(-5px);
}
.group-order-icon {
    font-size: 3rem;
}
.date-selector {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.date-option {
    flex: 1;
    min-width: 100px;
    padding: 1rem;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.date-option:hover, .date-option.selected {
    border-color: var(--bs-primary);
    background: rgba(var(--bs-primary-rgb), 0.1);
}
.date-option .date-num {
    font-size: 1.5rem;
    font-weight: bold;
}
.time-slots {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}
.time-slot {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.time-slot:hover:not(.unavailable) {
    background: #e9ecef;
}
.time-slot.selected {
    background: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}
.time-slot.unavailable {
    opacity: 0.5;
    cursor: not-allowed;
    text-decoration: line-through;
}
.tracking-map {
    height: 300px;
    background: linear-gradient(135deg, #FF4900 0%, #FF6B35 100%);
    border-radius: 0.5rem;
    position: relative;
}
.map-placeholder {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
}
.rider-marker {
    position: absolute;
    bottom: 40%;
    left: 60%;
    background: #28a745;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.tracking-timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
    border-left: 2px solid #dee2e6;
    padding-left: 1.5rem;
    margin-left: -30px;
}
.timeline-item:last-child {
    border-left: 2px solid transparent;
    padding-bottom: 0;
}
.timeline-item.completed {
    border-left-color: #28a745;
}
.timeline-item.active {
    border-left-color: #28a745;
}
.timeline-marker {
    position: absolute;
    left: -11px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid white;
}
.timeline-item.completed .timeline-marker {
    background: #28a745;
}
.timeline-item.active .timeline-marker {
    background: var(--bs-primary);
}
.timeline-marker.pulse {
    animation: pulse 1.5s infinite;
}
</style>
