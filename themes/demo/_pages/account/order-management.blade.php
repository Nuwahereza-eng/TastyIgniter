---
title: 'Order Management'
layout: account
permalink: /account/order-management
---
<style>
.order-card {
    transition: all 0.2s ease;
    border-left: 4px solid #dee2e6;
}
.order-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.order-card.status-pending { border-left-color: #ffc107; }
.order-card.status-confirmed { border-left-color: #17a2b8; }
.order-card.status-preparing { border-left-color: #fd7e14; }
.order-card.status-ready { border-left-color: #20c997; }
.order-card.status-picked_up { border-left-color: #6f42c1; }
.order-card.status-on_the_way { border-left-color: #ff6600; }
.order-card.status-delivered { border-left-color: #28a745; }

.status-badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
.rider-input-group {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
}
.order-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.filter-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.5rem 1rem;
}
.filter-tabs .nav-link.active {
    color: #ff6600;
    font-weight: 600;
    border-bottom: 2px solid #ff6600;
}
.order-time {
    font-size: 0.875rem;
    color: #6c757d;
}
.customer-info {
    font-size: 0.9rem;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-clipboard-list text-primary me-2"></i>Order Management</h2>
        <button class="btn btn-outline-primary" onclick="loadOrders()">
            <i class="fa fa-sync me-2"></i>Refresh
        </button>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav filter-tabs mb-4" id="orderTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-filter="all" onclick="filterOrders('all')">All Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="pending" onclick="filterOrders('pending')">Pending</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="confirmed" onclick="filterOrders('confirmed')">Confirmed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="preparing" onclick="filterOrders('preparing')">Preparing</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="ready" onclick="filterOrders('ready')">Ready</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="on_the_way" onclick="filterOrders('on_the_way')">Out for Delivery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-filter="delivered" onclick="filterOrders('delivered')">Delivered</a>
        </li>
    </ul>

    <!-- Orders List -->
    <div id="ordersList" class="row">
        <div class="col-12 text-center py-5">
            <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            <p class="mt-3 text-muted">Loading orders...</p>
        </div>
    </div>
</div>

<!-- Assign Rider Modal -->
<div class="modal fade" id="assignRiderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa fa-motorcycle me-2"></i>Assign Rider</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assignOrderId">
                <div class="mb-3">
                    <label class="form-label fw-bold">Order</label>
                    <p class="mb-0" id="assignOrderInfo">UGA-00001 - Customer Name</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Rider Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="riderName" placeholder="e.g., John Mukasa" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Rider Phone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="riderPhone" placeholder="e.g., +256779081600" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Vehicle Plate Number</label>
                    <input type="text" class="form-control" id="riderPlate" placeholder="e.g., UBD 123A">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAssignRider()">
                    <i class="fa fa-check me-2"></i>Assign Rider
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-sync me-2"></i>Update Order Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="statusOrderId">
                <div class="mb-3">
                    <label class="form-label fw-bold">Order</label>
                    <p class="mb-0" id="statusOrderInfo">UGA-00001</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">New Status</label>
                    <select class="form-select" id="newStatus">
                        <option value="confirmed">Order Confirmed</option>
                        <option value="preparing">Preparing Food</option>
                        <option value="ready">Ready for Pickup</option>
                        <option value="picked_up">Picked Up by Rider</option>
                        <option value="on_the_way">Out for Delivery</option>
                        <option value="nearby">Rider Nearby</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estimated Time (minutes)</label>
                    <input type="number" class="form-control" id="etaMinutes" placeholder="e.g., 30" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white" onclick="submitUpdateStatus()">
                    <i class="fa fa-save me-2"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = '/ajax';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

let allOrders = [];
let currentFilter = 'all';

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

async function loadOrders() {
    try {
        const response = await apiCall('/orders/list', 'GET');
        
        if (response.success && response.orders) {
            allOrders = response.orders;
            renderOrders(allOrders);
        } else {
            // Fallback: load from Laravel
            loadOrdersFromBackend();
        }
    } catch (error) {
        console.error('Failed to load orders:', error);
        loadOrdersFromBackend();
    }
}

async function loadOrdersFromBackend() {
    // Fetch orders using the existing order list
    try {
        const response = await fetch('/api/orders?limit=50', {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        
        if (data.data) {
            allOrders = data.data.map(order => ({
                id: order.order_id,
                formatted_id: 'UGA-' + String(order.order_id).padStart(5, '0'),
                customer_name: (order.first_name || '') + ' ' + (order.last_name || ''),
                phone: order.telephone,
                total: order.order_total,
                total_formatted: 'UGX ' + Number(order.order_total).toLocaleString(),
                status: order.status_name || 'Pending',
                tracking_status: 'pending',
                address: order.formatted_address || order.address?.address_1 || 'N/A',
                location: order.location?.location_name || 'Restaurant',
                items: order.order_menus?.map(m => m.name).join(', ') || 'Items',
                created_at: order.created_at,
                order_time: new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                order_date: new Date(order.created_at).toLocaleDateString(),
                rider: null,
            }));
            
            // Get tracking status for each order
            for (let order of allOrders.slice(0, 20)) {
                try {
                    const trackResp = await apiCall('/tracking/track', 'POST', { order_id: order.id });
                    if (trackResp.success && trackResp.tracking) {
                        order.tracking_status = trackResp.tracking.status;
                        order.rider = trackResp.tracking.rider;
                    }
                } catch (e) {}
            }
            
            renderOrders(allOrders);
        }
    } catch (error) {
        console.error('Failed to load orders:', error);
        document.getElementById('ordersList').innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                <p class="mt-3 text-muted">Failed to load orders. Please try again.</p>
                <button class="btn btn-primary" onclick="loadOrders()">Retry</button>
            </div>
        `;
    }
}

function renderOrders(orders) {
    const filtered = currentFilter === 'all' 
        ? orders 
        : orders.filter(o => o.tracking_status === currentFilter);
    
    if (filtered.length === 0) {
        document.getElementById('ordersList').innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fa fa-inbox fa-3x text-muted"></i>
                <p class="mt-3 text-muted">No orders found</p>
            </div>
        `;
        return;
    }
    
    const html = filtered.map(order => `
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card order-card status-${order.tracking_status}">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">${order.formatted_id}</h6>
                    <span class="badge status-badge ${getStatusBadgeClass(order.tracking_status)}">
                        ${getStatusLabel(order.tracking_status)}
                    </span>
                </div>
                <div class="card-body">
                    <div class="customer-info mb-3">
                        <p class="mb-1"><i class="fa fa-user text-muted me-2"></i><strong>${order.customer_name}</strong></p>
                        <p class="mb-1"><i class="fa fa-phone text-muted me-2"></i>${order.phone || 'N/A'}</p>
                        <p class="mb-1"><i class="fa fa-map-marker-alt text-muted me-2"></i>${order.address}</p>
                        <p class="mb-1"><i class="fa fa-store text-muted me-2"></i>${order.location}</p>
                    </div>
                    <div class="border-top pt-2 mb-3">
                        <p class="mb-1"><i class="fa fa-utensils text-muted me-2"></i>${order.items}</p>
                        <p class="mb-0 fw-bold text-primary"><i class="fa fa-money-bill text-muted me-2"></i>${order.total_formatted}</p>
                    </div>
                    ${order.rider ? `
                        <div class="rider-info bg-light rounded p-2 mb-3">
                            <small class="text-muted">Assigned Rider:</small>
                            <p class="mb-0 fw-bold"><i class="fa fa-motorcycle me-2"></i>${order.rider.name} - ${order.rider.phone}</p>
                        </div>
                    ` : ''}
                    <div class="order-time text-end">
                        <i class="fa fa-clock me-1"></i>${order.order_time} on ${order.order_date}
                    </div>
                </div>
                <div class="card-footer bg-white order-actions">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-info flex-grow-1" onclick="openStatusModal(${order.id}, '${order.formatted_id}')">
                            <i class="fa fa-sync me-1"></i>Status
                        </button>
                        <button class="btn btn-primary flex-grow-1" onclick="openAssignModal(${order.id}, '${order.formatted_id}', '${order.customer_name}')">
                            <i class="fa fa-motorcycle me-1"></i>Assign Rider
                        </button>
                        <a href="/track-order?order=${order.formatted_id}" class="btn btn-outline-secondary" target="_blank">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    document.getElementById('ordersList').innerHTML = html;
}

function filterOrders(filter) {
    currentFilter = filter;
    
    // Update tab styling
    document.querySelectorAll('.filter-tabs .nav-link').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.filter === filter) {
            tab.classList.add('active');
        }
    });
    
    renderOrders(allOrders);
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-warning text-dark',
        'confirmed': 'bg-info',
        'preparing': 'bg-orange text-white',
        'ready': 'bg-success',
        'picked_up': 'bg-purple text-white',
        'on_the_way': 'bg-primary',
        'nearby': 'bg-info',
        'delivered': 'bg-success',
    };
    return classes[status] || 'bg-secondary';
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'preparing': 'Preparing',
        'ready': 'Ready',
        'picked_up': 'Picked Up',
        'on_the_way': 'Out for Delivery',
        'nearby': 'Nearby',
        'delivered': 'Delivered',
    };
    return labels[status] || status;
}

function openAssignModal(orderId, formattedId, customerName) {
    document.getElementById('assignOrderId').value = orderId;
    document.getElementById('assignOrderInfo').textContent = `${formattedId} - ${customerName}`;
    document.getElementById('riderName').value = '';
    document.getElementById('riderPhone').value = '';
    document.getElementById('riderPlate').value = '';
    
    new bootstrap.Modal(document.getElementById('assignRiderModal')).show();
}

async function submitAssignRider() {
    const orderId = document.getElementById('assignOrderId').value;
    const riderName = document.getElementById('riderName').value.trim();
    const riderPhone = document.getElementById('riderPhone').value.trim();
    const riderPlate = document.getElementById('riderPlate').value.trim();
    
    if (!riderName || !riderPhone) {
        alert('Please enter rider name and phone number');
        return;
    }
    
    try {
        const response = await fetch(`/api/tracking/${orderId}/assign-rider`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                rider_name: riderName,
                rider_phone: riderPhone,
                rider_plate: riderPlate,
            }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Rider assigned successfully!');
            bootstrap.Modal.getInstance(document.getElementById('assignRiderModal')).hide();
            loadOrders();
        } else {
            alert('Failed to assign rider: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to assign rider. Please try again.');
    }
}

function openStatusModal(orderId, formattedId) {
    document.getElementById('statusOrderId').value = orderId;
    document.getElementById('statusOrderInfo').textContent = formattedId;
    document.getElementById('newStatus').value = 'confirmed';
    document.getElementById('etaMinutes').value = '';
    
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}

async function submitUpdateStatus() {
    const orderId = document.getElementById('statusOrderId').value;
    const status = document.getElementById('newStatus').value;
    const etaMinutes = document.getElementById('etaMinutes').value;
    
    try {
        const response = await fetch(`/api/tracking/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                status: status,
                eta_minutes: etaMinutes ? parseInt(etaMinutes) : null,
            }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Status updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
            loadOrders();
        } else {
            alert('Failed to update status: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update status. Please try again.');
    }
}

// Load orders on page load
document.addEventListener('DOMContentLoaded', loadOrders);

// Auto-refresh every 60 seconds
setInterval(loadOrders, 60000);
</script>
