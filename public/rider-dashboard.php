<?php
/**
 * Rider Dashboard - UgaEats
 * 
 * Allows delivery riders to:
 * - Login with their staff credentials
 * - View assigned orders
 * - Accept/Reject order assignments
 * - Update delivery status
 * - Toggle availability
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

session_start();

// ============================================
// AUTHENTICATION CHECK
// ============================================
$isAuthenticated = isset($_SESSION['rider_authenticated']) && $_SESSION['rider_authenticated'] === true;
$riderUser = $_SESSION['rider_user'] ?? null;
$riderId = $_SESSION['rider_user_id'] ?? null;
$loginError = '';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: rider-dashboard.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isAuthenticated && !isset($_GET['action'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Verify against TastyIgniter admin users table - only delivery staff
    $user = DB::table('admin_users as u')
        ->leftJoin('admin_user_roles as r', 'u.user_role_id', '=', 'r.user_role_id')
        ->where('u.email', $email)
        ->where('u.is_activated', 1)
        ->where('u.status', 1)
        ->select(['u.*', 'r.code as role_code', 'r.name as role_name'])
        ->first();
    
    if ($user && Hash::check($password, $user->password)) {
        // Check if user is delivery staff
        if ($user->role_code === 'delivery') {
            $_SESSION['rider_authenticated'] = true;
            $_SESSION['rider_user'] = $user->name ?? $user->email;
            $_SESSION['rider_user_id'] = $user->user_id;
            $_SESSION['rider_phone'] = $user->telephone ?? '';
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $isAuthenticated = true;
            $riderUser = $user->name ?? $user->email;
            $riderId = $user->user_id;
        } else {
            $loginError = 'Access denied. Only delivery staff can access this dashboard.';
        }
    } else {
        $loginError = 'Invalid email or password.';
    }
}

// Generate CSRF token if not exists
if ($isAuthenticated && !isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'] ?? '';

// ============================================
// SHOW LOGIN PAGE IF NOT AUTHENTICATED
// ============================================
if (!$isAuthenticated) {
    showLoginPage($loginError);
    exit;
}

// ============================================
// API ACTIONS
// ============================================
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Verify CSRF token
    $requestToken = $input['csrf_token'] ?? '';
    if ($requestToken !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        exit;
    }
    
    switch ($_GET['action']) {
        case 'get_my_orders':
            // Get orders assigned to this rider
            $orders = DB::table('orders as o')
                ->join('order_tracking as t', 'o.order_id', '=', 't.order_id')
                ->leftJoin('locations as l', 'o.location_id', '=', 'l.location_id')
                ->leftJoin('statuses as s', 'o.status_id', '=', 's.status_id')
                ->leftJoin('addresses as a', 'o.address_id', '=', 'a.address_id')
                ->where('t.assigned_rider_id', $riderId)
                ->whereNotIn('t.status', ['delivered', 'cancelled'])
                ->select([
                    'o.order_id',
                    'o.first_name',
                    'o.last_name',
                    'o.telephone',
                    'o.order_total',
                    'o.created_at',
                    'o.payment',
                    'a.address_1',
                    'a.address_2',
                    'a.city',
                    'l.location_name',
                    'l.location_address_1 as restaurant_address',
                    's.status_name',
                    't.status as tracking_status',
                    't.eta_minutes',
                    't.rider_accepted_at',
                ])
                ->orderBy('o.created_at', 'desc')
                ->get();
            
            $formatted = $orders->map(function($order) {
                $addressParts = array_filter([
                    $order->address_1 ?? '',
                    $order->address_2 ?? '',
                    $order->city ?? ''
                ]);
                
                return [
                    'id' => $order->order_id,
                    'formatted_id' => 'UGA-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                    'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                    'phone' => $order->telephone ?? '',
                    'total' => $order->order_total ?? 0,
                    'total_formatted' => 'UGX ' . number_format($order->order_total ?? 0),
                    'payment_method' => ucfirst($order->payment ?? 'cod'),
                    'status' => $order->status_name ?? 'Pending',
                    'tracking_status' => $order->tracking_status ?? 'pending',
                    'address' => implode(', ', $addressParts) ?: 'N/A',
                    'restaurant' => $order->location_name ?? 'Restaurant',
                    'restaurant_address' => $order->restaurant_address ?? '',
                    'eta' => $order->eta_minutes,
                    'accepted' => !empty($order->rider_accepted_at),
                    'created_at' => $order->created_at,
                ];
            });
            
            echo json_encode(['success' => true, 'orders' => $formatted]);
            exit;
        
        case 'get_pending_orders':
            // Get orders assigned to this rider but not yet accepted
            $orders = DB::table('orders as o')
                ->join('order_tracking as t', 'o.order_id', '=', 't.order_id')
                ->leftJoin('locations as l', 'o.location_id', '=', 'l.location_id')
                ->leftJoin('addresses as a', 'o.address_id', '=', 'a.address_id')
                ->where('t.assigned_rider_id', $riderId)
                ->whereNull('t.rider_accepted_at')
                ->whereNull('t.rider_rejected_at')
                ->select([
                    'o.order_id',
                    'o.first_name',
                    'o.last_name',
                    'o.telephone',
                    'o.order_total',
                    'o.payment',
                    'a.address_1',
                    'a.city',
                    'l.location_name',
                    'l.location_address_1 as restaurant_address',
                ])
                ->orderBy('o.created_at', 'desc')
                ->get();
            
            $formatted = $orders->map(function($order) {
                return [
                    'id' => $order->order_id,
                    'formatted_id' => 'UGA-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                    'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                    'phone' => $order->telephone ?? '',
                    'total_formatted' => 'UGX ' . number_format($order->order_total ?? 0),
                    'payment_method' => ucfirst($order->payment ?? 'cod'),
                    'address' => ($order->address_1 ?? '') . ', ' . ($order->city ?? ''),
                    'restaurant' => $order->location_name ?? 'Restaurant',
                    'restaurant_address' => $order->restaurant_address ?? '',
                ];
            });
            
            echo json_encode(['success' => true, 'orders' => $formatted]);
            exit;
        
        case 'accept_order':
            $orderId = $input['order_id'] ?? 0;
            
            if (!$orderId) {
                echo json_encode(['success' => false, 'error' => 'Missing order ID']);
                exit;
            }
            
            // Update tracking record
            DB::table('order_tracking')
                ->where('order_id', $orderId)
                ->where('assigned_rider_id', $riderId)
                ->update([
                    'rider_accepted_at' => now(),
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);
            
            // Mark rider as busy
            DB::table('admin_users')
                ->where('user_id', $riderId)
                ->update([
                    'is_available' => 0,
                    'current_order_id' => $orderId,
                ]);
            
            echo json_encode(['success' => true, 'message' => 'Order accepted!']);
            exit;
        
        case 'reject_order':
            $orderId = $input['order_id'] ?? 0;
            $reason = $input['reason'] ?? '';
            
            if (!$orderId) {
                echo json_encode(['success' => false, 'error' => 'Missing order ID']);
                exit;
            }
            
            // Update tracking record - remove rider assignment
            DB::table('order_tracking')
                ->where('order_id', $orderId)
                ->where('assigned_rider_id', $riderId)
                ->update([
                    'rider_rejected_at' => now(),
                    'assigned_rider_id' => null,
                    'rider_name' => null,
                    'rider_phone' => null,
                    'message' => 'Rider rejected: ' . $reason,
                    'updated_at' => now(),
                ]);
            
            echo json_encode(['success' => true, 'message' => 'Order rejected']);
            exit;
        
        case 'update_status':
            $orderId = $input['order_id'] ?? 0;
            $status = $input['status'] ?? '';
            $etaMinutes = $input['eta_minutes'] ?? null;
            
            if (!$orderId || !$status) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            $data = [
                'status' => $status,
                'updated_at' => now(),
            ];
            if ($etaMinutes !== null) {
                $data['eta_minutes'] = (int)$etaMinutes;
            }
            
            DB::table('order_tracking')
                ->where('order_id', $orderId)
                ->where('assigned_rider_id', $riderId)
                ->update($data);
            
            // If delivered, mark rider as available again
            if ($status === 'delivered') {
                DB::table('admin_users')
                    ->where('user_id', $riderId)
                    ->update([
                        'is_available' => 1,
                        'current_order_id' => null,
                    ]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Status updated']);
            exit;
        
        case 'toggle_availability':
            $available = $input['available'] ?? false;
            
            DB::table('admin_users')
                ->where('user_id', $riderId)
                ->update([
                    'is_available' => $available ? 1 : 0,
                ]);
            
            echo json_encode(['success' => true, 'available' => $available]);
            exit;
        
        case 'get_status':
            $rider = DB::table('admin_users')
                ->where('user_id', $riderId)
                ->select(['is_available', 'current_order_id'])
                ->first();
            
            $pendingCount = DB::table('order_tracking')
                ->where('assigned_rider_id', $riderId)
                ->whereNull('rider_accepted_at')
                ->whereNull('rider_rejected_at')
                ->count();
            
            $activeCount = DB::table('order_tracking')
                ->where('assigned_rider_id', $riderId)
                ->whereNotNull('rider_accepted_at')
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->count();
            
            echo json_encode([
                'success' => true,
                'is_available' => (bool)($rider->is_available ?? true),
                'current_order_id' => $rider->current_order_id,
                'pending_count' => $pendingCount,
                'active_count' => $activeCount,
            ]);
            exit;
    }
}

// ============================================
// SHOW RIDER DASHBOARD
// ============================================
showRiderDashboard($riderUser, $csrfToken);
exit;

// ============================================
// LOGIN PAGE FUNCTION
// ============================================
function showLoginPage($error = '') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login - UgaEats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            color: #28a745;
        }
        .login-body {
            padding: 2rem;
        }
        .btn-rider {
            background: #28a745;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
        }
        .btn-rider:hover {
            background: #1e7e34;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fa fa-motorcycle"></i>
            <h4>UgaEats Rider</h4>
            <p class="mb-0 opacity-75">Delivery Dashboard</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" required placeholder="your@email.com">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" required placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn btn-rider w-100">
                    <i class="fa fa-sign-in-alt me-2"></i>Login to Dashboard
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fa fa-info-circle me-1"></i>
                    Use your delivery staff credentials
                </small>
            </div>
        </div>
    </div>
</body>
</html>
<?php
}

// ============================================
// RIDER DASHBOARD FUNCTION
// ============================================
function showRiderDashboard($riderUser, $csrfToken) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard - UgaEats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --rider-green: #28a745;
            --rider-dark: #1e7e34;
        }
        body {
            background: #f5f5f5;
            padding-bottom: 80px;
        }
        .navbar-rider {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .status-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        .order-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .order-card.pending-accept {
            border-left: 4px solid #ffc107;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 2px 10px rgba(255, 193, 7, 0.3); }
            50% { box-shadow: 0 2px 20px rgba(255, 193, 7, 0.6); }
        }
        .order-card.active {
            border-left: 4px solid #28a745;
        }
        .order-header {
            background: #f8f9fa;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
        }
        .order-body {
            padding: 1rem;
        }
        .btn-accept {
            background: #28a745;
            color: white;
            border: none;
        }
        .btn-accept:hover {
            background: #1e7e34;
            color: white;
        }
        .btn-reject {
            background: #dc3545;
            color: white;
            border: none;
        }
        .btn-reject:hover {
            background: #c82333;
            color: white;
        }
        .status-btn {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin: 0.25rem;
        }
        .availability-toggle {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .availability-toggle .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }
        .availability-toggle .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        .tab-content {
            padding-top: 1rem;
        }
        .nav-pills .nav-link.active {
            background: #28a745;
        }
        .nav-pills .nav-link {
            color: #28a745;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        .contact-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-rider sticky-top">
        <div class="container">
            <span class="navbar-brand">
                <i class="fa fa-motorcycle me-2"></i>UgaEats Rider
            </span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="fa fa-user me-1"></i><?= htmlspecialchars($riderUser) ?>
                </span>
                <a href="?logout=1" class="btn btn-outline-light btn-sm">
                    <i class="fa fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Stats Bar -->
    <div class="container mt-3">
        <div class="row g-2">
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body py-2">
                        <div id="pendingCount" class="h4 mb-0 text-warning">0</div>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body py-2">
                        <div id="activeCount" class="h4 mb-0 text-success">0</div>
                        <small class="text-muted">Active</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-body py-2">
                        <div id="statusIndicator" class="h4 mb-0">
                            <i class="fa fa-circle text-success"></i>
                        </div>
                        <small class="text-muted">Online</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="container mt-3">
        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pending-tab">
                    <i class="fa fa-bell me-1"></i>New <span id="pendingBadge" class="badge bg-warning text-dark">0</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#active-tab">
                    <i class="fa fa-route me-1"></i>Active
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Pending Orders Tab -->
            <div class="tab-pane fade show active" id="pending-tab">
                <div id="pendingOrders">
                    <div class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <p>No pending orders</p>
                    </div>
                </div>
            </div>

            <!-- Active Orders Tab -->
            <div class="tab-pane fade" id="active-tab">
                <div id="activeOrders">
                    <div class="empty-state">
                        <i class="fa fa-route"></i>
                        <p>No active deliveries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Toggle -->
    <div class="availability-toggle">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Availability</strong>
                    <div id="availabilityText" class="text-muted small">You are online</div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="availabilitySwitch" checked onchange="toggleAvailability()">
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa fa-sync me-2"></i>Update Delivery Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="statusOrderId">
                    <p class="text-muted mb-3">Select the current status:</p>
                    <div class="d-flex flex-wrap justify-content-center">
                        <button class="btn btn-outline-primary status-btn" onclick="setStatus('picked_up')">
                            <i class="fa fa-box me-1"></i>Picked Up
                        </button>
                        <button class="btn btn-outline-info status-btn" onclick="setStatus('on_the_way')">
                            <i class="fa fa-motorcycle me-1"></i>On The Way
                        </button>
                        <button class="btn btn-outline-warning status-btn" onclick="setStatus('arriving')">
                            <i class="fa fa-map-marker-alt me-1"></i>Arriving
                        </button>
                        <button class="btn btn-success status-btn" onclick="setStatus('delivered')">
                            <i class="fa fa-check-circle me-1"></i>Delivered
                        </button>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">ETA (minutes)</label>
                        <input type="number" class="form-control" id="etaInput" placeholder="e.g., 15" min="1" max="120">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const CSRF_TOKEN = '<?= $csrfToken ?>';
    let refreshInterval;

    document.addEventListener('DOMContentLoaded', function() {
        loadStatus();
        loadOrders();
        
        // Refresh every 15 seconds
        refreshInterval = setInterval(() => {
            loadStatus();
            loadOrders();
        }, 15000);
    });

    async function loadStatus() {
        try {
            const response = await fetch('?action=get_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('pendingCount').textContent = data.pending_count;
                document.getElementById('activeCount').textContent = data.active_count;
                document.getElementById('pendingBadge').textContent = data.pending_count;
                
                const available = data.is_available;
                document.getElementById('availabilitySwitch').checked = available;
                document.getElementById('availabilityText').textContent = available ? 'You are online' : 'You are offline';
                document.getElementById('statusIndicator').innerHTML = available 
                    ? '<i class="fa fa-circle text-success"></i>'
                    : '<i class="fa fa-circle text-secondary"></i>';
            }
        } catch (error) {
            console.error('Failed to load status:', error);
        }
    }

    async function loadOrders() {
        await Promise.all([loadPendingOrders(), loadActiveOrders()]);
    }

    async function loadPendingOrders() {
        try {
            const response = await fetch('?action=get_pending_orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            const container = document.getElementById('pendingOrders');
            
            if (data.success && data.orders.length > 0) {
                container.innerHTML = data.orders.map(order => `
                    <div class="order-card pending-accept">
                        <div class="order-header d-flex justify-content-between align-items-center">
                            <strong class="text-warning"><i class="fa fa-bell me-1"></i>${order.formatted_id}</strong>
                            <span class="badge bg-warning text-dark">NEW</span>
                        </div>
                        <div class="order-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted">Customer</small>
                                    <div class="fw-bold">${order.customer_name}</div>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Amount</small>
                                    <div class="fw-bold text-success">${order.total_formatted}</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted"><i class="fa fa-store me-1"></i>Pickup</small>
                                <div>${order.restaurant}</div>
                                <small class="text-muted">${order.restaurant_address}</small>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted"><i class="fa fa-map-marker-alt me-1"></i>Deliver to</small>
                                <div>${order.address}</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-accept flex-fill" onclick="acceptOrder(${order.id})">
                                    <i class="fa fa-check me-1"></i>Accept
                                </button>
                                <button class="btn btn-reject flex-fill" onclick="rejectOrder(${order.id})">
                                    <i class="fa fa-times me-1"></i>Reject
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <p>No pending orders</p>
                        <small class="text-muted">New orders will appear here</small>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load pending orders:', error);
        }
    }

    async function loadActiveOrders() {
        try {
            const response = await fetch('?action=get_my_orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            const container = document.getElementById('activeOrders');
            
            // Filter to only accepted orders
            const activeOrders = data.success ? data.orders.filter(o => o.accepted) : [];
            
            if (activeOrders.length > 0) {
                container.innerHTML = activeOrders.map(order => `
                    <div class="order-card active">
                        <div class="order-header d-flex justify-content-between align-items-center">
                            <strong class="text-success">${order.formatted_id}</strong>
                            <span class="badge bg-success">${getStatusLabel(order.tracking_status)}</span>
                        </div>
                        <div class="order-body">
                            <div class="row mb-2">
                                <div class="col-8">
                                    <div class="fw-bold">${order.customer_name}</div>
                                    <small class="text-muted">${order.address}</small>
                                </div>
                                <div class="col-4 text-end">
                                    <a href="tel:${order.phone}" class="btn btn-outline-success contact-btn">
                                        <i class="fa fa-phone"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Restaurant: ${order.restaurant}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-light text-dark">${order.payment_method}</span>
                                    <strong class="ms-2">${order.total_formatted}</strong>
                                </div>
                                <button class="btn btn-success btn-sm" onclick="openStatusModal(${order.id})">
                                    <i class="fa fa-sync me-1"></i>Update
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa fa-route"></i>
                        <p>No active deliveries</p>
                        <small class="text-muted">Accept orders to start delivering</small>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load active orders:', error);
        }
    }

    function getStatusLabel(status) {
        const labels = {
            'confirmed': 'Confirmed',
            'preparing': 'Preparing',
            'ready': 'Ready',
            'picked_up': 'Picked Up',
            'on_the_way': 'On The Way',
            'arriving': 'Arriving',
            'delivered': 'Delivered'
        };
        return labels[status] || status;
    }

    async function acceptOrder(orderId) {
        if (!confirm('Accept this delivery?')) return;
        
        try {
            const response = await fetch('?action=accept_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                alert('✓ Order accepted! Go to Active tab to manage delivery.');
                loadOrders();
                loadStatus();
                // Switch to active tab
                document.querySelector('[data-bs-target="#active-tab"]').click();
            } else {
                alert('Error: ' + (data.error || 'Failed to accept order'));
            }
        } catch (error) {
            alert('Failed to accept order. Please try again.');
        }
    }

    async function rejectOrder(orderId) {
        const reason = prompt('Reason for rejecting (optional):');
        if (reason === null) return; // Cancelled
        
        try {
            const response = await fetch('?action=reject_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, reason: reason, csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                loadOrders();
                loadStatus();
            } else {
                alert('Error: ' + (data.error || 'Failed to reject order'));
            }
        } catch (error) {
            alert('Failed to reject order. Please try again.');
        }
    }

    function openStatusModal(orderId) {
        document.getElementById('statusOrderId').value = orderId;
        document.getElementById('etaInput').value = '';
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    async function setStatus(status) {
        const orderId = document.getElementById('statusOrderId').value;
        const eta = document.getElementById('etaInput').value;
        
        try {
            const response = await fetch('?action=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    order_id: orderId, 
                    status: status, 
                    eta_minutes: eta || null,
                    csrf_token: CSRF_TOKEN 
                })
            });
            const data = await response.json();
            
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                
                if (status === 'delivered') {
                    alert('🎉 Delivery completed! Great job!');
                }
                
                loadOrders();
                loadStatus();
            } else {
                alert('Error: ' + (data.error || 'Failed to update status'));
            }
        } catch (error) {
            alert('Failed to update status. Please try again.');
        }
    }

    async function toggleAvailability() {
        const available = document.getElementById('availabilitySwitch').checked;
        
        try {
            const response = await fetch('?action=toggle_availability', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ available: available, csrf_token: CSRF_TOKEN })
            });
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('availabilityText').textContent = available ? 'You are online' : 'You are offline';
                document.getElementById('statusIndicator').innerHTML = available 
                    ? '<i class="fa fa-circle text-success"></i>'
                    : '<i class="fa fa-circle text-secondary"></i>';
            }
        } catch (error) {
            console.error('Failed to toggle availability:', error);
        }
    }
    </script>
</body>
</html>
<?php
}
