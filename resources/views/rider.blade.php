<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UgaEats Rider</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { background: #f4f5f7; }
        .rider-header { background: #ff4900; color: #fff; }
        #riderMap { height: 260px; border-radius: .75rem; }
        .share-on { background: #198754 !important; border-color: #198754 !important; }
        .delivery-card.selected { outline: 3px solid #ff4900; }
        .status-btn { min-height: 48px; }
    </style>
</head>
<body>
<header class="rider-header py-3 mb-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold"><i class="fa fa-motorcycle me-2"></i>UgaEats Rider</div>
            <small>{{ $rider->name }}</small>
        </div>
        <button id="shareBtn" class="btn btn-light btn-sm fw-bold" type="button">
            <i class="fa fa-location-crosshairs me-1"></i><span>Share location</span>
        </button>
    </div>
</header>

<main class="container pb-5">
    <div id="shareStatus" class="alert alert-secondary small py-2">
        Location sharing is off. Tap <strong>Share location</strong> when you set off, and keep this page open while delivering.
    </div>

    <div id="riderMap" class="mb-3"></div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Your deliveries</h6>
        <button class="btn btn-link btn-sm" type="button" onclick="loadDeliveries()"><i class="fa fa-rotate me-1"></i>Refresh</button>
    </div>
    <div id="deliveries"><p class="text-muted">Loading…</p></div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const KAMPALA = [0.3476, 32.5825];
const SEND_EVERY_MS = 10000;

let deliveries = [];
let selectedId = null;
let watchId = null;
let lastSentAt = 0;
let riderPos = null;

const map = L.map('riderMap').setView(KAMPALA, 13);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors',
}).addTo(map);
const layers = L.layerGroup().addTo(map);

function icon(cls, color) {
    return L.divIcon({
        className: '',
        html: `<div style="background:${color};color:#fff;width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.35)"><i class="fa ${cls}"></i></div>`,
        iconSize: [34, 34], iconAnchor: [17, 17],
    });
}

async function api(url, method = 'GET', body = null) {
    const res = await fetch(url, {
        method,
        headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
        credentials: 'same-origin',
        body: body ? JSON.stringify(body) : null,
    });
    if (res.status === 401) { window.location.href = '{{ admin_url('login') }}'; return {}; }
    return res.json();
}

async function loadDeliveries() {
    const data = await api('/rider/deliveries');
    deliveries = data.deliveries || [];
    if (!deliveries.some(d => d.order_id === selectedId)) {
        selectedId = deliveries.length ? deliveries[0].order_id : null;
    }
    renderDeliveries();
    renderMap();
}

function selected() {
    return deliveries.find(d => d.order_id === selectedId) || null;
}

function esc(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function renderDeliveries() {
    const el = document.getElementById('deliveries');
    if (!deliveries.length) {
        el.innerHTML = '<div class="card"><div class="card-body text-center text-muted">No deliveries assigned to you right now.</div></div>';
        return;
    }

    el.innerHTML = deliveries.map(d => {
        const phone = (d.customer_phone || '').replace(/[^0-9+]/g, '');
        const wa = phone.replace(/^\+/, '').replace(/^0/, '256');
        const nav = d.delivery
            ? `https://www.google.com/maps/dir/?api=1&destination=${d.delivery.lat},${d.delivery.lng}&travelmode=two-wheeler`
            : `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((d.address || '') + ', Uganda')}`;
        return `
        <div class="card mb-3 delivery-card ${d.order_id === selectedId ? 'selected' : ''}" onclick="selectDelivery(${d.order_id})">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <strong>${esc(d.formatted_id)}</strong>
                    <span class="badge bg-warning text-dark">${esc(d.status_label)}</span>
                </div>
                <div class="small text-muted mb-2"><i class="fa fa-store me-1"></i>${esc(d.restaurant.name)}</div>
                <div><i class="fa fa-user me-1"></i>${esc(d.customer_name)}</div>
                <div class="small"><i class="fa fa-location-dot me-1"></i>${esc(d.address || 'No address')}</div>
                ${d.comment ? `<div class="small fst-italic mt-1">“${esc(d.comment)}”</div>` : ''}
                <div class="small mt-1"><strong>${esc(d.total)}</strong> · ${esc(d.payment || '')}</div>
                <div class="d-flex gap-2 mt-3">
                    <a class="btn btn-outline-primary btn-sm flex-fill" href="tel:${esc(phone)}" onclick="event.stopPropagation()"><i class="fa fa-phone"></i> Call</a>
                    <a class="btn btn-outline-success btn-sm flex-fill" href="https://wa.me/${esc(wa)}" target="_blank" onclick="event.stopPropagation()"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a class="btn btn-outline-dark btn-sm flex-fill" href="${nav}" target="_blank" onclick="event.stopPropagation()"><i class="fa fa-diamond-turn-right"></i> Navigate</a>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-6"><button class="btn btn-secondary w-100 status-btn" onclick="setStatus(event, ${d.order_id}, 'picked_up')">Picked up</button></div>
                    <div class="col-6"><button class="btn btn-primary w-100 status-btn" onclick="setStatus(event, ${d.order_id}, 'on_the_way')">On the way</button></div>
                    <div class="col-6"><button class="btn btn-info w-100 status-btn" onclick="setStatus(event, ${d.order_id}, 'nearby')">Almost there</button></div>
                    <div class="col-6"><button class="btn btn-success w-100 status-btn" onclick="setStatus(event, ${d.order_id}, 'delivered')">Delivered</button></div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function renderMap() {
    layers.clearLayers();
    const d = selected();
    const points = [];
    if (d && d.restaurant.coordinates) {
        const p = [d.restaurant.coordinates.lat, d.restaurant.coordinates.lng];
        L.marker(p, {icon: icon('fa-store', '#ff4900')}).bindPopup(esc(d.restaurant.name)).addTo(layers);
        points.push(p);
    }
    if (d && d.delivery) {
        const p = [d.delivery.lat, d.delivery.lng];
        L.marker(p, {icon: icon('fa-house', '#0d6efd')}).bindPopup(esc(d.address)).addTo(layers);
        points.push(p);
    }
    if (riderPos) {
        L.marker(riderPos, {icon: icon('fa-motorcycle', '#198754')}).bindPopup('You').addTo(layers);
        points.push(riderPos);
    }
    if (points.length > 1) map.fitBounds(points, {padding: [30, 30]});
    else if (points.length === 1) map.setView(points[0], 15);
}

function selectDelivery(orderId) {
    selectedId = orderId;
    renderDeliveries();
    renderMap();
}

async function setStatus(event, orderId, status) {
    event.stopPropagation();
    if (status === 'delivered' && !confirm('Mark this order as delivered?')) return;
    const res = await api(`/rider/deliveries/${orderId}/status`, 'POST', {status});
    if (res.success && (status === 'picked_up' || status === 'on_the_way') && watchId === null) {
        startSharing();
    }
    loadDeliveries();
}

function setShareStatus(text, cls) {
    const el = document.getElementById('shareStatus');
    el.className = `alert ${cls} small py-2`;
    el.innerHTML = text;
}

async function sendPosition(force = false) {
    const d = selected();
    if (!riderPos || !d) return;
    if (!force && Date.now() - lastSentAt < SEND_EVERY_MS) return;
    lastSentAt = Date.now();
    try {
        const res = await api(`/rider/deliveries/${d.order_id}/location`, 'POST', {lat: riderPos[0], lng: riderPos[1]});
        const eta = res.eta_minutes ? ` · ETA about ${res.eta_minutes} min` : '';
        setShareStatus(`<i class="fa fa-satellite-dish me-1"></i>Sharing your location with the customer of <strong>${esc(d.formatted_id)}</strong>${eta}. Last sent ${new Date().toLocaleTimeString()}.`, 'alert-success');
    } catch (e) {
        setShareStatus('Could not send your location. Check your internet connection.', 'alert-warning');
    }
}

function startSharing() {
    if (!navigator.geolocation) {
        setShareStatus('This phone does not support location sharing.', 'alert-danger');
        return;
    }
    watchId = navigator.geolocation.watchPosition(pos => {
        riderPos = [pos.coords.latitude, pos.coords.longitude];
        renderMap();
        sendPosition(lastSentAt === 0);
    }, err => {
        const msg = err.code === err.PERMISSION_DENIED
            ? 'Location permission denied. Allow location for this site in your browser settings.'
            : 'Waiting for GPS signal…';
        setShareStatus(msg, 'alert-warning');
    }, {enableHighAccuracy: true, maximumAge: 5000, timeout: 20000});

    const btn = document.getElementById('shareBtn');
    btn.classList.add('share-on', 'text-white');
    btn.querySelector('span').textContent = 'Sharing';
    setShareStatus('Getting your GPS position…', 'alert-info');
}

function stopSharing() {
    if (watchId !== null) navigator.geolocation.clearWatch(watchId);
    watchId = null;
    lastSentAt = 0;
    const btn = document.getElementById('shareBtn');
    btn.classList.remove('share-on', 'text-white');
    btn.querySelector('span').textContent = 'Share location';
    setShareStatus('Location sharing is off.', 'alert-secondary');
}

document.getElementById('shareBtn').addEventListener('click', () => watchId === null ? startSharing() : stopSharing());

// Send the latest position even if the phone has not moved enough to fire watchPosition.
setInterval(() => { if (watchId !== null) sendPosition(); }, SEND_EVERY_MS);
setInterval(loadDeliveries, 60000);
loadDeliveries();
</script>
</body>
</html>
