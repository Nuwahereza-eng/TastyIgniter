---
title: My Subscriptions
permalink: /account/subscriptions
layout: default
security: customer
---

<link rel="stylesheet" href="{{ asset('themes/demo/assets/css/features.css') }}">
<div class="container">
    <div class="row py-4">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu"/>
        </div>
        
        <div class="col-sm-10">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%); color: white;">
                    <h4 class="mb-0"><i class="fa fa-crown me-2"></i>My Subscriptions</h4>
                </div>
                <div class="card-body">
                    <!-- Active Subscription -->
                    <div id="activeSubscriptionSection">
                        <h5 class="mb-3"><i class="fa fa-check-circle me-2" style="color: #ff4900;"></i>Active Subscription</h5>
                        <div id="activeSubscriptionContent">
                            <div class="text-center py-4">
                                <span class="spinner-border spinner-border-sm" style="color: #ff4900;"></span>
                                <span class="ms-2">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Subscription History -->
                    <div id="subscriptionHistorySection">
                        <h5 class="mb-3"><i class="fa fa-history me-2" style="color: #ff4900;"></i>Subscription History</h5>
                        <div id="subscriptionHistoryContent">
                            <div class="text-center py-4">
                                <span class="spinner-border spinner-border-sm" style="color: #ff4900;"></span>
                                <span class="ms-2">Loading history...</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Subscribe to New Plan -->
                    <div class="text-center">
                        <p class="text-muted mb-3">Want to subscribe to a meal plan or change your current plan?</p>
                        <a href="{{ page_url('account.features') }}#subscriptions" class="btn btn-lg" style="background: #ff4900; color: white;">
                            <i class="fa fa-crown me-2"></i>View Meal Plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $customer = \Igniter\User\Facades\Auth::customer();
@endphp

<style>
.subscription-status-card {
    border: 2px solid #ff4900;
    border-radius: 12px;
    padding: 20px;
    background: linear-gradient(135deg, #fff8f5 0%, #ffffff 100%);
}

.subscription-status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
}

.status-active {
    background: #ff4900;
    color: white;
}

.status-paused {
    background: #6c757d;
    color: white;
}

.status-cancelled {
    background: #6c757d;
    color: white;
}

.meals-progress {
    height: 10px;
    border-radius: 5px;
    background: #e9ecef;
    overflow: hidden;
}

.meals-progress-bar {
    height: 100%;
    background: #ff4900;
    border-radius: 5px;
    transition: width 0.3s ease;
}

.action-btn {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    transform: translateY(-1px);
}

.btn-pause {
    background: #6c757d;
    color: white;
}

.btn-resume {
    background: #ff4900;
    color: white;
}

.btn-cancel {
    background: transparent;
    color: #6c757d;
    border: 1px solid #6c757d;
}
</style>

<script>
const API_BASE = '/ajax';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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
    const json = await response.json();
    
    if (!response.ok) {
        throw new Error(json.error || json.message || 'API request failed');
    }
    
    return json;
}

document.addEventListener('DOMContentLoaded', function() {
    loadActiveSubscription();
    loadSubscriptionHistory();
});

async function loadActiveSubscription() {
    const container = document.getElementById('activeSubscriptionContent');
    
    try {
        const response = await apiCall('/subscriptions/current');
        
        if (response.success && response.has_subscription && response.subscription) {
            const sub = response.subscription;
            const plan = sub.plan || {};
            const mealsUsed = (plan.meals_per_period || 0) - (sub.meals_remaining || 0);
            const mealsTotal = plan.meals_per_period || 10;
            const mealsPercentage = mealsTotal > 0 ? Math.round((mealsUsed / mealsTotal) * 100) : 0;
            
            const expiresAt = sub.expires_at ? new Date(sub.expires_at).toLocaleDateString('en-UG', { 
                day: 'numeric', month: 'short', year: 'numeric' 
            }) : 'N/A';
            
            const statusClass = sub.status === 'active' ? 'status-active' : 
                               sub.status === 'paused' ? 'status-paused' : 'status-cancelled';
            
            container.innerHTML = `
                <div class="subscription-status-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="mb-0 me-3">${plan.name || 'Meal Plan'}</h5>
                                <span class="subscription-status-badge ${statusClass}">${sub.status.toUpperCase()}</span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Price</small>
                                    <p class="mb-0 fw-bold" style="color: #ff4900;">UGX ${Number(plan.price || 0).toLocaleString()}/week</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Expires</small>
                                    <p class="mb-0 fw-bold">${expiresAt}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Meals Used</small>
                                <div class="d-flex align-items-center">
                                    <div class="meals-progress flex-grow-1 me-3">
                                        <div class="meals-progress-bar" style="width: ${mealsPercentage}%;"></div>
                                    </div>
                                    <span class="fw-bold">${mealsUsed}/${mealsTotal}</span>
                                </div>
                            </div>
                            
                            <div>
                                <small class="text-muted">Auto-Renew</small>
                                <p class="mb-0">${sub.auto_renew ? '<i class="fa fa-check text-success"></i> Enabled' : '<i class="fa fa-times text-muted"></i> Disabled'}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column gap-2">
                                ${sub.status === 'active' ? `
                                    <button class="action-btn btn-pause" onclick="pauseSubscription()">
                                        <i class="fa fa-pause me-2"></i>Pause
                                    </button>
                                ` : sub.status === 'paused' ? `
                                    <button class="action-btn btn-resume" onclick="resumeSubscription()">
                                        <i class="fa fa-play me-2"></i>Resume
                                    </button>
                                ` : ''}
                                
                                ${(sub.status === 'active' || sub.status === 'paused') ? `
                                    <button class="action-btn btn-cancel" onclick="cancelSubscription()">
                                        <i class="fa fa-times me-2"></i>Cancel
                                    </button>
                                ` : ''}
                                
                                <button class="action-btn" style="background: #f8f9fa; color: #212529;" onclick="toggleAutoRenew()">
                                    <i class="fa fa-sync me-2"></i>${sub.auto_renew ? 'Disable' : 'Enable'} Auto-Renew
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center py-4" style="background: #f8f9fa; border-radius: 12px;">
                    <i class="fa fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
                    <p class="text-muted mb-3">You don't have an active subscription</p>
                    <a href="{{ page_url('account.features') }}#subscriptions" class="btn" style="background: #ff4900; color: white;">
                        <i class="fa fa-crown me-2"></i>Subscribe to a Meal Plan
                    </a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading subscription:', error);
        container.innerHTML = `
            <div class="text-center py-4" style="background: #f8f9fa; border-radius: 12px;">
                <i class="fa fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
                <p class="text-muted mb-0">You don't have an active subscription</p>
            </div>
        `;
    }
}

async function loadSubscriptionHistory() {
    const container = document.getElementById('subscriptionHistoryContent');
    
    try {
        const response = await apiCall('/subscriptions/history');
        const history = response.subscriptions || [];
        
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
                const startDate = sub.started_at ? new Date(sub.started_at).toLocaleDateString('en-UG', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                const endDate = sub.expires_at ? new Date(sub.expires_at).toLocaleDateString('en-UG', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Ongoing';
                const planName = sub.plan?.name || 'Meal Plan';
                const amount = sub.amount_paid || sub.plan?.price || 0;
                const mealsUsed = sub.meals_used || 0;
                const mealsTotal = sub.plan?.meals_per_period || 10;
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
                                    <div class="progress-bar" style="width: ${mealsPercentage}%; background: #ff4900;"></div>
                                </div>
                                <small class="ms-2 text-muted">${mealsUsed}/${mealsTotal}</small>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="text-center py-4" style="background: #f8f9fa; border-radius: 12px;">
                    <i class="fa fa-inbox fa-2x mb-2" style="color: #dee2e6;"></i>
                    <p class="text-muted mb-0">No subscription history yet</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading history:', error);
        container.innerHTML = `
            <div class="text-center py-4" style="background: #f8f9fa; border-radius: 12px;">
                <i class="fa fa-inbox fa-2x mb-2" style="color: #dee2e6;"></i>
                <p class="text-muted mb-0">No subscription history yet</p>
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

async function pauseSubscription() {
    if (!confirm('Are you sure you want to pause your subscription? You can resume anytime.')) return;
    
    try {
        const response = await apiCall('/subscriptions/pause', 'POST');
        if (response.success) {
            alert('Subscription paused successfully');
            loadActiveSubscription();
            loadSubscriptionHistory();
        } else {
            alert(response.error || 'Failed to pause subscription');
        }
    } catch (error) {
        alert(error.message || 'Failed to pause subscription');
    }
}

async function resumeSubscription() {
    try {
        const response = await apiCall('/subscriptions/resume', 'POST');
        if (response.success) {
            alert('Subscription resumed successfully');
            loadActiveSubscription();
            loadSubscriptionHistory();
        } else {
            alert(response.error || 'Failed to resume subscription');
        }
    } catch (error) {
        alert(error.message || 'Failed to resume subscription');
    }
}

async function cancelSubscription() {
    if (!confirm('Are you sure you want to cancel your subscription? You will still be able to use remaining meals until expiration.')) return;
    
    try {
        const response = await apiCall('/subscriptions/cancel', 'POST');
        if (response.success) {
            alert('Subscription cancelled. You can still use remaining meals until expiration.');
            loadActiveSubscription();
            loadSubscriptionHistory();
        } else {
            alert(response.error || 'Failed to cancel subscription');
        }
    } catch (error) {
        alert(error.message || 'Failed to cancel subscription');
    }
}

async function toggleAutoRenew() {
    try {
        const response = await apiCall('/subscriptions/toggle-auto-renew', 'POST');
        if (response.success) {
            alert(response.message);
            loadActiveSubscription();
        } else {
            alert(response.error || 'Failed to update auto-renew setting');
        }
    } catch (error) {
        alert(error.message || 'Failed to update auto-renew setting');
    }
}
</script>
