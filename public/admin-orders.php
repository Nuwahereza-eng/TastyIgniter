<?php
/**
 * Order Management & Rider Assignment Admin Page
 * Access: /admin-orders.php
 * 
 * SECURED: Requires TastyIgniter admin authentication
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// AUTHENTICATION CHECK - REQUIRED
// ============================================
$isAuthenticated = false;
$adminUser = null;

// Check if already authenticated via session
if (isset($_SESSION['admin_orders_authenticated']) && $_SESSION['admin_orders_authenticated'] === true) {
    $isAuthenticated = true;
    $adminUser = $_SESSION['admin_orders_user'] ?? 'Admin';
}

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Verify against TastyIgniter admin users table (prefix ti_ is auto-added)
    $user = DB::table('admin_users')
        ->where('email', $email)
        ->where('is_activated', 1)
        ->first();
    
    if ($user && Hash::check($password, $user->password)) {
        // Check if user has admin permissions (super_user or has a role assigned)
        $hasPermission = $user->super_user || !empty($user->user_role_id);
        
        if ($hasPermission) {
            $_SESSION['admin_orders_authenticated'] = true;
            $_SESSION['admin_orders_user'] = $user->name ?? $user->email;
            $_SESSION['admin_orders_user_id'] = $user->user_id;
            $isAuthenticated = true;
            $adminUser = $user->name ?? $user->email;
        } else {
            $loginError = 'You do not have permission to access this page.';
        }
    } else {
        $loginError = 'Invalid email or password.';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_orders_authenticated']);
    unset($_SESSION['admin_orders_user']);
    unset($_SESSION['admin_orders_user_id']);
    header('Location: /admin-orders.php');
    exit;
}

// If not authenticated, show login form
if (!$isAuthenticated) {
    showLoginForm($loginError ?? null);
    exit;
}

// Generate CSRF token for this session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// ============================================
// AUTHENTICATED - HANDLE API REQUESTS
// ============================================

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Verify CSRF token for POST requests
    $requestToken = $input['csrf_token'] ?? '';
    if ($requestToken !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token. Please refresh the page.']);
        exit;
    }
    
    // Log admin action
    $adminUserId = $_SESSION['admin_orders_user_id'] ?? 0;
    
    switch ($_GET['action']) {
        case 'assign_rider':
            $orderId = $input['order_id'] ?? 0;
            $riderId = $input['rider_id'] ?? 0;
            $riderName = $input['rider_name'] ?? '';
            $riderPhone = $input['rider_phone'] ?? '';
            
            if (!$orderId || !$riderName) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            // Update or create tracking record
            $exists = DB::table('order_tracking')->where('order_id', $orderId)->exists();
            if ($exists) {
                DB::table('order_tracking')->where('order_id', $orderId)->update([
                    'rider_name' => $riderName,
                    'rider_phone' => $riderPhone,
                    'assigned_rider_id' => $riderId ?: null,
                    'rider_accepted_at' => null, // Reset acceptance when reassigned
                    'rider_rejected_at' => null,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('order_tracking')->insert([
                    'order_id' => $orderId,
                    'status' => 'placed',
                    'rider_name' => $riderName,
                    'rider_phone' => $riderPhone,
                    'assigned_rider_id' => $riderId ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Rider assigned successfully. Waiting for rider to accept.']);
            exit;
            
        case 'update_status':
            $orderId = $input['order_id'] ?? 0;
            $status = $input['status'] ?? '';
            $etaMinutes = $input['eta_minutes'] ?? null;
            
            if (!$orderId || !$status) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            $exists = DB::table('order_tracking')->where('order_id', $orderId)->exists();
            $data = [
                'status' => $status,
                'updated_at' => now(),
            ];
            if ($etaMinutes !== null) {
                $data['eta_minutes'] = (int)$etaMinutes;
            }
            
            if ($exists) {
                DB::table('order_tracking')->where('order_id', $orderId)->update($data);
            } else {
                $data['order_id'] = $orderId;
                $data['created_at'] = now();
                DB::table('order_tracking')->insert($data);
            }
            
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            exit;
        
        case 'get_delivery_staff':
            // Get all staff members who can be delivery riders (role_id = 4 for Delivery, or all active staff)
            $prefix = DB::getTablePrefix();
            $staff = DB::table('admin_users as u')
                ->leftJoin('admin_user_roles as r', 'u.user_role_id', '=', 'r.user_role_id')
                ->where('u.status', 1)
                ->where('u.is_activated', 1)
                ->select([
                    'u.user_id',
                    'u.name',
                    'u.telephone',
                    'u.email',
                    'u.is_available',
                    'u.current_order_id',
                    'r.name as role_name',
                    'r.code as role_code',
                ])
                ->orderByRaw("CASE WHEN {$prefix}r.code = 'delivery' THEN 0 ELSE 1 END")
                ->orderByRaw("CASE WHEN {$prefix}u.is_available = 1 THEN 0 ELSE 1 END")
                ->orderBy('u.name')
                ->get();
            
            $formatted = $staff->map(function($s) {
                return [
                    'id' => $s->user_id,
                    'name' => $s->name,
                    'phone' => $s->telephone ?: '',
                    'email' => $s->email,
                    'role' => $s->role_name ?? 'Staff',
                    'is_delivery' => ($s->role_code === 'delivery'),
                    'is_available' => (bool)$s->is_available,
                    'current_order_id' => $s->current_order_id,
                ];
            });
            
            echo json_encode(['success' => true, 'staff' => $formatted]);
            exit;
            
        case 'get_orders':
            $orders = DB::table('orders as o')
                ->leftJoin('locations as l', 'o.location_id', '=', 'l.location_id')
                ->leftJoin('statuses as s', 'o.status_id', '=', 's.status_id')
                ->leftJoin('order_tracking as t', 'o.order_id', '=', 't.order_id')
                ->leftJoin('addresses as a', 'o.address_id', '=', 'a.address_id')
                ->select([
                    'o.order_id',
                    'o.first_name',
                    'o.last_name',
                    'o.telephone',
                    'o.order_total',
                    'a.address_1',
                    'a.address_2',
                    'a.city',
                    'o.created_at',
                    'l.location_name',
                    's.status_name',
                    't.status as tracking_status',
                    't.rider_name',
                    't.rider_phone',
                    't.eta_minutes',
                ])
                ->orderBy('o.created_at', 'desc')
                ->limit(100)
                ->get();
            
            $formattedOrders = $orders->map(function($order) {
                $addressParts = array_filter([
                    $order->address_1 ?? '',
                    $order->address_2 ?? '',
                    $order->city ?? ''
                ]);
                $address = implode(', ', $addressParts) ?: 'N/A';
                
                return [
                    'id' => $order->order_id,
                    'formatted_id' => 'UGA-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                    'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                    'phone' => $order->telephone ?? '',
                    'total' => $order->order_total ?? 0,
                    'total_formatted' => 'UGX ' . number_format($order->order_total ?? 0),
                    'status' => $order->status_name ?? 'Pending',
                    'tracking_status' => $order->tracking_status ?? 'pending',
                    'address' => $address,
                    'location' => $order->location_name ?? 'Restaurant',
                    'order_time' => date('g:i A', strtotime($order->created_at)),
                    'order_date' => date('M d, Y', strtotime($order->created_at)),
                    'rider' => $order->rider_name ? [
                        'name' => $order->rider_name,
                        'phone' => $order->rider_phone,
                    ] : null,
                    'eta_minutes' => $order->eta_minutes,
                ];
            });
            
            echo json_encode(['success' => true, 'orders' => $formattedOrders]);
            exit;
    }
    
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

// Get order items for an order
function getOrderItems($orderId) {
    $items = DB::table('order_menus')
        ->where('order_id', $orderId)
        ->pluck('name')
        ->toArray();
    return implode(', ', $items);
}

// Show main admin page
showAdminPage($adminUser, $csrfToken);
exit;

// ============================================
// MAIN ADMIN PAGE FUNCTION
// ============================================
function showAdminPage($adminUser, $csrfToken) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - UgaEats Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .navbar-brand img { height: 40px; }
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
        .status-badge { font-size: 0.75rem; padding: 0.35em 0.65em; }
        .filter-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        .filter-tabs .nav-link.active {
            color: #ff6600;
            font-weight: 600;
            border-bottom: 2px solid #ff6600;
        }
        .order-time { font-size: 0.875rem; color: #6c757d; }
        .customer-info { font-size: 0.9rem; }
        .btn-orange { background: #ff6600; color: white; }
        .btn-orange:hover { background: #e55a00; color: white; }
        .stats-card { background: white; border-radius: 10px; padding: 1.5rem; }
        .stats-number { font-size: 2rem; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #ff6600;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin-orders.php">
                <i class="fa fa-motorcycle me-2"></i>UgaEats Order Manager
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
                <span class="nav-link text-white-50 me-2">
                    <i class="fa fa-user-shield me-1"></i><?= htmlspecialchars($adminUser) ?>
                </span>
                <a class="nav-link" href="/admin"><i class="fa fa-cog me-1"></i>Admin Panel</a>
                <a class="nav-link" href="/"><i class="fa fa-home me-1"></i>Website</a>
                <a class="nav-link text-warning" href="?logout=1"><i class="fa fa-sign-out-alt me-1"></i>Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Stats Row -->
        <div class="row mb-4" id="statsRow">
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-warning" id="statPending">0</div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-info" id="statPreparing">0</div>
                    <div class="text-muted">Preparing</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-primary" id="statDelivery">0</div>
                    <div class="text-muted">Out for Delivery</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-success" id="statDelivered">0</div>
                    <div class="text-muted">Delivered Today</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa fa-clipboard-list text-primary me-2"></i>Order Management</h2>
            <button class="btn btn-outline-primary" onclick="loadOrders()">
                <i class="fa fa-sync me-2"></i>Refresh
            </button>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav filter-tabs mb-4" id="orderTabs">
            <li class="nav-item"><a class="nav-link active" data-filter="all" onclick="filterOrders('all')">All Orders</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="pending" onclick="filterOrders('pending')">Pending</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="confirmed" onclick="filterOrders('confirmed')">Confirmed</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="preparing" onclick="filterOrders('preparing')">Preparing</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="ready" onclick="filterOrders('ready')">Ready</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="on_the_way" onclick="filterOrders('on_the_way')">Out for Delivery</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="delivered" onclick="filterOrders('delivered')">Delivered</a></li>
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
                    <h5 class="modal-title"><i class="fa fa-motorcycle me-2"></i>Assign Delivery Rider</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="assignOrderId">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong id="assignOrderInfo">Order UGA-00001</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Delivery Staff <span class="text-danger">*</span></label>
                        <select class="form-select" id="riderSelect" onchange="onRiderSelect()">
                            <option value="">-- Loading staff... --</option>
                        </select>
                        <div class="form-text text-muted">Delivery staff are shown first</div>
                    </div>
                    <div id="selectedRiderInfo" class="card bg-light d-none">
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Name</small>
                                    <div id="selectedRiderName" class="fw-bold">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Phone</small>
                                    <div id="selectedRiderPhone" class="fw-bold">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="riderName">
                    <input type="hidden" id="riderPhone">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-orange" onclick="submitAssignRider()" id="assignRiderBtn">
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
                    <div class="alert alert-info">
                        <strong id="statusOrderInfo">Order UGA-00001</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Status</label>
                        <select class="form-select" id="newStatus">
                            <option value="pending">Pending</option>
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
                        <label class="form-label">Estimated Delivery Time (minutes)</label>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Security: CSRF Token
    const CSRF_TOKEN = '<?= htmlspecialchars($csrfToken) ?>';
    
    let allOrders = [];
    let currentFilter = 'all';

    async function loadOrders() {
        try {
            const response = await fetch('?action=get_orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                allOrders = data.orders;
                updateStats();
                renderOrders(allOrders);
            } else if (data.error && data.error.includes('security token')) {
                alert('Session expired. Please refresh the page.');
                location.reload();
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

    function updateStats() {
        const pending = allOrders.filter(o => !o.tracking_status || o.tracking_status === 'pending' || o.tracking_status === 'confirmed').length;
        const preparing = allOrders.filter(o => o.tracking_status === 'preparing' || o.tracking_status === 'ready').length;
        const delivery = allOrders.filter(o => o.tracking_status === 'picked_up' || o.tracking_status === 'on_the_way' || o.tracking_status === 'nearby').length;
        const delivered = allOrders.filter(o => o.tracking_status === 'delivered').length;
        
        document.getElementById('statPending').textContent = pending;
        document.getElementById('statPreparing').textContent = preparing;
        document.getElementById('statDelivery').textContent = delivery;
        document.getElementById('statDelivered').textContent = delivered;
    }

    function renderOrders(orders) {
        const filtered = currentFilter === 'all' 
            ? orders 
            : orders.filter(o => (o.tracking_status || 'pending') === currentFilter);
        
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
                <div class="card order-card status-${order.tracking_status || 'pending'}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">${order.formatted_id}</h6>
                        <span class="badge status-badge ${getStatusBadgeClass(order.tracking_status)}">
                            ${getStatusLabel(order.tracking_status)}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="customer-info mb-3">
                            <p class="mb-1"><i class="fa fa-user text-muted me-2"></i><strong>${order.customer_name || 'Customer'}</strong></p>
                            <p class="mb-1"><i class="fa fa-phone text-muted me-2"></i>${order.phone || 'N/A'}</p>
                            <p class="mb-1"><i class="fa fa-map-marker-alt text-muted me-2"></i>${order.address || 'Address'}</p>
                            <p class="mb-1"><i class="fa fa-store text-muted me-2"></i>${order.location || 'Restaurant'}</p>
                        </div>
                        <div class="border-top pt-2 mb-3">
                            <p class="mb-0 fw-bold" style="color: #ff6600;"><i class="fa fa-money-bill text-muted me-2"></i>${order.total_formatted}</p>
                        </div>
                        ${order.rider ? `
                            <div class="bg-light rounded p-2 mb-3">
                                <small class="text-muted d-block">Assigned Rider:</small>
                                <span class="fw-bold"><i class="fa fa-motorcycle text-success me-2"></i>${order.rider.name}</span>
                                <a href="tel:${order.rider.phone}" class="ms-2 text-primary">${order.rider.phone}</a>
                            </div>
                        ` : '<div class="alert alert-warning py-2 mb-3"><i class="fa fa-exclamation-triangle me-2"></i>No rider assigned</div>'}
                        ${order.eta_minutes ? `<div class="text-info mb-2"><i class="fa fa-clock me-2"></i>ETA: ${order.eta_minutes} mins</div>` : ''}
                        <div class="order-time text-end">
                            <i class="fa fa-clock me-1"></i>${order.order_time} on ${order.order_date}
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-info flex-grow-1" onclick="openStatusModal(${order.id}, '${order.formatted_id}', '${order.tracking_status || 'pending'}')">
                                <i class="fa fa-sync me-1"></i>Status
                            </button>
                            <button class="btn btn-sm btn-orange flex-grow-1" onclick="openAssignModal(${order.id}, '${order.formatted_id}', '${order.customer_name}')">
                                <i class="fa fa-motorcycle me-1"></i>Assign
                            </button>
                            <a href="/track-order?order=${order.formatted_id}" class="btn btn-sm btn-outline-secondary" target="_blank" title="View Tracking">
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
        document.querySelectorAll('.filter-tabs .nav-link').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.filter === filter);
        });
        renderOrders(allOrders);
    }

    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning text-dark',
            'confirmed': 'bg-info',
            'preparing': 'bg-warning',
            'ready': 'bg-success',
            'picked_up': 'bg-primary',
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
        return labels[status] || 'Pending';
    }

    let deliveryStaff = [];
    
    async function loadDeliveryStaff() {
        try {
            const response = await fetch('?action=get_delivery_staff', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            if (data.success) {
                deliveryStaff = data.staff;
                updateRiderDropdown();
            }
        } catch (error) {
            console.error('Failed to load delivery staff:', error);
        }
    }
    
    function updateRiderDropdown() {
        const select = document.getElementById('riderSelect');
        select.innerHTML = '<option value="">-- Select a rider --</option>';
        
        // Group by delivery staff first, then by availability
        const deliveryRiders = deliveryStaff.filter(s => s.is_delivery);
        const otherStaff = deliveryStaff.filter(s => !s.is_delivery);
        
        if (deliveryRiders.length > 0) {
            const available = deliveryRiders.filter(s => s.is_available);
            const busy = deliveryRiders.filter(s => !s.is_available);
            
            if (available.length > 0) {
                const group1 = document.createElement('optgroup');
                group1.label = '🟢 Available Riders';
                available.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = `${s.name}${s.phone ? ' - ' + s.phone : ''}`;
                    opt.dataset.name = s.name;
                    opt.dataset.phone = s.phone;
                    group1.appendChild(opt);
                });
                select.appendChild(group1);
            }
            
            if (busy.length > 0) {
                const group2 = document.createElement('optgroup');
                group2.label = '🔴 Busy Riders';
                busy.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = `${s.name} (on delivery)${s.phone ? ' - ' + s.phone : ''}`;
                    opt.dataset.name = s.name;
                    opt.dataset.phone = s.phone;
                    group2.appendChild(opt);
                });
                select.appendChild(group2);
            }
        }
        
        if (otherStaff.length > 0) {
            const group3 = document.createElement('optgroup');
            group3.label = '👤 Other Staff';
            otherStaff.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = `${s.name} (${s.role})${s.phone ? ' - ' + s.phone : ''}`;
                opt.dataset.name = s.name;
                opt.dataset.phone = s.phone;
                group3.appendChild(opt);
            });
            select.appendChild(group3);
        }
    }
    
    function onRiderSelect() {
        const select = document.getElementById('riderSelect');
        const selectedOption = select.options[select.selectedIndex];
        const infoDiv = document.getElementById('selectedRiderInfo');
        
        if (select.value) {
            const name = selectedOption.dataset.name || '';
            const phone = selectedOption.dataset.phone || '';
            
            document.getElementById('riderName').value = name;
            document.getElementById('riderPhone').value = phone;
            document.getElementById('selectedRiderName').textContent = name;
            document.getElementById('selectedRiderPhone').textContent = phone || 'No phone';
            infoDiv.classList.remove('d-none');
        } else {
            document.getElementById('riderName').value = '';
            document.getElementById('riderPhone').value = '';
            infoDiv.classList.add('d-none');
        }
    }

    function openAssignModal(orderId, formattedId, customerName) {
        document.getElementById('assignOrderId').value = orderId;
        document.getElementById('assignOrderInfo').textContent = `${formattedId} - ${customerName}`;
        document.getElementById('riderName').value = '';
        document.getElementById('riderPhone').value = '';
        document.getElementById('riderSelect').value = '';
        document.getElementById('selectedRiderInfo').classList.add('d-none');
        
        // Load staff if not loaded yet
        if (deliveryStaff.length === 0) {
            loadDeliveryStaff();
        } else {
            updateRiderDropdown();
        }
        
        new bootstrap.Modal(document.getElementById('assignRiderModal')).show();
    }

    async function submitAssignRider() {
        const orderId = document.getElementById('assignOrderId').value;
        const riderSelect = document.getElementById('riderSelect');
        const riderId = riderSelect.value;
        const riderName = document.getElementById('riderName').value.trim();
        const riderPhone = document.getElementById('riderPhone').value.trim();
        
        if (!riderId || !riderName) {
            alert('Please select a delivery rider');
            return;
        }
        
        try {
            const response = await fetch('?action=assign_rider', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, rider_id: riderId, rider_name: riderName, rider_phone: riderPhone, csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                alert('✓ ' + data.message);
                bootstrap.Modal.getInstance(document.getElementById('assignRiderModal')).hide();
                loadOrders();
            } else {
                alert('Error: ' + (data.error || 'Failed to assign rider'));
            }
        } catch (error) {
            alert('Failed to assign rider. Please try again.');
        }
    }

    function openStatusModal(orderId, formattedId, currentStatus) {
        document.getElementById('statusOrderId').value = orderId;
        document.getElementById('statusOrderInfo').textContent = formattedId;
        document.getElementById('newStatus').value = currentStatus || 'pending';
        document.getElementById('etaMinutes').value = '';
        new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
    }

    async function submitUpdateStatus() {
        const orderId = document.getElementById('statusOrderId').value;
        const status = document.getElementById('newStatus').value;
        const etaMinutes = document.getElementById('etaMinutes').value;
        
        try {
            const response = await fetch('?action=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: status, eta_minutes: etaMinutes || null, csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                alert('✓ Status updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
                loadOrders();
            } else {
                alert('Error: ' + (data.error || 'Failed to update status'));
            }
        } catch (error) {
            alert('Failed to update status. Please try again.');
        }
    }

    // Load orders on page load
    loadOrders();
    
    // Auto-refresh every 30 seconds
    setInterval(loadOrders, 30000);
    </script>
</body>
</html>
<?php
}

// ============================================
// LOGIN FORM FUNCTION
// ============================================
function showLoginForm($error = null) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UgaEats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff6600 0%, #ff8533 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .login-header {
            background: #333;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .login-body {
            padding: 2rem;
        }
        .btn-login {
            background: #ff6600;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #e55a00;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fa fa-motorcycle"></i>
            <h4 class="mb-0">UgaEats Admin</h4>
            <small>Order Management System</small>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="admin_login" value="1">
                
                <div class="mb-3">
                    <label class="form-label"><i class="fa fa-envelope me-2"></i>Admin Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" 
                           placeholder="admin@ugaeats.com" required autofocus>
                </div>
                
                <div class="mb-4">
                    <label class="form-label"><i class="fa fa-lock me-2"></i>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" 
                           placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-login btn-primary w-100 btn-lg">
                    <i class="fa fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <a href="/" class="text-muted"><i class="fa fa-home me-1"></i>Back to Website</a>
                <span class="mx-2">|</span>
                <a href="/admin" class="text-muted"><i class="fa fa-cog me-1"></i>TastyIgniter Admin</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
}
?>
