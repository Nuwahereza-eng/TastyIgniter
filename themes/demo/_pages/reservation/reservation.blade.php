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
    
    $commitmentFee = 10000; // UGX 10,000
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
                                    <span class="fs-5">Commitment Fee</span>
                                    <span class="fs-4 fw-bold text-primary">UGX {{ number_format($commitmentFee) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <h5 class="mb-3"><i class="fa fa-credit-card me-2"></i>Select Payment Method</h5>
                        
                        <form id="paymentForm">
                            <input type="hidden" name="location_id" value="{{ $locationId }}">
                            
                            <div class="row g-3 mb-4">
                                <!-- Tasty Wallet -->
                                <div class="col-12">
                                    <label class="payment-option w-100 {{ $walletBalance >= $commitmentFee ? '' : 'disabled' }}">
                                        <input type="radio" name="payment_method" value="wallet" {{ $walletBalance >= $commitmentFee ? '' : 'disabled' }}>
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <i class="fa fa-wallet fa-2x me-3 text-primary"></i>
                                            <div class="flex-grow-1">
                                                <strong>Tasty Wallet</strong>
                                                <br>
                                                <small class="text-muted">Balance: UGX {{ number_format($walletBalance) }}</small>
                                            </div>
                                            @if($walletBalance >= $commitmentFee)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Insufficient</span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- MTN Mobile Money -->
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="mtn" checked>
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg" alt="MTN" style="width: 40px; height: 40px; object-fit: contain;" class="me-3">
                                            <div>
                                                <strong>MTN MoMo</strong>
                                                <br>
                                                <small class="text-muted">Mobile Money</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Airtel Money -->
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="payment_method" value="airtel">
                                        <div class="payment-card d-flex align-items-center p-3 border rounded">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/77/Airtel_Money.png" alt="Airtel" style="width: 40px; height: 40px; object-fit: contain;" class="me-3">
                                            <div>
                                                <strong>Airtel Money</strong>
                                                <br>
                                                <small class="text-muted">Mobile Money</small>
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
                                    <i class="fa fa-lock me-2"></i>Pay UGX {{ number_format($commitmentFee) }}
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
        
        // Go to step 2
        goToStep(2);
    });
    
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
                // Redirect to success page
                window.location.href = '/reservation/success?id=' + data.reservation.id + '&tx=' + data.reservation.transaction_id;
            } else {
                alert(data.message || 'Payment failed. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-lock me-2"></i>Pay UGX 10,000';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-lock me-2"></i>Pay UGX 10,000';
        });
    });
});

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
