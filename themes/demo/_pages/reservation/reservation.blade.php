---
title: 'Book a Table'
layout: default
permalink: ':location/reservation'
---
@php
    $location = \Igniter\Local\Facades\Location::current();
    $locationId = $location ? $location->getKey() : 1;
    $locationName = $location ? $location->getName() : 'Restaurant';

    $customer = \Igniter\User\Facades\Auth::customer();
    $walletBalance = 0;
    $isLoggedIn = false;

    if ($customer) {
        $isLoggedIn = true;
        $wallet = \App\Models\TastyWallet::getOrCreateForCustomer($customer->customer_id);
        $walletBalance = $wallet->balance;
    }

    $feePerGuest = \App\Http\Controllers\ReservationController::FEE_PER_GUEST; // UGX 20,000 / guest
    $defaultGuests = 2;
    $commitmentFee = $feePerGuest * $defaultGuests;
    $isTrusted = \App\Http\Controllers\ReservationController::isTrustedCustomer($customer);
@endphp

<div class="container pt-4 pb-5">
    <div class="card mb-3 bg-white">
        <div class="card-body py-2">
            <a 
                class="text-decoration-none back-button d-inline-flex align-items-center px-3 py-2 rounded" 
                href="{{ page_url('reservations') }}"
                style="background: #f8f9fa; color: #495057; font-weight: 500;"
            >
                <i class="fa fa-arrow-left me-2" style="color: #FF4900;"></i> Back to Restaurants
            </a>
        </div>
    </div>
    
    <div class="card bg-white">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fa fa-calendar-check me-2"></i>Reserve a Table at {{ $locationName }}</h4>
        </div>
        <div class="card-body">
            <!-- Progress Steps -->
            <div class="d-flex justify-content-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="step-circle active" id="step1-indicator">1</div>
                    <span class="ms-2 me-4 fw-medium">Details</span>
                    <div class="step-line"></div>
                    <div class="step-circle" id="step2-indicator">2</div>
                    <span class="ms-2 fw-medium">Payment</span>
                </div>
            </div>

            <!-- Step 1: Reservation Details -->
            <div id="step1" class="step-content">
                <form id="reservationStep1">
                    <div class="row g-4">
                        <!-- Left Column: Date & Time -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-2">Select Date</label>
                                <input type="text" id="reservationDatePicker" class="form-control form-control-lg" placeholder="Click to select date" readonly required>
                                <input type="hidden" name="date" id="reservationDate">
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Guests</label>
                                    <select name="guest" class="form-select form-select-lg" required>
                                        @for($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">Time</label>
                                    <select name="time" class="form-select form-select-lg" required>
                                        @for($h = 10; $h <= 21; $h++)
                                            @foreach(['00', '30'] as $m)
                                                @php $time = sprintf('%02d:%s', $h, $m); @endphp
                                                <option value="{{ $time }}" {{ $time == '12:00' ? 'selected' : '' }}>{{ date('g:i A', strtotime($time)) }}</option>
                                            @endforeach
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Contact Details -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Name</label>
                                <input type="text" name="first_name" class="form-control form-control-lg" placeholder="Enter your name" value="{{ $customer ? $customer->first_name : '' }}" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="you@email.com" value="{{ $customer ? $customer->email : '' }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input type="tel" name="telephone" class="form-control" placeholder="+256 7XX XXX" value="{{ $customer ? $customer->telephone : '' }}" required>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <label class="form-label fw-bold">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="comment" class="form-control" rows="2" placeholder="Allergies, occasion, seating preference..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Commitment Fee Notice -->
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fa fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Commitment Fee Required</strong><br>
                            A non-refundable commitment fee of <strong>UGX {{ number_format($commitmentFee) }}</strong> is required to confirm your reservation. This amount will be deducted from your final bill.
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fa fa-arrow-right me-2"></i>Continue to Payment
                    </button>
                </form>
            </div>

            <!-- Step 2: Payment -->
            <div id="step2" class="step-content" style="display: none;">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Reservation Summary -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fa fa-receipt me-2"></i>Reservation Summary</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Date</p>
                                        <p class="fw-bold" id="summary-date">-</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Time</p>
                                        <p class="fw-bold" id="summary-time">-</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Guests</p>
                                        <p class="fw-bold" id="summary-guests">-</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Restaurant</p>
                                        <p class="fw-bold">{{ $locationName }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fs-5 d-block">Commitment Fee</span>
                                        <small class="text-muted">UGX {{ number_format($feePerGuest) }} &times; <span id="summary-guests-count">{{ $defaultGuests }}</span> guest(s)</small>
                                    </div>
                                    <span class="fs-4 fw-bold text-primary" id="summary-fee">UGX {{ number_format($commitmentFee) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <h5 class="mb-3"><i class="fa fa-credit-card me-2"></i>Select Payment Method</h5>

                        <form id="paymentForm"
                              data-fee-per-guest="{{ $feePerGuest }}"
                              data-is-trusted="{{ $isTrusted ? '1' : '0' }}">
                            <input type="hidden" name="location_id" value="{{ $locationId }}">

                            @if(!$isTrusted)
                                <div class="alert alert-info py-2">
                                    <i class="fa fa-info-circle me-2"></i>
                                    First-time guests pay the commitment fee online via Marz. After {{ \App\Http\Controllers\ReservationController::TRUSTED_ORDERS_THRESHOLD }} completed orders you'll also be able to pay at the venue.
                                </div>
                            @endif
                            
                            <div class="row g-3 mb-4">
                                <!-- Tasty Wallet -->
                                <div class="col-12" id="walletOptionWrap" data-balance="{{ $walletBalance }}">
                                    <label class="payment-option w-100 {{ $walletBalance >= $commitmentFee ? '' : 'disabled' }}" id="walletOptionLabel">
                                        <input type="radio" name="payment_method" value="wallet" {{ $walletBalance >= $commitmentFee ? '' : 'disabled' }}>
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <i class="fa fa-wallet fa-2x me-3 text-primary"></i>
                                            <div class="flex-grow-1">
                                                <strong>Tasty Wallet</strong>
                                                <br>
                                                <small class="text-muted">Balance: UGX {{ number_format($walletBalance) }}</small>
                                            </div>
                                            <span class="badge" id="walletBadge">{{ $walletBalance >= $commitmentFee ? 'Available' : 'Insufficient' }}</span>
                                        </div>
                                    </label>
                                </div>

                                @if($isTrusted)
                                <!-- Pay at Venue (trusted customers only) -->
                                <div class="col-12">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="cash_at_venue">
                                        <div class="payment-card d-flex align-items-center p-3 border rounded" style="background: #f5fff6; border-color: #b6e2c1 !important;">
                                            <i class="fa fa-handshake fa-2x me-3 text-success"></i>
                                            <div class="flex-grow-1">
                                                <strong>Pay at Venue</strong>
                                                <br>
                                                <small class="text-muted">Trusted customer privilege &mdash; settle the fee when you arrive</small>
                                            </div>
                                            <span class="badge bg-success">Trusted</span>
                                        </div>
                                    </label>
                                </div>
                                @endif

                                <!-- MTN Mobile Money -->
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="mtn" {{ !$isTrusted ? 'checked' : '' }}>
                                        <div class="payment-card d-flex align-items-center p-3 rounded" style="background: #fffde7; border: 2px solid #ffcc00;">
                                            <img src="/images/payments/mtn.png" alt="MTN" style="width: 40px; height: 40px; object-fit: contain;" class="me-3">
                                            <div>
                                                <strong>MTN MoMo</strong>
                                                <br>
                                                <small class="text-muted">via Marz</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Airtel Money -->
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="airtel">
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <img src="/images/payments/airtel.png" alt="Airtel" style="width: 40px; height: 40px; object-fit: contain;" class="me-3">
                                            <div>
                                                <strong>Airtel Money</strong>
                                                <br>
                                                <small class="text-muted">via Marz</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Marz Card / Wallet -->
                                <div class="col-12 mt-3">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="marz">
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <img src="/images/payments/marz.png" alt="Marz" style="width: 40px; height: 40px; object-fit: contain;" class="me-3" onerror="this.style.display='none'">
                                            <div>
                                                <strong>Card / Marz Wallet</strong>
                                                <br>
                                                <small class="text-muted">Pay by card on Marz hosted checkout</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @if(!$isLoggedIn)
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <a href="{{ page_url('account.login') }}">Login</a> to use Tasty Wallet and earn 5% cashback on your orders!
                                </div>
                            @endif

                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="goToStep(1)">
                                    <i class="fa fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" id="payBtn">
                                    <i class="fa fa-lock me-2"></i><span id="payBtnLabel">Pay UGX {{ number_format($commitmentFee) }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.step-circle.active {
    background: #FF4900;
    color: white;
}
.step-line {
    width: 60px;
    height: 2px;
    background: #e9ecef;
    margin: 0 10px;
}
.payment-option input[type="radio"] {
    display: none;
}
.payment-option .payment-card {
    cursor: pointer;
    transition: all 0.2s;
}
.payment-option input[type="radio"]:checked + .payment-card {
    border-color: #FF4900 !important;
    background: #fff8f5;
    box-shadow: 0 0 0 2px #FF4900;
}
.payment-option.disabled {
    opacity: 0.6;
    pointer-events: none;
}
.flatpickr-calendar {
    box-shadow: 0 3px 13px rgba(0,0,0,0.08) !important;
}
.flatpickr-day.selected, .flatpickr-day.selected:hover {
    background: #FF4900 !important;
    border-color: #FF4900 !important;
}
.flatpickr-day:hover {
    background: #ffece6 !important;
}
.flatpickr-months .flatpickr-month {
    background: #FF4900 !important;
    color: white !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
    color: white !important;
}
.flatpickr-weekdays {
    background: #FF4900 !important;
}
.flatpickr-weekday, span.flatpickr-weekday {
    color: white !important;
}
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month {
    fill: white !important;
    color: white !important;
}
</style>

<script>
var reservationData = {};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr
    if (typeof flatpickr !== 'undefined') {
        var fp = flatpickr("#reservationDatePicker", {
            minDate: "today",
            maxDate: new Date().fp_incr(60),
            dateFormat: "l, F j, Y",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('reservationDate').value = instance.formatDate(selectedDates[0], "Y-m-d");
            }
        });
        document.getElementById('reservationDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('reservationDatePicker').value = fp.formatDate(new Date(), "l, F j, Y");
    }
    
    // Step 1 form submission
    document.getElementById('reservationStep1').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect form data
        var formData = new FormData(this);
        reservationData = {
            date: document.getElementById('reservationDate').value,
            time: formData.get('time'),
            guest: formData.get('guest'),
            first_name: formData.get('first_name'),
            email: formData.get('email'),
            telephone: formData.get('telephone'),
            comment: formData.get('comment') || ''
        };
        
        // Update summary
        document.getElementById('summary-date').textContent = document.getElementById('reservationDatePicker').value;
        document.getElementById('summary-time').textContent = formatTime(reservationData.time);
        document.getElementById('summary-guests').textContent = reservationData.guest + (reservationData.guest == 1 ? ' Guest' : ' Guests');

        // Recompute commitment fee for the chosen guest count
        refreshCommitmentFee(parseInt(reservationData.guest, 10) || 1);

        // Go to step 2
        goToStep(2);
    });

    // Recalculate commitment fee (UGX <feePerGuest> x guests) and update UI
    function refreshCommitmentFee(guests) {
        var form = document.getElementById('paymentForm');
        var perGuest = parseInt(form.getAttribute('data-fee-per-guest'), 10) || 0;
        var fee = perGuest * Math.max(1, guests);
        var formatted = 'UGX ' + fee.toLocaleString('en-US');

        var feeEl = document.getElementById('summary-fee');
        if (feeEl) feeEl.textContent = formatted;

        var guestsEl = document.getElementById('summary-guests-count');
        if (guestsEl) guestsEl.textContent = guests;

        var lbl = document.getElementById('payBtnLabel');
        if (lbl) lbl.textContent = 'Pay ' + formatted;

        // Adjust wallet option availability
        var wrap = document.getElementById('walletOptionWrap');
        if (wrap) {
            var balance = parseInt(wrap.getAttribute('data-balance'), 10) || 0;
            var label = document.getElementById('walletOptionLabel');
            var radio = label ? label.querySelector('input[type="radio"]') : null;
            var badge = document.getElementById('walletBadge');
            if (balance >= fee) {
                if (label) label.classList.remove('disabled');
                if (radio) radio.disabled = false;
                if (badge) { badge.textContent = 'Available'; badge.className = 'badge bg-success'; }
            } else {
                if (label) label.classList.add('disabled');
                if (radio) {
                    radio.disabled = true;
                    if (radio.checked) {
                        radio.checked = false;
                        var fallback = document.querySelector('input[name="payment_method"][value="mtn"]');
                        if (fallback) fallback.checked = true;
                    }
                }
                if (badge) { badge.textContent = 'Insufficient'; badge.className = 'badge bg-danger'; }
            }
        }
        return fee;
    }
    
    // Payment form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';
        
        var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        var locationId = document.querySelector('input[name="location_id"]').value;
        
        // Combine all data
        var fullData = {
            ...reservationData,
            location_id: locationId,
            payment_method: paymentMethod
        };
        
        fetch('/ajax/reservation/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(fullData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Card flow → Marz hosted checkout (full-page redirect).
                if (data.redirect && data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }

                // Mobile money pending → keep the user here and poll until the
                // reservation is confirmed (USSD push approved) or fails.
                if (data.pending && data.reservation && data.reservation.id) {
                    pollReservationPayment(data.reservation.id, data.message, data.reservation.commitment_fee);
                    return;
                }

                // Wallet / Cash-at-venue / fully-paid response → straight to success.
                window.location.href = '/reservation/success?id=' + data.reservation.id
                    + '&tx=' + (data.reservation.transaction_id || '');
            } else {
                alert(data.message || 'Payment failed. Please try again.');
                btn.disabled = false;
                var labelEl = document.getElementById('payBtnLabel');
                var labelText = labelEl ? labelEl.textContent : 'Pay';
                btn.innerHTML = '<i class="fa fa-lock me-2"></i><span id="payBtnLabel">' + labelText + '</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            btn.disabled = false;
            var labelEl2 = document.getElementById('payBtnLabel');
            var labelText2 = labelEl2 ? labelEl2.textContent : 'Pay';
            btn.innerHTML = '<i class="fa fa-lock me-2"></i><span id="payBtnLabel">' + labelText2 + '</span>';
        });
    });
});

// Poll the reservation status endpoint while a Mobile Money USSD push is
// outstanding. The status endpoint actively re-verifies with Marz so the
// flow works even when the Marz webhook can't reach the server in dev.
function pollReservationPayment(reservationId, message, amount) {
    var overlay = document.createElement('div');
    overlay.id = 'momoOverlay';
    overlay.innerHTML = ''
        + '<div style="position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;">'
        + '  <div style="background:#fff;border-radius:12px;max-width:460px;width:100%;padding:28px;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,.25);">'
        + '    <i class="fa fa-mobile-alt fa-3x text-primary mb-3"></i>'
        + '    <h4 class="mb-2">Approve on your phone</h4>'
        + '    <p class="text-muted mb-3" id="momoMessage"></p>'
        + '    <div class="d-flex justify-content-center align-items-center mb-3">'
        + '      <div class="spinner-border text-primary me-2" role="status" style="width:1.5rem;height:1.5rem;"></div>'
        + '      <span id="momoStatus" class="fw-medium">Waiting for confirmation…</span>'
        + '    </div>'
        + '    <small class="text-muted d-block mb-3">This page will refresh automatically once the payment is received. Keep it open.</small>'
        + '    <button type="button" class="btn btn-link" id="momoCancel">Cancel</button>'
        + '  </div>'
        + '</div>';
    document.body.appendChild(overlay);
    document.getElementById('momoMessage').textContent = message
        || ('A payment prompt for UGX ' + (amount || 0).toLocaleString('en-US') + ' has been sent to your phone.');

    var stopped = false;
    document.getElementById('momoCancel').addEventListener('click', function() {
        stopped = true;
        overlay.remove();
        var btn = document.getElementById('payBtn');
        if (btn) {
            btn.disabled = false;
            var lbl = document.getElementById('payBtnLabel');
            var lblText = lbl ? lbl.textContent : 'Pay';
            btn.innerHTML = '<i class="fa fa-lock me-2"></i><span id="payBtnLabel">' + lblText + '</span>';
        }
    });

    var attempts = 0;
    var maxAttempts = 60; // ~2 minutes at 2s interval
    function tick() {
        if (stopped) return;
        attempts++;
        fetch('/ajax/reservation/status/' + reservationId, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(j) {
                if (stopped) return;
                if (j.success && j.reservation) {
                    if (j.reservation.fee_paid) {
                        document.getElementById('momoStatus').textContent = 'Payment received! Redirecting…';
                        window.location.href = '/reservation/success?id=' + reservationId
                            + '&tx=' + (j.reservation.fee_transaction_id || '')
                            + '&status=paid';
                        return;
                    }
                    var ps = (j.reservation.payment_status || '').toLowerCase();
                    if (ps === 'failed' || ps === 'cancelled') {
                        document.getElementById('momoStatus').textContent = 'Payment was ' + ps + '.';
                        setTimeout(function() {
                            overlay.remove();
                            alert('The Mobile Money payment was not completed. Please try again.');
                            var btn = document.getElementById('payBtn');
                            if (btn) {
                                btn.disabled = false;
                                var lbl = document.getElementById('payBtnLabel');
                                var lblText = lbl ? lbl.textContent : 'Pay';
                                btn.innerHTML = '<i class="fa fa-lock me-2"></i><span id="payBtnLabel">' + lblText + '</span>';
                            }
                        }, 800);
                        return;
                    }
                }
                if (attempts >= maxAttempts) {
                    document.getElementById('momoStatus').textContent = 'Still waiting… you can close this and check your reservation later.';
                    return;
                }
                setTimeout(tick, 2000);
            })
            .catch(function() {
                if (attempts >= maxAttempts || stopped) return;
                setTimeout(tick, 3000);
            });
    }
    setTimeout(tick, 2500);
}

function goToStep(step) {
    document.getElementById('step1').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('step2').style.display = step === 2 ? 'block' : 'none';
    
    document.getElementById('step1-indicator').classList.toggle('active', step >= 1);
    document.getElementById('step2-indicator').classList.toggle('active', step >= 2);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function formatTime(time) {
    var parts = time.split(':');
    var hours = parseInt(parts[0]);
    var minutes = parts[1];
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    return hours + ':' + minutes + ' ' + ampm;
}
</script>
