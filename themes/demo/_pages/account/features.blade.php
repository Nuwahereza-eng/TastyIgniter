---
title: 'TastyIgniter Premium Features'
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
                                <!-- Premium Header Banner -->
                                <div class="premium-feature-banner mb-4">
                                    <div class="premium-banner-content">
                                        <div class="premium-badge">
                                            <i class="fa fa-crown"></i>
                                            <span>Premium Feature</span>
                                        </div>
                                        <h3><i class="fa fa-users me-2"></i>Group Orders</h3>
                                        <p class="mb-0">Order together with friends, family, or colleagues. Everyone adds their items, split the bill or pay together!</p>
                                    </div>
                                    <div class="premium-banner-decoration">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                                
                                <!-- Action Cards -->
                                <div class="row g-4 mb-5">
                                    <div class="col-md-6">
                                        <div class="premium-action-card create-card" onclick="showCreateGroupModal()">
                                            <div class="action-card-glow"></div>
                                            <div class="action-card-content">
                                                <div class="action-icon-wrap">
                                                    <div class="action-icon">
                                                        <i class="fa fa-plus"></i>
                                                    </div>
                                                    <div class="action-icon-ring"></div>
                                                </div>
                                                <h4>Create Group Order</h4>
                                                <p>Start a new group and invite friends to add their items</p>
                                                <div class="action-features">
                                                    <span><i class="fa fa-check-circle"></i> Share invite code</span>
                                                    <span><i class="fa fa-check-circle"></i> Set deadline</span>
                                                    <span><i class="fa fa-check-circle"></i> Split bills</span>
                                                </div>
                                                <div class="action-btn">
                                                    <span>Create New Group</span>
                                                    <i class="fa fa-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="premium-action-card join-card" onclick="showJoinGroupModal()">
                                            <div class="action-card-glow"></div>
                                            <div class="action-card-content">
                                                <div class="action-icon-wrap">
                                                    <div class="action-icon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                    <div class="action-icon-ring"></div>
                                                </div>
                                                <h4>Join Group Order</h4>
                                                <p>Enter a code shared by a friend to join their order</p>
                                                <div class="action-features">
                                                    <span><i class="fa fa-check-circle"></i> Add your items</span>
                                                    <span><i class="fa fa-check-circle"></i> See group total</span>
                                                    <span><i class="fa fa-check-circle"></i> Track together</span>
                                                </div>
                                                <div class="action-btn join">
                                                    <span>Join with Code</span>
                                                    <i class="fa fa-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Group Orders -->
                                <div id="activeGroupOrders" class="active-groups-section">
                                    <div class="active-groups-header">
                                        <div class="d-flex align-items-center">
                                            <div class="active-groups-icon">
                                                <i class="fa fa-layer-group"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">Your Active Group Orders</h5>
                                                <small class="text-muted">Manage your ongoing group orders</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="groupOrdersList" class="position-relative">
                                        <span class="refresh-icon" onclick="loadGroupOrders()" title="Refresh">
                                            <i class="fa fa-sync-alt"></i>
                                        </span>
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
                                    <button type="button" class="btn btn-warning btn-lg" onclick="proceedToMenuWithSchedule()">
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
                                            <div class="subscription-badge bg-warning">Most Popular</div>
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
                                                <button class="btn btn-warning w-100" onclick="selectPlan('premium')">
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
                                
                                <!-- Subscription History -->
                                <div class="card mt-4" id="subscriptionHistory">
                                    <div class="card-header" style="background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%); color: white;">
                                        <h6 class="mb-0"><i class="fa fa-history me-2"></i>Subscription History</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="subscriptionHistoryList">
                                            <div class="text-center py-4">
                                                <span class="spinner-border spinner-border-sm" style="color: #ff4900;"></span>
                                                <span class="ms-2">Loading history...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ORDER TRACKING TAB -->
                        <div class="tab-pane fade" id="order-tracking" role="tabpanel">
                            <div class="tracking-container">
                                <div class="tracking-header">
                                    <h4><i class="fa fa-motorcycle me-2" style="color: #ff4900;"></i>Track Your Order</h4>
                                    <p class="tracking-subtitle mb-0">Real-time updates on your order status and delivery location</p>
                                </div>
                                
                                <!-- Order Search -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="form-label">Enter Order Number</label>
                                                <input type="text" class="form-control form-control-lg" id="trackingOrderId" placeholder="e.g., UGA-00042, 42, or your order hash">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button class="btn btn-lg w-100" style="background-color: #ff4900; border-color: #ff4900; color: white;" onclick="trackOrder()">
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
                                                                <p class="mb-0 mt-1 small" style="color: #ff4900;">
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
                <h5 class="modal-title"><i class="fa fa-users text-warning me-2"></i>Create Group Order</h5>
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
                <button type="button" class="btn btn-warning" onclick="createGroupOrder()">
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

<!-- Split Bill Modal -->
<div class="modal fade" id="splitBillModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-orange text-white">
                <h5 class="modal-title">
                    <i class="fa fa-cut me-2"></i>Split Bill - Payment Breakdown
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Group Order Summary -->
                <div class="split-bill-summary mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0" id="splitBillGroupName">Group Order</h6>
                        <span class="badge bg-success" id="splitBillStatus">Ready</span>
                    </div>
                    <div class="total-amount-box text-center p-4 rounded bg-light mb-3">
                        <small class="text-muted d-block">Total Group Amount</small>
                        <h2 class="mb-0 text-warning" id="splitBillTotalAmount">UGX 0</h2>
                    </div>
                </div>
                
                <!-- Split Method Selection -->
                <div class="split-method-section mb-4">
                    <h6 class="mb-3"><i class="fa fa-cut me-2 text-warning"></i>How to Split?</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="split-option-card" onclick="selectSplitMethod('equal')">
                                <input type="radio" name="splitMethod" value="equal" id="splitEqual" class="d-none">
                                <div class="split-option-icon"><i class="fa fa-equals"></i></div>
                                <h6>Split Equally</h6>
                                <p class="small text-muted mb-0">Everyone pays the same amount</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="split-option-card active" onclick="selectSplitMethod('individual')">
                                <input type="radio" name="splitMethod" value="individual" id="splitIndividual" class="d-none" checked>
                                <div class="split-option-icon"><i class="fa fa-receipt"></i></div>
                                <h6>Pay Your Items</h6>
                                <p class="small text-muted mb-0">Each pays for their own items</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="split-option-card" onclick="selectSplitMethod('host')">
                                <input type="radio" name="splitMethod" value="host" id="splitHost" class="d-none">
                                <div class="split-option-icon"><i class="fa fa-crown"></i></div>
                                <h6>Host Pays All</h6>
                                <p class="small text-muted mb-0">You cover everyone's orders</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Participant Breakdown -->
                <div class="participant-breakdown-section mb-4">
                    <h6 class="mb-3"><i class="fa fa-money-bill-wave me-2 text-success"></i>Who Pays What</h6>
                    <p class="text-muted small mb-3">Each participant's share based on the split method selected above</p>
                    <div id="splitBillParticipants" class="participant-list">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>
                
                <!-- Payment Collection Status -->
                <div class="payment-status-section" id="paymentStatusSection" style="display: none;">
                    <h6 class="mb-3"><i class="fa fa-check-circle me-2 text-success"></i>Payment Status</h6>
                    <div id="paymentStatusList">
                        <!-- Will show who has paid -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="sendPaymentRequests" onclick="sendPaymentRequests()">
                    <i class="fa fa-paper-plane me-2"></i>Send Payment Requests
                </button>
                <button type="button" class="btn btn-success" id="proceedToPayBtn" onclick="proceedToGroupPayment()">
                    <i class="fa fa-credit-card me-2"></i>Pay Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Confirmation Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%); border-radius: 16px 16px 0 0; padding: 1.25rem 1.5rem;">
                <h5 class="modal-title text-white" id="subscriptionModalLabel"><i class="fa fa-crown me-2"></i>Subscribe to Meal Plan</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeSubscriptionModalManual()" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem; background: #ffffff;">
                <div class="text-center mb-4 p-3" style="background: #fff8f5; border-radius: 12px; border: 1px solid #ffe5d9;">
                    <div class="subscription-selected-plan mb-3">
                        <span id="selectedPlanBadge" class="badge fs-5 px-4 py-2" style="background: linear-gradient(135deg, #ff4900, #ff6b35); color: white;">Premium Plan</span>
                    </div>
                    <h3 id="selectedPlanPrice" class="fw-bold" style="color: #ff4900;">UGX 280,000/week</h3>
                    <p class="text-muted small mb-0">Billed weekly. Cancel anytime.</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef; height: 100%;">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fa fa-map-marker-alt me-2" style="color: #212529;"></i>Delivery Address</label>
                                <textarea class="form-control" rows="2" id="subscriptionAddress" placeholder="e.g., Ntinda Shopping Center, Kampala" style="border-radius: 8px; background: white;"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fa fa-clock me-2" style="color: #ff4900;"></i>Preferred Delivery Time</label>
                                <select class="form-select" id="subscriptionTime" style="border-radius: 8px; background: white;">
                                    <option value="12:00">12:00 PM - Lunch</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="18:00">6:00 PM - Dinner</option>
                                    <option value="19:00">7:00 PM</option>
                                    <option value="20:00">8:00 PM</option>
                                </select>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-bold"><i class="fa fa-calendar me-2" style="color: #ff4900;"></i>Start Date</label>
                                <input type="date" class="form-control" id="subscriptionStartDate" min="" style="border-radius: 8px; background: white;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-3" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef; height: 100%;">
                            <label class="form-label fw-bold"><i class="fa fa-credit-card me-2" style="color: #ff4900;"></i>Payment Method</label>
                            
                            <!-- Mobile Money Options -->
                            <div class="payment-methods">
                                <div class="form-check payment-option mb-2 p-3 rounded" style="background: #fff8f5; cursor: pointer; border: 2px solid #ff4900;" onclick="selectPayment('mtn')">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="payMTN" value="mtn" checked>
                                    <label class="form-check-label d-flex align-items-center" for="payMTN" style="cursor: pointer;">
                                        <img src="/images/payments/mtn.png" alt="MTN" style="height: 28px; width: auto; margin-right: 10px;">
                                        MTN Mobile Money
                                    </label>
                                </div>
                                
                                <div class="form-check payment-option mb-2 p-3 rounded" style="background: white; cursor: pointer; border: 1px solid #e9ecef;" onclick="selectPayment('airtel')">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="payAirtel" value="airtel">
                                    <label class="form-check-label d-flex align-items-center" for="payAirtel" style="cursor: pointer;">
                                        <img src="/images/payments/airtel.png" alt="Airtel" style="height: 28px; width: auto; margin-right: 10px;">
                                        Airtel Money
                                    </label>
                                </div>
                                
                                    <div class="form-check payment-option mb-2 p-3 rounded" style="background: white; cursor: pointer; border: 1px solid #e9ecef;" onclick="selectPayment('card')">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="payCard" value="card">
                                    <label class="form-check-label d-flex align-items-center" for="payCard" style="cursor: pointer;">
                                        <i class="fa fa-credit-card me-2" style="color: #ff4900; font-size: 1.2rem;"></i>
                                        Visa / Mastercard
                                    </label>
                                </div>
                            </div>

                            <!-- Marz Mobile Money UGX 200,000 limit notice -->
                            <div id="momoLimitNotice" class="alert alert-warning d-none mt-2 mb-0 py-2 px-3"
                                 style="font-size: 0.85rem; border-radius: 8px;">
                                <i class="fa fa-exclamation-triangle me-1"></i>
                                Mobile Money is capped at <strong>UGX 200,000</strong> per transaction.
                                Please use <a href="#" onclick="selectPayment('card');return false;" class="alert-link">Visa / Mastercard</a>
                                instead, or contact support to raise your MoMo limit.
                            </div>
                            
                            <!-- Phone Number for Mobile Money -->
                            <div class="mt-3" id="mobileMoneyInput">
                                <label class="form-label">Mobile Money Number</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #ff4900; color: white; border: none;">+256</span>
                                    <input type="tel" class="form-control" id="mobileMoneyNumber" placeholder="7XX XXX XXX" maxlength="9" style="border-radius: 0 8px 8px 0; background: white;">
                                </div>
                                <small class="text-muted">You will receive a payment prompt on this number</small>
                            </div>
                            
                            <!-- Card Input (hidden by default) -->
                            <div class="mt-3 d-none" id="cardInput">
                                <div class="mb-2">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" placeholder="4242 4242 4242 4242" style="border-radius: 8px; background: white;">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Expiry</label>
                                        <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY" style="border-radius: 8px; background: white;">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cardCvv" placeholder="123" style="border-radius: 8px; background: white;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert mt-4 mb-0" style="background: #fff8f5; border: 1px solid #fff8f5; border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-shield-alt fa-2x me-3" style="color: #ff4900;"></i>
                        <div>
                            <strong style="color: #ff4900;">Secure Payment</strong>
                            <p class="mb-0 small" style="color: #cc3a00;">Your payment is protected. Cancel anytime with no fees.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 16px 16px; border-top: 1px solid #e9ecef; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline-secondary" onclick="closeSubscriptionModalManual()">Cancel</button>
                <button type="button" class="btn btn-lg" style="background: linear-gradient(135deg, #ff4900, #ff4900); border: none; color: white;" onclick="confirmSubscription()" id="subscribeBtn">
                    <i class="fa fa-check-circle me-2"></i>Subscribe Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Payment method selection
function selectPayment(method) {
    document.querySelector(`input[value="${method}"]`).checked = true;
    
    if (method === 'card') {
        document.getElementById('mobileMoneyInput').classList.add('d-none');
        document.getElementById('cardInput').classList.remove('d-none');
    } else {
        document.getElementById('mobileMoneyInput').classList.remove('d-none');
        document.getElementById('cardInput').classList.add('d-none');
    }

    // Warn when the selected plan exceeds the Marz Mobile-Money cap.
    updateMomoLimitNotice();
}

/**
 * Show or hide an inline notice about the Marz Mobile Money UGX 200,000 cap.
 * If the user picked MTN/Airtel but the plan price is above the cap we make
 * it visible and suggest paying by card.
 */
const MARZ_MOMO_LIMIT = 200000;
function updateMomoLimitNotice() {
    const notice = document.getElementById('momoLimitNotice');
    if (!notice) return;
    const method = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    const priceText = document.getElementById('selectedPlanPrice')?.textContent || '';
    const m = priceText.match(/[\d,]+/);
    const amount = m ? parseInt(m[0].replace(/,/g, ''), 10) : 0;
    const isMomo = method === 'mtn' || method === 'airtel';
    if (isMomo && amount > MARZ_MOMO_LIMIT) {
        notice.classList.remove('d-none');
    } else {
        notice.classList.add('d-none');
    }
}

// Set minimum date for subscription start
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('subscriptionStartDate');
    if (startDateInput) {
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        startDateInput.value = today;
    }
    
    // Clean up any orphaned modal backdrops on page load
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Add event listener to clean up when modal is hidden
    const subscriptionModal = document.getElementById('subscriptionModal');
    if (subscriptionModal) {
        subscriptionModal.addEventListener('hidden.bs.modal', function () {
            // Remove any orphaned backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
});

// Close modal helper function
function closeSubscriptionModal() {
    const modalElement = document.getElementById('subscriptionModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    // Force cleanup
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}
</script>

<script>
// Set customer info from TastyIgniter session
@php
    $customer = null;
    if (class_exists('\Igniter\User\Facades\Auth')) {
        $customer = \Igniter\User\Facades\Auth::customer();
    }
@endphp
window.ugaeatsCustomerEmail = '{{ $customer ? $customer->email : "" }}';
window.ugaeatsCustomerName = '{{ $customer ? $customer->first_name . " " . $customer->last_name : "" }}';
window.ugaeatsCustomerId = {{ $customer ? (int) $customer->customer_id : 0 }};
window.ugaeatsCustomerPhone = '{{ $customer && isset($customer->telephone) ? $customer->telephone : "" }}';
</script>

<script>
// ============ API CONFIGURATION ============
const API_BASE = '/ajax';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const MENU_PAGE_URL = '{{ page_url("local.menus") }}';

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
    loadSubscriptionHistory();

    // Poll group orders every 15s while the user is on this page so paid-status
    // chips and "Place Order" buttons appear without manual refresh as soon as
    // other participants finish paying.
    setInterval(() => {
        if (document.hidden) return;
        const panel = document.getElementById('groupOrdersList');
        if (panel && panel.offsetParent !== null) {
            loadGroupOrders({ silent: true });
        }
    }, 15000);
    
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

async function loadGroupOrders(opts = {}) {
    const silent = !!opts.silent;
    const container = document.getElementById('groupOrdersList');
    const hasContent = container && container.querySelector('.group-order-card, .empty-groups');
    // Only show the spinner on first load; silent refreshes (polling) should
    // not blank the panel — that's what caused the visible page "blink".
    if (!silent && !hasContent) {
        container.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-warning"></span> <span class="ms-2">Loading your group orders...</span></div>';
    }

    try {
        const response = await groupOrderCall('/');
        const hosted = response.hosted || [];
        const participating = response.participating || [];

        if (hosted.length === 0 && participating.length === 0) {
            container.innerHTML = `
                <div class="empty-groups card border-0 bg-light">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fa fa-users fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted">No Active Group Orders</h6>
                        <p class="text-muted small mb-0">Create a new group order or join an existing one using a code!</p>
                    </div>
                </div>
            `;
            return;
        }
        
        let html = '';
        
        // Hosted Groups Section
        if (hosted.length > 0) {
            html += `
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning text-dark me-2"><i class="fa fa-crown"></i></span>
                        <h6 class="mb-0 text-muted">Groups You're Hosting (${hosted.length})</h6>
                    </div>
                    <div class="row g-3">
            `;
            hosted.forEach(group => {
                html += renderGroupCard(group, true);
            });
            html += `</div></div>`;
        }
        
        // Participating Groups Section
        if (participating.length > 0) {
            html += `
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning me-2"><i class="fa fa-user-friends"></i></span>
                        <h6 class="mb-0 text-muted">Groups You've Joined (${participating.length})</h6>
                    </div>
                    <div class="row g-3">
            `;
            participating.forEach(group => {
                html += renderGroupCard(group, false);
            });
            html += `</div></div>`;
        }
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Load group orders error:', error);
        container.innerHTML = `
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fa fa-exclamation-circle fa-3x text-warning"></i>
                    </div>
                    <h6 class="text-muted">Could not load group orders</h6>
                    <p class="text-muted small mb-3">Please try again or create a new group order.</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadGroupOrders()">
                        <i class="fa fa-sync-alt me-1"></i>Try Again
                    </button>
                </div>
            </div>
        `;
    }
}

function renderGroupCard(group, isHost) {
    const deadline = group.deadline_at ? new Date(group.deadline_at) : null;
    const isExpired = deadline && deadline < new Date();
    const participantCount = group.participants?.length || 0;
    const totalAmount = Number(group.total_amount || 0);

    // Streamlined payment-status snapshot used throughout the card.
    const participants = group.participants || [];
    const shareCount = participants.filter(p => Number(p.share_amount || 0) > 0).length;
    const paidCount  = participants.filter(p => p.has_paid).length;
    const allPaid    = shareCount > 0 && paidCount >= shareCount;
    const me         = participants.find(p => Number(p.customer_id) === Number(window.ugaeatsCustomerId || 0));
    const myShare    = Number(me?.share_amount || 0);
    const myPaid     = !!(me && me.has_paid);
    const showPayBtn = !!me && !myPaid && myShare > 0 && ['open', 'closed'].includes(group.status);

    const statusColors = {
        'open': 'success',
        'closed': 'primary',
        'ordered': 'secondary',
        'finalized': 'primary',
        'completed': 'secondary',
        'cancelled': 'danger'
    };
    const statusColor = isExpired ? 'danger' : (statusColors[group.status] || 'secondary');
    
    return `
        <div class="col-12 col-md-6">
            <div class="card group-order-card h-100 ${isExpired ? 'border-danger' : ''}">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="group-avatar me-3">
                                <i class="fa fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">${group.title}</h6>
                                <small class="text-muted">
                                    ${isHost ? '<i class="fa fa-crown text-warning me-1"></i>You are the host' : '<i class="fa fa-user text-warning me-1"></i>Member'}
                                </small>
                            </div>
                        </div>
                        <span class="badge bg-${statusColor} px-3 py-2">
                            ${isExpired ? '<i class="fa fa-clock me-1"></i>Closed' : 
                              (group.status === 'open' ? '<i class="fa fa-door-open me-1"></i>Open' : 
                               group.status.charAt(0).toUpperCase() + group.status.slice(1))}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Invite Code -->
                    <div class="invite-code-box mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block mb-1">Share Code</small>
                                <code class="fs-5 fw-bold text-warning">${formatInviteCode(group.invite_code)}</code>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="copyGroupCode('${group.invite_code}')" title="Copy code">
                                    <i class="fa fa-copy"></i>
                                </button>
                                <a href="https://wa.me/?text=Join%20my%20UgaEats%20group%20order!%20Use%20code:%20${group.invite_code}" 
                                   class="btn btn-success btn-sm" target="_blank" title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="stat-box text-center p-2 rounded bg-light">
                                <i class="fa fa-users text-warning mb-1"></i>
                                <div class="fw-bold">${participantCount}</div>
                                <small class="text-muted">${participantCount === 1 ? 'Person' : 'People'}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box text-center p-2 rounded bg-light">
                                <i class="fa fa-clock text-warning mb-1"></i>
                                <div class="fw-bold small">${deadline ? formatDeadline(deadline) : 'None'}</div>
                                <small class="text-muted">Deadline</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box text-center p-2 rounded bg-light">
                                <i class="fa fa-money-bill-wave text-success mb-1"></i>
                                <div class="fw-bold">${formatCurrency(totalAmount)}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Participants -->
                    ${participantCount > 0 ? `
                        <div class="participants-section mb-3">
                            <small class="text-muted d-block mb-2"><i class="fa fa-users me-1"></i>Participants
                                <span class="ms-2 text-success">(${paidCount}/${shareCount} paid)</span>
                            </small>
                            <div class="d-flex flex-wrap gap-2">
                                ${(group.participants || []).map(p => {
                                    const paid = !!p.has_paid;
                                    const share = Number(p.share_amount || 0);
                                    const ready = p.status === 'ready';
                                    return `
                                    <div class="participant-chip ${ready ? 'ready' : ''}" title="${paid ? 'Paid ' + formatCurrency(share) : (share > 0 ? 'Owes ' + formatCurrency(share) : 'Not in split')}">
                                        <span class="participant-avatar">${p.name.charAt(0).toUpperCase()}</span>
                                        <span class="participant-name">${p.name}${p.is_host ? ' <i class="fa fa-crown text-warning ms-1"></i>' : ''}</span>
                                        ${paid ? '<i class="fa fa-check-circle text-success ms-1" title="Paid"></i>' :
                                          (share > 0 ? '<i class="fa fa-hourglass-half text-warning ms-1" title="Owes ' + formatCurrency(share) + '"></i>' :
                                          (ready ? '<i class="fa fa-check text-muted ms-1"></i>' : ''))}
                                    </div>
                                `;}).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
                
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex align-items-center gap-2 flex-nowrap flex-wrap">
                        ${!isExpired && group.status === 'open' ? `
                            <button class="btn btn-warning btn-sm" onclick="goToMenuForGroup(${group.id})">
                                <i class="fa fa-plus me-1"></i>Add Items
                            </button>
                        ` : ''}
                        ${isHost && group.status === 'open' && totalAmount > 0 ? `
                            <button class="btn btn-warning btn-sm" onclick="openSplitBillModal(${group.id})" title="Split payment between participants">
                                <i class="fa fa-cut me-1"></i>Split Bill
                            </button>
                        ` : ''}
                        ${showPayBtn ? `
                            <button class="btn btn-success btn-sm" onclick="payMyShareModal(${group.id})" title="Pay your share via Marz (MTN, Airtel or Card)">
                                <i class="fa fa-mobile-alt me-1"></i>Pay My Share (${formatCurrency(myShare)})
                            </button>
                        ` : (myPaid ? `
                            <span class="badge bg-success px-3 py-2"><i class="fa fa-check-circle me-1"></i>Your share is paid</span>
                        ` : '')}
                        ${isHost && group.status === 'closed' && allPaid ? `
                            <button class="btn btn-outline-success btn-sm" onclick="proceedToGroupCheckout(${group.id})">
                                <i class="fa fa-receipt me-1"></i>Place Order
                            </button>
                        ` : ''}
                        <div class="flex-grow-1"></div>
                        ${isHost ? `
                            <button class="btn btn-outline-danger btn-sm" onclick="cancelGroupOrder(${group.id})" title="Cancel group order">
                                <i class="fa fa-trash"></i>
                            </button>
                        ` : `
                            <button class="btn btn-outline-secondary btn-sm" onclick="leaveGroupOrder(${group.id})" title="Leave group">
                                <i class="fa fa-sign-out-alt"></i>
                            </button>
                        `}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function formatDeadline(date) {
    const now = new Date();
    const diff = date - now;
    
    if (diff < 0) return 'Expired';
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    
    if (hours > 24) {
        return date.toLocaleDateString('en-UG', { month: 'short', day: 'numeric' });
    } else if (hours > 0) {
        return `${hours}h ${mins}m`;
    } else {
        return `${mins} mins`;
    }
}

function formatCurrency(amount) {
    if (amount >= 1000000) {
        return (amount / 1000000).toFixed(1) + 'M';
    } else if (amount >= 1000) {
        return Math.round(amount / 1000) + 'K';
    }
    return amount.toLocaleString();
}

function formatInviteCode(code) {
    if (!code) return '';
    // Format as XXXX-XXXX
    if (code.length === 8) {
        return code.substring(0, 4) + '-' + code.substring(4);
    }
    return code;
}

// Store current group order ID for various operations
let currentGroupOrderId = null;
let currentGroupOrderData = null;

async function setActiveGroupOrder(groupOrderId) {
    try {
        await groupOrderCall('/set-active', 'POST', { group_order_id: groupOrderId });
        // Store in localStorage for menu page to know about group order
        localStorage.setItem('ugaeats_active_group_order', JSON.stringify({
            id: groupOrderId,
            return_url: window.location.href + '#group-orders',
            timestamp: Date.now()
        }));
    } catch (error) {
        console.error('Error setting active group order:', error);
    }
}

// Navigate to menu for group order - handles the location properly
async function goToMenuForGroup(groupOrderId) {
    // Set active group order first
    await setActiveGroupOrder(groupOrderId);
    
    // Use the Blade-generated menu URL with group_order param
    const separator = MENU_PAGE_URL.includes('?') ? '&' : '?';
    window.location.href = MENU_PAGE_URL + separator + 'group_order=' + groupOrderId;
}

// Open Split Bill Modal
async function openSplitBillModal(groupOrderId) {
    currentGroupOrderId = groupOrderId;
    
    try {
        // Get combined cart data
        const response = await groupOrderCall(`/${groupOrderId}/combined-cart`);
        
        if (!response.success) {
            throw new Error(response.error || 'Failed to load group order');
        }
        
        currentGroupOrderData = response;
        
        // Populate modal
        document.getElementById('splitBillGroupName').textContent = response.group_order?.title || 'Group Order';
        document.getElementById('splitBillTotalAmount').textContent = 'UGX ' + formatNumberWithCommas(response.total || 0);
        
        // Render participants
        renderSplitBillParticipants(response.participant_breakdown, response.group_order?.split_method || 'individual');
        
        // Select the current split method
        selectSplitMethod(response.group_order?.split_method || 'individual');
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('splitBillModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error opening Split Bill modal:', error);
        alert('Failed to load group order details: ' + error.message);
    }
}

function selectSplitMethod(method) {
    // Update UI
    document.querySelectorAll('.split-option-card').forEach(card => {
        card.classList.remove('active');
    });
    const selectedCard = document.querySelector(`.split-option-card input[value="${method}"]`)?.closest('.split-option-card');
    if (selectedCard) {
        selectedCard.classList.add('active');
        selectedCard.querySelector('input').checked = true;
    }
    
    // Update participant amounts based on method
    if (currentGroupOrderData) {
        updateSplitAmounts(method, currentGroupOrderData);
    }
}

function updateSplitAmounts(method, data) {
    const participants = data.participant_breakdown || [];
    const total = parseFloat(data.total) || 0;
    const participantCount = participants.length;
    
    participants.forEach((p, index) => {
        const amountEl = document.getElementById(`participant-amount-${index}`);
        if (!amountEl) return;
        
        let amount = 0;
        switch (method) {
            case 'equal':
                amount = total / participantCount;
                break;
            case 'individual':
                amount = parseFloat(p.subtotal) || 0;
                break;
            case 'host':
                amount = p.is_host ? total : 0;
                break;
        }
        
        amountEl.textContent = 'UGX ' + formatNumberWithCommas(Math.round(amount));
        amountEl.dataset.amount = amount;
    });
}

function renderSplitBillParticipants(participants, splitMethod) {
    const container = document.getElementById('splitBillParticipants');
    
    if (!participants || participants.length === 0) {
        container.innerHTML = '<p class="text-muted text-center py-3">No participants yet</p>';
        return;
    }
    
    const total = participants.reduce((sum, p) => sum + (parseFloat(p.subtotal) || 0), 0);
    const participantCount = participants.length;
    
    let html = '';
    participants.forEach((p, index) => {
        const itemCount = (p.items || []).length;
        let amount = parseFloat(p.subtotal) || 0;
        
        // Calculate based on split method
        if (splitMethod === 'equal') {
            amount = total / participantCount;
        } else if (splitMethod === 'host' && !p.is_host) {
            amount = 0;
        } else if (splitMethod === 'host' && p.is_host) {
            amount = total;
        }
        
        html += `
            <div class="participant-payment-card mb-2">
                <div class="d-flex align-items-center">
                    <div class="participant-avatar-lg me-3">
                        ${p.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">
                            ${p.name}
                            ${p.is_host ? '<span class="badge bg-warning text-dark ms-1"><i class="fa fa-crown"></i></span>' : ''}
                        </h6>
                        <small class="text-muted">${itemCount} item${itemCount !== 1 ? 's' : ''}</small>
                    </div>
                    <div class="text-end">
                        <span class="participant-amount fw-bold text-warning" id="participant-amount-${index}" data-amount="${amount}">
                            UGX ${formatNumberWithCommas(Math.round(amount))}
                        </span>
                        <div class="payment-status-badge mt-1" id="participant-status-${index}">
                            <span class="badge bg-secondary">Pending</span>
                        </div>
                    </div>
                </div>
                ${itemCount > 0 ? `
                    <div class="participant-items mt-2">
                        <small class="text-muted">
                            ${(p.items || []).slice(0, 3).map(item => `${item.quantity}x ${item.name}`).join(', ')}
                            ${itemCount > 3 ? `... +${itemCount - 3} more` : ''}
                        </small>
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function formatNumberWithCommas(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

async function sendPaymentRequests() {
    const btn = document.getElementById('sendPaymentRequests');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    try {
        // Persist the chosen split server-side so every participant sees the
        // same share amounts when they open their account.
        const splitMethod = document.querySelector('input[name="splitMethod"]:checked')?.value || 'individual';

        // Hit the summary endpoint — this recomputes & persists per-participant shares.
        await fetch(`/ajax/group-orders/${currentGroupOrderId}/payment-summary?split_method=${encodeURIComponent(splitMethod)}`, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
        });

        // Reflect "requested" badges in the modal.
        const participants = currentGroupOrderData?.participant_breakdown || [];
        participants.forEach((p, i) => {
            if (p.is_host) return;
            const statusEl = document.getElementById(`participant-status-${i}`);
            if (statusEl) statusEl.innerHTML = '<span class="badge bg-warning text-dark">Requested</span>';
        });
        const section = document.getElementById('paymentStatusSection');
        if (section) section.style.display = 'block';

        alert('Each participant now has a “Pay My Share” button on their group order card. Tell them to open Account → Premium Features.');
    } catch (e) {
        console.error('sendPaymentRequests error', e);
        alert('Could not send payment requests. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-paper-plane me-2"></i>Resend Requests';
    }
}

/**
 * Open the per-user share payment picker (MTN / Airtel / Card via Marz).
 * Used by the "Pay My Share" button on every group order card.
 */
async function payMyShareModal(groupOrderId) {
    let summary;
    try {
        const r = await fetch(`/ajax/group-orders/${groupOrderId}/payment-summary`, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
        });
        summary = await r.json();
    } catch (e) {
        alert('Could not load your share. Please try again.');
        return;
    }
    if (!summary.success || !summary.me) {
        alert('You are not a participant in this group order.');
        return;
    }
    if (summary.me.has_paid) {
        alert('You have already paid your share. Thank you!');
        return;
    }
    if (!summary.me.share_amount || summary.me.share_amount <= 0) {
        alert('There is no amount due for your share.');
        return;
    }

    const amount = Math.round(summary.me.share_amount);
    const formatted = 'UGX ' + amount.toLocaleString('en-US');

    let overlay = document.getElementById('shareOverlay');
    if (overlay) overlay.remove();
    overlay = document.createElement('div');
    overlay.id = 'shareOverlay';
    overlay.innerHTML = `
      <div style="position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;">
        <div style="background:#ffffff;border-radius:12px;max-width:480px;width:100%;padding:24px;box-shadow:0 12px 40px rgba(0,0,0,.25);">
          <h5 class="mb-1"><i class="fa fa-mobile-alt me-2 text-primary"></i>Pay your share</h5>
          <p class="text-muted mb-3">You owe <strong>${formatted}</strong> for <em>${summary.group_order.name || ('group order #' + summary.group_order.id)}</em>.</p>
          <div class="mb-2"><strong>Choose a payment method</strong></div>
          <div class="d-grid gap-2 mb-3">
            <button type="button" class="btn btn-outline-warning text-start share-pm" data-pm="mtn">
              <img src="/images/payments/mtn.png" style="height:22px;vertical-align:middle" class="me-2"> MTN Mobile Money
            </button>
            <button type="button" class="btn btn-outline-danger text-start share-pm" data-pm="airtel">
              <img src="/images/payments/airtel.png" style="height:22px;vertical-align:middle" class="me-2"> Airtel Money
            </button>
            <button type="button" class="btn btn-outline-primary text-start share-pm" data-pm="card">
              <i class="fa fa-credit-card me-2"></i>Visa / Mastercard
            </button>
          </div>
          <div id="sharePhoneWrap" style="display:none" class="mb-3">
            <label class="form-label">Mobile Money number</label>
            <input id="sharePhone" type="tel" class="form-control" placeholder="07XX XXX XXX" value="${(window.ugaeatsCustomerPhone||'').replace(/^\\+?256/, '0')}">
          </div>
          <div class="d-flex gap-2">
            <button type="button" id="sharePay" class="btn btn-primary flex-grow-1" disabled>
              <i class="fa fa-lock me-2"></i>Pay ${formatted}
            </button>
            <button type="button" id="shareCancel" class="btn btn-link">Cancel</button>
          </div>
          <div id="shareStatusMsg" class="small text-muted mt-2"></div>
        </div>
      </div>`;
    document.body.appendChild(overlay);

    let chosen = null;
    overlay.querySelectorAll('.share-pm').forEach(b => {
        b.addEventListener('click', () => {
            chosen = b.dataset.pm;
            overlay.querySelectorAll('.share-pm').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            const isMomo = chosen === 'mtn' || chosen === 'airtel';
            document.getElementById('sharePhoneWrap').style.display = isMomo ? 'block' : 'none';
            document.getElementById('sharePay').disabled = false;
        });
    });

    document.getElementById('shareCancel').addEventListener('click', () => overlay.remove());

    document.getElementById('sharePay').addEventListener('click', async () => {
        if (!chosen) return;
        const phone = (document.getElementById('sharePhone')?.value || '').trim();
        const isMomo = chosen === 'mtn' || chosen === 'airtel';
        if (isMomo && !phone) { alert('Please enter your Mobile Money number'); return; }

        const payBtn = document.getElementById('sharePay');
        payBtn.disabled = true;
        payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        try {
            const r = await fetch(`/ajax/group-orders/${groupOrderId}/pay-share`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ payment_method: chosen, phone: isMomo ? phone : null }),
            });
            const j = await r.json();
            if (!j.success) { alert(j.error || j.message || 'Payment failed'); payBtn.disabled = false; payBtn.innerHTML = `<i class="fa fa-lock me-2"></i>Pay ${formatted}`; return; }

            if (j.simulated || j.auto_paid) {
                document.getElementById('shareStatusMsg').innerHTML = '<span class="text-success"><i class="fa fa-check-circle me-1"></i>Share paid.</span>';
                setTimeout(() => { overlay.remove(); loadGroupOrders(); }, 800);
                return;
            }

            if (j.redirect_url) {
                window.location.href = j.redirect_url;
                return;
            }

            // Mobile money pending → poll until success or failure.
            document.getElementById('shareStatusMsg').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + (j.message || 'Waiting for confirmation…');
            pollShareStatus(groupOrderId, summary.me.participant_id, overlay);
        } catch (e) {
            console.error(e);
            alert('Payment failed: ' + e.message);
            payBtn.disabled = false;
            payBtn.innerHTML = `<i class="fa fa-lock me-2"></i>Pay ${formatted}`;
        }
    });
}

function pollShareStatus(groupOrderId, participantId, overlay) {
    let attempts = 0;
    const maxAttempts = 60;
    const tick = () => {
        attempts++;
        fetch(`/ajax/group-orders/${groupOrderId}/share-status/${participantId}`, {
            credentials: 'same-origin', headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(j => {
            if (j.success && j.has_paid) {
                document.getElementById('shareStatusMsg').innerHTML = '<span class="text-success"><i class="fa fa-check-circle me-1"></i>Share paid. Thanks!</span>';
                setTimeout(() => { overlay.remove(); loadGroupOrders(); }, 1000);
                return;
            }
            const ps = (j.payment_status || '').toLowerCase();
            if (ps === 'failed' || ps === 'cancelled') {
                document.getElementById('shareStatusMsg').innerHTML = '<span class="text-danger"><i class="fa fa-times-circle me-1"></i>Payment ' + ps + '.</span>';
                const payBtn = document.getElementById('sharePay');
                if (payBtn) { payBtn.disabled = false; payBtn.innerHTML = '<i class="fa fa-lock me-2"></i>Try again'; }
                return;
            }
            if (attempts < maxAttempts) setTimeout(tick, 2500);
            else document.getElementById('shareStatusMsg').textContent = 'Still waiting… you can close this and check again later.';
        })
        .catch(() => { if (attempts < maxAttempts) setTimeout(tick, 3000); });
    };
    setTimeout(tick, 2500);
}

async function proceedToGroupPayment() {
    // Persist the chosen split method, then open the host's share picker.
    const splitMethod = document.querySelector('input[name="splitMethod"]:checked')?.value || 'individual';
    try {
        await fetch(`/ajax/group-orders/${currentGroupOrderId}/payment-summary?split_method=${encodeURIComponent(splitMethod)}`, {
            credentials: 'same-origin', headers: { 'Accept': 'application/json' },
        });
    } catch (e) { /* non-fatal */ }

    bootstrap.Modal.getInstance(document.getElementById('splitBillModal'))?.hide();
    payMyShareModal(currentGroupOrderId);
}

async function finalizeGroupOrder(groupOrderId) {
    // First, get the group order details
    try {
        const response = await groupOrderCall(`/${groupOrderId}/combined-cart`);
        
        if (!response.success) {
            throw new Error(response.error || 'Failed to load group order');
        }
        
        const participants = response.participant_breakdown || [];
        const total = parseFloat(response.total) || 0;
        
        // Check if there are any items
        const totalItems = participants.reduce((sum, p) => sum + (p.items?.length || 0), 0);
        
        if (totalItems === 0) {
            alert('No items in this group order yet. Please add items before finalizing.');
            return;
        }
        
        // Open Split Bill modal instead of direct finalize
        openSplitBillModal(groupOrderId);
        
    } catch (error) {
        console.error('Error finalizing group order:', error);
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

// Proceed directly to checkout for a finalized group order
async function proceedToGroupCheckout(groupOrderId) {
    if (!confirm('Place the group order now? Every participant has paid their share.')) return;
    try {
        const resp = await fetch(`/ajax/group-orders/${groupOrderId}/place-order`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        const data = await resp.json();
        if (!resp.ok || !data.success) {
            alert(data.error || data.message || 'Could not place the order.');
            return;
        }
        alert(data.message || 'Order placed!');
        try { localStorage.removeItem('ugaeats_active_group_order'); } catch (e) {}
        loadGroupOrders();
    } catch (e) {
        alert('Network error placing order. Please retry.');
    }
}

// Sync cart items to group order when on menu page
async function syncCartToGroupOrder() {
    const activeGroup = localStorage.getItem('ugaeats_active_group_order');
    if (!activeGroup) return false;
    
    try {
        const groupData = JSON.parse(activeGroup);
        
        // Check if still valid (within 4 hours)
        if (Date.now() - groupData.timestamp > 4 * 60 * 60 * 1000) {
            localStorage.removeItem('ugaeats_active_group_order');
            return false;
        }
        
        // Call sync endpoint
        const response = await groupOrderCall('/sync-cart', 'POST');
        return response.success;
    } catch (error) {
        console.error('Error syncing cart to group order:', error);
        return false;
    }
}

// Check for active group order and show notification
function checkActiveGroupOrder() {
    const activeGroup = localStorage.getItem('ugaeats_active_group_order');
    if (!activeGroup) return;
    
    try {
        const groupData = JSON.parse(activeGroup);
        
        // Check if still valid
        if (Date.now() - groupData.timestamp > 4 * 60 * 60 * 1000) {
            localStorage.removeItem('ugaeats_active_group_order');
            return;
        }
        
        // Show notification that user is in a group order
        showGroupOrderNotification(groupData);
    } catch (error) {
        console.error('Error checking active group order:', error);
    }
}

function showGroupOrderNotification(groupData) {
    // Create floating notification
    const notification = document.createElement('div');
    notification.className = 'group-order-notification';
    notification.innerHTML = `
        <div class="group-notification-content">
            <i class="fa fa-users text-white me-2"></i>
            <span>You're adding to a group order</span>
            <a href="${groupData.return_url || '/account/features#group-orders'}" class="btn btn-sm btn-light ms-3">
                <i class="fa fa-arrow-left me-1"></i>Back to Group
            </a>
            <button class="btn btn-sm btn-outline-light ms-2" onclick="exitGroupOrderMode()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(notification);
}

function exitGroupOrderMode() {
    localStorage.removeItem('ugaeats_active_group_order');
    document.querySelector('.group-order-notification')?.remove();
    
    // Clear active group order on server
    groupOrderCall('/clear-active', 'POST').catch(() => {});
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
                    badge: plan.slug === 'basic' ? 'bg-secondary' : (plan.slug === 'premium' ? 'bg-warning' : 'bg-success')
                };
            });
        }
    } catch (error) {
        console.log('Using default subscription plans');
        subscriptionPlans = {
            basic: { id: 1, name: 'Basic Plan', price: 'UGX 150,000/week', badge: 'bg-secondary' },
            premium: { id: 3, name: 'Premium Plan', price: 'UGX 280,000/week', badge: 'bg-warning' },
            family: { id: 2, name: 'Family Plan', price: 'UGX 450,000/week', badge: 'bg-success' }
        };
    }
}

function selectPlan(plan) {
    console.log('selectPlan called with:', plan);
    
    const plans = subscriptionPlans.basic ? subscriptionPlans : {
        basic: { id: 1, name: 'Basic Plan', price: 'UGX 150,000/week', badge: 'bg-secondary' },
        premium: { id: 3, name: 'Premium Plan', price: 'UGX 280,000/week', badge: 'bg-warning' },
        family: { id: 2, name: 'Family Plan', price: 'UGX 450,000/week', badge: 'bg-success' }
    };
    
    const selected = plans[plan];
    document.getElementById('selectedPlanBadge').textContent = selected.name;
    document.getElementById('selectedPlanBadge').className = `badge ${selected.badge} fs-5 px-4 py-2`;
    document.getElementById('selectedPlanPrice').textContent = selected.price;
    document.getElementById('selectedPlanBadge').dataset.planId = selected.id || plan;
    document.getElementById('selectedPlanBadge').dataset.planSlug = plan;
    // Re-evaluate the Marz MoMo limit notice whenever the plan changes.
    if (typeof updateMomoLimitNotice === 'function') updateMomoLimitNotice();
    
    // Get modal element
    const modalElement = document.getElementById('subscriptionModal');
    const modalDialog = modalElement.querySelector('.modal-dialog');
    
    // Manual modal show - bypass Bootstrap issues
    // First clean up
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'subscriptionModalBackdrop';
    backdrop.style.zIndex = '1055';
    document.body.appendChild(backdrop);
    
    // Show modal
    modalElement.style.display = 'block';
    modalElement.style.zIndex = '1060';
    modalElement.classList.add('show');
    modalElement.removeAttribute('aria-hidden');
    modalElement.setAttribute('aria-modal', 'true');
    modalElement.setAttribute('role', 'dialog');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = '0px';
    
    // Focus the first input in the modal
    setTimeout(() => {
        const firstInput = modalElement.querySelector('textarea, input, select');
        if (firstInput) firstInput.focus();
    }, 100);
    
    // Add click listener on modal element to close only when clicking outside modal-dialog
    modalElement.onclick = function(e) {
        // Only close if clicking directly on the modal overlay (not on modal-dialog or its children)
        if (e.target === modalElement) {
            closeSubscriptionModalManual();
        }
    };
    
    // Prevent clicks inside modal-dialog from propagating to modal overlay
    if (modalDialog) {
        modalDialog.onclick = function(e) {
            e.stopPropagation();
        };
    }
    
    // Add ESC key listener
    document.addEventListener('keydown', handleEscKey);
    
    console.log('Modal should now be visible');
}

function handleEscKey(e) {
    if (e.key === 'Escape') {
        closeSubscriptionModalManual();
    }
}

// Close subscription modal manually
function closeSubscriptionModalManual() {
    const modalElement = document.getElementById('subscriptionModal');
    const modalDialog = modalElement?.querySelector('.modal-dialog');
    const backdrop = document.getElementById('subscriptionModalBackdrop');
    
    if (modalElement) {
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
        modalElement.removeAttribute('aria-modal');
        modalElement.setAttribute('aria-hidden', 'true');
        // Clear the onclick handler
        modalElement.onclick = null;
    }
    
    if (modalDialog) {
        modalDialog.onclick = null;
    }
    
    if (backdrop) {
        backdrop.remove();
    }
    
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Remove ESC key listener
    document.removeEventListener('keydown', handleEscKey);
}

async function confirmSubscription() {
    const address = document.getElementById('subscriptionAddress').value;
    const time = document.getElementById('subscriptionTime').value;
    const startDate = document.getElementById('subscriptionStartDate')?.value;
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    const mobileMoneyNumber = document.getElementById('mobileMoneyNumber')?.value;
    
    if (!address) {
        alert('Please enter your delivery address');
        return;
    }
    
    if ((paymentMethod === 'mtn' || paymentMethod === 'airtel') && !mobileMoneyNumber) {
        alert('Please enter your Mobile Money number');
        return;
    }
    
    if (paymentMethod === 'card') {
        // Card payments will redirect to payment page
    }
    
    const planSlug = document.getElementById('selectedPlanBadge').dataset.planSlug;
    const planIdRaw = document.getElementById('selectedPlanBadge').dataset.planId;
    const planId = parseInt(planIdRaw, 10);
    const planPrice = document.getElementById('selectedPlanPrice').textContent;
    
    // Validate plan_id
    if (!planId || isNaN(planId)) {
        alert('Please select a valid plan');
        console.error('Invalid plan_id:', planIdRaw);
        return;
    }
    
    // Extract amount from price (e.g., "UGX 280,000/week" -> 280000)
    const amountMatch = planPrice.match(/[\d,]+/);
    const amount = amountMatch ? parseInt(amountMatch[0].replace(/,/g, '')) : 0;
    
    if (!amount) {
        alert('Invalid plan price');
        return;
    }

    // Pre-flight: Marz mobile-money cap is UGX 200,000. Stop before hitting
    // the API so the user gets a clear, actionable message.
    if ((paymentMethod === 'mtn' || paymentMethod === 'airtel') && amount > MARZ_MOMO_LIMIT) {
        alert('Mobile Money payments are limited to UGX ' + MARZ_MOMO_LIMIT.toLocaleString()
            + ' per transaction.\n\nPlease pay with Visa / Mastercard instead, or contact support to raise your MoMo limit.');
        selectPayment('card');
        return;
    }
    
    const btn = document.getElementById('subscribeBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Payment...';
    
    try {
        // Get customer email from session or use default
        const customerEmail = window.ugaeatsCustomerEmail || 'customer@ugaeats.com';
        const customerName = window.ugaeatsCustomerName || 'Customer';
        
        // Try to create the subscription record (may fail if already subscribed)
        let subscriptionId = null;
        try {
            const subscriptionResponse = await apiCall('/subscriptions/subscribe', 'POST', {
                plan_id: planId,
                auto_renew: true,
                delivery_address: address,
                delivery_time: time,
                start_date: startDate,
            });
            subscriptionId = subscriptionResponse.subscription?.id || null;
        } catch (subError) {
            console.warn('Subscription creation note:', subError.message);
            // Continue with payment anyway - subscription will be created after successful payment
        }
        
        // Now process payment
        const paymentResponse = await fetch('/ajax/payments/initialize', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                amount: amount,
                email: customerEmail,
                name: customerName,
                phone: mobileMoneyNumber ? '256' + mobileMoneyNumber : null,
                type: 'subscription',
                reference_id: subscriptionId,
                plan_id: planId,  // Include plan ID for subscription creation
                payment_method: paymentMethod,
            }),
        });
        
        const paymentData = await paymentResponse.json();
        console.log('Payment response:', paymentData);
        console.log('Payment response status:', paymentResponse.status);
        
        // Check for TastyIgniter flash message errors (session expired, etc.)
        if (paymentData.X_IGNITER_FLASH_MESSAGES) {
            const flashMessages = paymentData.X_IGNITER_FLASH_MESSAGES;
            const errorMsg = flashMessages.find(m => m.class === 'danger');
            if (errorMsg) {
                if (errorMsg.text && errorMsg.text.includes('session')) {
                    throw new Error('Session expired. Please reload the page and try again.');
                }
                throw new Error(errorMsg.text || 'Server error');
            }
        }
        
        // Check for HTTP errors first
        if (!paymentResponse.ok) {
            // Handle validation errors
            if (paymentData.errors) {
                const errorMessages = Object.values(paymentData.errors).flat().join(', ');
                throw new Error('Validation error: ' + errorMessages);
            }
            throw new Error(paymentData.message || paymentData.error || 'Server error');
        }
        
        if (paymentData.success) {
            if (paymentData.redirect_url) {
                // Card / Marz hosted checkout
                window.location.href = paymentData.redirect_url;
                return;
            }

            const methodName = paymentMethod === 'mtn' ? 'MTN Mobile Money' :
                               paymentMethod === 'airtel' ? 'Airtel Money' :
                               paymentMethod === 'marz' ? 'Marz Wallet' : 'Card';

            // Pending Mobile Money (Marz USSD push). Poll the payment record
            // until it resolves so the user sees a real confirmation instead
            // of a misleading "success" toast.
            if (paymentData.pending && paymentData.tx_ref) {
                closeSubscriptionModalManual();
                pollSubscriptionPayment(paymentData.tx_ref, {
                    plan: document.getElementById('selectedPlanBadge').textContent,
                    price: planPrice,
                    paymentMethod: methodName,
                    message: paymentData.message,
                });
                return;
            }

            // Demo / instantly-successful payment
            closeSubscriptionModalManual();
            showPaymentSuccess({
                txRef: paymentData.tx_ref,
                plan: document.getElementById('selectedPlanBadge').textContent,
                price: planPrice,
                paymentMethod: methodName,
                message: paymentData.message,
            });

            updateSubscriptionStatus();
            loadSubscriptionHistory();
        } else {
            throw new Error(paymentData.message || paymentData.error || 'Payment processing failed');
        }
    } catch (error) {
        console.error('Payment error:', error);
        alert(error.message || 'Payment failed. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check-circle me-2"></i>Subscribe Now';
    }
}

function pollSubscriptionPayment(txRef, ctx) {
    let overlay = document.getElementById('subPollOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'subPollOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:1080;background:rgba(0,0,0,0.55);display:flex;align-items:center;justify-content:center;';
        overlay.innerHTML = `
            <div style="background:#ffffff;border-radius:14px;padding:28px 32px;max-width:380px;width:90%;text-align:center;box-shadow:0 18px 40px rgba(0,0,0,0.25);">
                <div class="spinner-border text-warning mb-3" role="status"></div>
                <h5 class="mb-2">Confirm on your phone</h5>
                <p class="text-muted mb-2" style="font-size:0.92rem;">${(ctx.message || 'Enter your Mobile Money PIN to authorise the payment.').replace(/</g,'&lt;')}</p>
                <p class="small text-muted mb-3">Ref: <code>${txRef}</code></p>
                <button id="subPollCancel" class="btn btn-sm btn-outline-secondary">Cancel</button>
            </div>`;
        document.body.appendChild(overlay);
        document.getElementById('subPollCancel').onclick = () => overlay.remove();
    }

    let attempts = 0;
    const maxAttempts = 60;

    const tick = async () => {
        if (!document.body.contains(overlay)) return;
        attempts++;
        try {
            const resp = await fetch(`/ajax/payments/verify?tx_ref=${encodeURIComponent(txRef)}`, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' },
            });
            const data = await resp.json();
            if (data.success && data.status === 'successful') {
                overlay.remove();
                showPaymentSuccess({
                    txRef,
                    plan: ctx.plan,
                    price: ctx.price,
                    paymentMethod: ctx.paymentMethod,
                    message: 'Payment confirmed.',
                });
                updateSubscriptionStatus();
                loadSubscriptionHistory();
                return;
            }
            if (['failed', 'cancelled'].includes((data.status || '').toLowerCase())) {
                overlay.remove();
                alert(`Payment ${data.status}. Please try again.`);
                return;
            }
        } catch (e) { /* network blip */ }

        if (attempts >= maxAttempts) {
            overlay.remove();
            alert('Still waiting for confirmation. We will update your subscription once Marz confirms the payment.');
            return;
        }
        setTimeout(tick, 2500);
    };
    setTimeout(tick, 2500);
}

function showPaymentSuccess(data) {
    const modal = document.createElement('div');
    modal.className = 'modal fade show';
    modal.style.display = 'block';
    modal.style.zIndex = '1070';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa fa-check-circle me-2"></i>Payment Successful!</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fa fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="mb-3">Your subscription is now active!</h4>
                    <div class="text-start mx-auto" style="max-width: 300px;">
                        <p class="mb-2"><strong>Plan:</strong> ${data.plan}</p>
                        <p class="mb-2"><strong>Amount:</strong> ${data.price}</p>
                        <p class="mb-2"><strong>Payment:</strong> ${data.paymentMethod}</p>
                        <p class="mb-2"><strong>Reference:</strong> <code>${data.txRef}</code></p>
                    </div>
                    <p class="text-muted small mt-3">${data.message || 'You will receive a confirmation SMS shortly.'}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="this.closest('.modal').remove(); document.querySelector('.modal-backdrop-success')?.remove();">
                        <i class="fa fa-thumbs-up me-2"></i>Great!
                    </button>
                </div>
            </div>
        </div>
    `;
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show modal-backdrop-success';
    backdrop.style.zIndex = '1065';
    
    document.body.appendChild(backdrop);
    document.body.appendChild(modal);
}

async function updateSubscriptionStatus() {
    const container = document.getElementById('currentSubscription');
    
    try {
        const response = await apiCall('/subscriptions/current');
        
        if (response.has_subscription && response.subscription) {
            const sub = response.subscription;
            const pct = Math.max(0, Math.min(100, Number(sub.meals_used_percentage || 0)));
            container.innerHTML = `
                <div class="card-body p-3 active-sub-card">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="active-sub-icon me-3">
                                <i class="fa fa-utensils"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="mb-0 fw-bold">${sub.plan.name}</h6>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fa fa-circle me-1" style="font-size:.5rem;"></i>Active
                                    </span>
                                </div>
                                <small class="text-muted">Auto-renew: ${sub.auto_renew ? 'On' : 'Off'}</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More actions">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button type="button" class="dropdown-item" onclick="pauseSubscription()">
                                        <i class="fa fa-pause text-warning me-2"></i>Pause subscription
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" onclick="cancelSubscription()">
                                        <i class="fa fa-times me-2"></i>Cancel subscription
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <div class="active-sub-stat">
                                <div class="fw-bold">${sub.meals_remaining}</div>
                                <small class="text-muted">Meals left</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="active-sub-stat">
                                <div class="fw-bold">${sub.days_remaining}</div>
                                <small class="text-muted">Days left</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: ${pct}%"></div>
                    </div>
                    <small class="text-muted d-block mt-1">${pct}% of meals used</small>
                    ${(sub.served_orders && sub.served_orders.length) ? `
                        <hr class="my-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <small class="text-muted fw-bold"><i class="fa fa-utensils me-1"></i>Recently served</small>
                            <small class="text-muted">${sub.served_orders.length} ${sub.served_orders.length === 1 ? 'order' : 'orders'}</small>
                        </div>
                        <ul class="list-unstyled mb-0 small served-orders-list">
                            ${sub.served_orders.slice(0,5).map(o => `
                                <li class="d-flex justify-content-between py-1 border-bottom">
                                    <a href="/track-order?order=${encodeURIComponent(o.order_id)}" class="text-decoration-none text-dark">
                                        <i class="fa fa-check-circle text-success me-1"></i>${o.formatted_id}
                                    </a>
                                    <span class="text-muted">${new Date(o.used_at).toLocaleDateString('en-UG', { month: 'short', day: 'numeric' })}</span>
                                </li>
                            `).join('')}
                        </ul>
                    ` : ''}
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
                <div class="card-body p-3 active-sub-card">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="active-sub-icon me-3">
                                <i class="fa fa-utensils"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="mb-0 fw-bold">${sub.plan}</h6>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fa fa-circle me-1" style="font-size:.5rem;"></i>Active
                                    </span>
                                </div>
                                <small class="text-muted">${sub.price} · Delivery ${sub.deliveryTime}</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More actions">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button type="button" class="dropdown-item text-danger" onclick="cancelSubscription()">
                                        <i class="fa fa-times me-2"></i>Cancel subscription
                                    </button>
                                </li>
                            </ul>
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
        loadSubscriptionHistory();
    } catch (error) {
        // Fallback
        localStorage.removeItem('ugaeats_subscription');
        location.reload();
    }
}

// ============ SUBSCRIPTION HISTORY ============
async function loadSubscriptionHistory() {
    console.log('[Subscriptions] Loading subscription history...');
    const container = document.getElementById('subscriptionHistoryList');
    if (!container) {
        console.log('[Subscriptions] Container not found');
        return;
    }
    
    // Check if user is logged in
    if (!window.ugaeatsCustomerEmail) {
        console.log('[Subscriptions] User not logged in');
        container.innerHTML = `
            <div class="text-center py-4" style="background: #f8f9fa;">
                <i class="fa fa-sign-in-alt fa-2x mb-2" style="color: #ff4900;"></i>
                <p class="mb-0 text-muted">Please log in to view your subscription history</p>
            </div>
        `;
        return;
    }
    
    try {
        console.log('[Subscriptions] Calling API...');
        const response = await apiCall('/subscriptions/history');
        console.log('[Subscriptions] API Response:', response);
        
        // Handle both 'history' and 'subscriptions' response keys
        const history = response.history || response.subscriptions || [];
        
        if (response.success && history.length > 0) {
            let html = '<div class="table-responsive"><table class="table table-hover mb-0">';
            html += `
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="border: none; padding: 12px;">Plan</th>
                        <th style="border: none; padding: 12px;">Period</th>
                        <th style="border: none; padding: 12px;">Amount</th>
                        <th style="border: none; padding: 12px;">Status</th>
                        <th style="border: none; padding: 12px;">Meals Used</th>
                    </tr>
                </thead>
                <tbody>
            `;
            
            history.forEach(sub => {
                const statusBadge = getStatusBadge(sub.status);
                const startDate = sub.start_date ? new Date(sub.start_date).toLocaleDateString('en-UG', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                const endDate = sub.end_date ? new Date(sub.end_date).toLocaleDateString('en-UG', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Ongoing';
                const planName = sub.plan?.name || sub.plan_name || 'Meal Plan';
                const amount = sub.plan?.price || sub.amount || 0;
                const mealsUsed = sub.meals_used || 0;
                const mealsTotal = sub.plan?.meals_per_week || sub.meals_total || 10;
                const mealsPercentage = mealsTotal > 0 ? Math.round((mealsUsed / mealsTotal) * 100) : 0;
                
                html += `
                    <tr>
                        <td style="padding: 12px; vertical-align: middle;">
                            <span class="fw-bold">${planName}</span>
                        </td>
                        <td style="padding: 12px; vertical-align: middle;">
                            <small>${startDate} - ${endDate}</small>
                        </td>
                        <td style="padding: 12px; vertical-align: middle;">
                            <span style="color: #ff4900; font-weight: 600;">UGX ${Number(amount).toLocaleString()}</span>
                        </td>
                        <td style="padding: 12px; vertical-align: middle;">
                            ${statusBadge}
                        </td>
                        <td style="padding: 12px; vertical-align: middle;">
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 8px; background: #e9ecef; border-radius: 4px;">
                                    <div class="progress-bar" style="width: ${mealsPercentage}%; background: linear-gradient(135deg, #ff4900, #ff6b35);"></div>
                                </div>
                                <small class="ms-2 text-muted">${mealsUsed}/${mealsTotal}</small>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';

            // Append a separate "Payments" list so the user sees every
            // subscription-related transaction (renewals, simulated, failed),
            // not just the canonical Subscription rows.
            const payments = response.payments || [];
            if (payments.length) {
                html += `
                    <div class="mt-4">
                        <h6 class="mb-2"><i class="fa fa-receipt me-1 text-warning"></i>Payments
                            <small class="text-muted">(${payments.length})</small>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead style="background:#f8f9fa;">
                                    <tr>
                                        <th style="border:none;padding:8px;">Date</th>
                                        <th style="border:none;padding:8px;">Reference</th>
                                        <th style="border:none;padding:8px;">Method</th>
                                        <th style="border:none;padding:8px;">Amount</th>
                                        <th style="border:none;padding:8px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ${payments.map(p => {
                                    const when = p.created_at ? new Date(p.created_at).toLocaleString('en-UG', { day:'numeric', month:'short', hour:'2-digit', minute:'2-digit' }) : '-';
                                    const ok = p.status === 'successful';
                                    const fail = ['failed','cancelled'].includes((p.status||'').toLowerCase());
                                    const colour = ok ? '#28a745' : (fail ? '#dc3545' : '#6c757d');
                                    return `
                                        <tr>
                                            <td style="padding:8px;"><small>${when}</small></td>
                                            <td style="padding:8px;"><code class="small">${p.tx_ref}</code></td>
                                            <td style="padding:8px;"><small>${(p.payment_method||'-').toUpperCase()}</small></td>
                                            <td style="padding:8px;"><small>UGX ${Number(p.amount).toLocaleString()}</small></td>
                                            <td style="padding:8px;">
                                                <span class="badge" style="background:${colour};">${p.status}</span>
                                                ${p.simulated ? '<span class="badge ms-1" style="background:#6c757d;">demo</span>' : ''}
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            container.innerHTML = html;
            console.log('[Subscriptions] Rendered', history.length, 'subscriptions');
        } else {
            console.log('[Subscriptions] No subscriptions found');
            container.innerHTML = `
                <div class="text-center py-4" style="background: #f8f9fa;">
                    <i class="fa fa-inbox fa-2x mb-2" style="color: #dee2e6;"></i>
                    <p class="text-muted mb-0">No subscription history yet</p>
                    <small class="text-muted">Subscribe to a meal plan to see your history here</small>
                </div>
            `;
        }
    } catch (error) {
        console.error('[Subscriptions] Error loading history:', error);
        container.innerHTML = `
            <div class="text-center py-4" style="background: #f8f9fa;">
                <i class="fa fa-exclamation-triangle fa-2x mb-2" style="color: #ff4900;"></i>
                <p class="mb-0 text-muted">Could not load subscription history</p>
                <small class="text-muted">${error.message || 'Please try again later'}</small>
            </div>
        `;
    }
}

function getStatusBadge(status) {
    const statusMap = {
        'active': '<span class="badge" style="background: #ff4900;">Active</span>',
        'paused': '<span class="badge" style="background: #6c757d;">Paused</span>',
        'cancelled': '<span class="badge" style="background: #6c757d;">Cancelled</span>',
        'expired': '<span class="badge" style="background: #6c757d;">Expired</span>',
        'completed': '<span class="badge" style="background: #ff4900;">Completed</span>',
    };
    return statusMap[status] || '<span class="badge" style="background: #6c757d;">' + (status || 'Unknown') + '</span>';
}

// ============ ORDER TRACKING FUNCTIONS ============
// Accepts any of: bare numeric order_id, UGA-NNNNN, UGA-YYYY-NNNNN, or the order hash.
function buildTrackPayload(raw) {
    const trimmed = (raw || '').trim();
    if (!trimmed) return null;
    const upper = trimmed.toUpperCase();
    if (upper.startsWith('UGA')) {
        const groups = upper.match(/\d+/g);
        if (groups && groups.length) {
            const num = parseInt(groups[groups.length - 1], 10);
            if (!isNaN(num) && num > 0) return { order_id: num };
        }
    }
    if (/^\d+$/.test(trimmed)) return { order_id: parseInt(trimmed, 10) };
    if (/^[A-Za-z0-9_-]{8,}$/.test(trimmed)) return { order_hash: trimmed };
    return null;
}

async function trackOrder() {
    const raw = document.getElementById('trackingOrderId').value.trim();

    if (!raw) {
        alert('Please enter an order number');
        return;
    }

    const payload = buildTrackPayload(raw);
    if (!payload) {
        alert('Invalid order number. Enter the number shown on your receipt (e.g. UGA-00042, 42, or the order hash from your confirmation email).');
        return;
    }

    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Tracking...';

    try {
        const response = await apiCall('/tracking/track', 'POST', payload);

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
/* ============================================================
   UGAEATS UNIFIED PALETTE
   Use these tokens for any new styles. Existing inline styles
   have been normalized to the same hex values below.
   ============================================================ */
:root {
    /* Brand (orange) */
    --uga-brand:            #ff4900; /* primary orange */
    --uga-brand-accent:     #ff6b35; /* lighter orange (gradients) */
    --uga-brand-dark:       #cc3a00; /* darker orange (hover / emphasis) */
    --uga-brand-tint:       #fff8f5; /* very pale orange bg (success / info states) */
    --uga-brand-tint-bd:    #ffe5d9; /* pale orange border */

    /* Black / white / gray only */
    --uga-black:            #212529;
    --uga-ink:              #212529; /* alias */
    --uga-gray-700:         #495057;
    --uga-muted:            #6c757d;
    --uga-muted-2:          #adb5bd;
    --uga-line:             #dee2e6;
    --uga-bg-soft:          #e9ecef;
    --uga-bg-softer:        #f8f9fa;
    --uga-white:            #ffffff;
}

/* Additional inline styles for this page */

/* Premium Feature Banner */
.premium-feature-banner {
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 50%, #ff6b35 100%);
    border-radius: 20px;
    padding: 30px 35px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(255, 73, 0, 0.3);
}
.premium-feature-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.premium-banner-content {
    position: relative;
    z-index: 1;
}
.premium-banner-content h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 8px;
}
.premium-banner-content p {
    opacity: 0.95;
    font-size: 1rem;
    max-width: 500px;
}
.premium-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 12px;
    border: 1px solid rgba(255,255,255,0.3);
}
.premium-badge i {
    color: #ff4900;
}
.premium-banner-decoration {
    position: absolute;
    right: 30px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 120px;
    opacity: 0.1;
}

/* Premium Action Cards */
.premium-action-card {
    position: relative;
    background: white;
    border-radius: 20px;
    padding: 35px 30px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 2px solid #f8f9fa;
    overflow: hidden;
    height: 100%;
}
.premium-action-card:hover {
    transform: translateY(-8px);
    border-color: transparent;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}
.premium-action-card.create-card:hover {
    box-shadow: 0 20px 60px rgba(255, 73, 0, 0.25);
}
.premium-action-card.join-card:hover {
    box-shadow: 0 20px 60px rgba(40, 167, 69, 0.25);
}
.action-card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff4900, #ff6b35);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.join-card .action-card-glow {
    background: linear-gradient(90deg, #ff4900, #ff4900);
}
.premium-action-card:hover .action-card-glow {
    opacity: 1;
}
.action-card-content {
    position: relative;
    z-index: 1;
}
.action-icon-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}
.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
}
.join-card .action-icon {
    background: linear-gradient(135deg, #ff4900 0%, #ff4900 100%);
}
.premium-action-card:hover .action-icon {
    transform: scale(1.1);
}
.action-icon-ring {
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    border: 2px dashed rgba(255, 73, 0, 0.3);
    border-radius: 24px;
    animation: spin 20s linear infinite;
}
.join-card .action-icon-ring {
    border-color: rgba(40, 167, 69, 0.3);
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.premium-action-card h4 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 10px;
}
.premium-action-card > .action-card-content > p {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 20px;
    line-height: 1.5;
}
.action-features {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 25px;
}
.action-features span {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
    font-size: 0.9rem;
}
.action-features i {
    color: #ff4900;
    font-size: 0.85rem;
}
.join-card .action-features i {
    color: #ff4900;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 73, 0, 0.3);
}
.action-btn.join {
    background: linear-gradient(135deg, #ff4900 0%, #ff4900 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}
.premium-action-card:hover .action-btn {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(255, 73, 0, 0.4);
}
.premium-action-card:hover .action-btn.join {
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}
.action-btn i {
    transition: transform 0.3s ease;
}
.premium-action-card:hover .action-btn i {
    transform: translateX(3px);
}

/* Active Groups Header */
.active-groups-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 16px;
    border: 1px solid #e9ecef;
}
.active-groups-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    margin-right: 12px;
}
.active-groups-header h5 {
    font-weight: 600;
    color: #212529;
    font-size: 0.95rem;
}

/* Refresh Icon */
.refresh-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    cursor: pointer;
    transition: color 0.2s ease;
    z-index: 10;
}
.refresh-icon:hover {
    color: #ff4900;
}
.refresh-icon i {
    font-size: 0.9rem;
}

/* Active Groups Section */
.active-groups-section {
    position: relative;
}
#groupOrdersList {
    min-height: 100px;
}

/* Group Order Cards */
.group-order-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
}
.group-order-card:hover {
    border-color: #ff4900;
    box-shadow: 0 8px 25px rgba(255, 73, 0, 0.15);
    transform: translateY(-3px);
}
.group-order-card .card-header {
    border-bottom: 1px solid #f8f9fa;
    padding: 0.75rem 1rem !important;
}
.group-order-card .card-body {
    padding: 0.9rem 1rem;
}
.group-order-card .card-footer {
    border-top: 1px solid #f8f9fa;
    padding: 0.6rem 1rem !important;
}
.group-order-card .group-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 1rem;
}
.group-order-card .invite-code-box {
    padding: 8px 12px;
}
.group-order-card .stat-box {
    padding: 6px 4px !important;
}
.group-order-card .stat-box i {
    font-size: 0.95rem;
}

.group-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.invite-code-box {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 12px 15px;
}

.stat-box {
    border: 1px solid #e9ecef;
}
.stat-box i {
    display: block;
    font-size: 1.1rem;
}

.participant-chip {
    display: inline-flex;
    align-items: center;
    background: #f8f9fa;
    padding: 5px 10px 5px 5px;
    border-radius: 20px;
    font-size: 0.85rem;
}
.participant-chip.ready {
    background: #fff8f5;
}
.participant-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ff4900;
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    margin-right: 6px;
}
.participant-name {
    font-weight: 500;
}

/* Active Subscription compact card */
.active-sub-card { background: #fff; }
.active-sub-icon {
    width: 42px; height: 42px; border-radius: 10px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.active-sub-stat {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 10px;
    text-align: center;
    line-height: 1.2;
}
.active-sub-stat .fw-bold { font-size: 1rem; }

/* Track Order header */
.tracking-header {
    background: linear-gradient(135deg, #fff5f0 0%, #ffffff 100%);
    border: 1px solid #ffe0d2;
    border-radius: 12px;
    padding: 18px 22px;
}
.tracking-header h4 {
    margin-bottom: 6px;
    color: #212529;
    font-weight: 700;
}
.tracking-subtitle {
    color: #495057;
    font-size: 0.95rem;
    line-height: 1.4;
}

/* Split Bill (Split Payment) Styles */
.bg-gradient-orange {
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
}
.total-amount-box {
    border: 2px dashed #ff4900;
}
.split-option-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}
.split-option-card:hover {
    border-color: #ff4900;
    transform: translateY(-3px);
}
.split-option-card.active {
    border-color: #ff4900;
    background: rgba(255, 73, 0, 0.05);
    box-shadow: 0 4px 15px rgba(255, 73, 0, 0.2);
}
.split-option-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 12px;
}
.split-option-card h6 {
    font-weight: 600;
    margin-bottom: 5px;
}
.participant-payment-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    border: 1px solid #e9ecef;
}
.participant-avatar-lg {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
}
.participant-amount {
    font-size: 1.1rem;
}
.participant-items {
    padding-left: 58px;
}

/* Group Order Notification Bar */
.group-order-notification {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    padding: 12px 20px;
    z-index: 1050;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
}
.group-notification-content {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 500;
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
    border-color: #ff4900;
    background: rgba(255, 73, 0, 0.1);
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
    background: #ff4900;
    color: white;
    border-color: #ff4900;
}
.time-slot.unavailable {
    opacity: 0.5;
    cursor: not-allowed;
    text-decoration: line-through;
}
.tracking-map {
    height: 300px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
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
    background: #ff4900;
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
    border-left-color: #ff4900;
}
.timeline-item.active {
    border-left-color: #ff4900;
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
    background: #ff4900;
}
.timeline-item.active .timeline-marker {
    background: #ff4900;
}
.timeline-marker.pulse {
    animation: pulse 1.5s infinite;
}
</style>
