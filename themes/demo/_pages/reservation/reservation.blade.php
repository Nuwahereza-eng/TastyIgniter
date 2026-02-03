---
title: 'Book a Table'
layout: default
permalink: ':location/reservation'
---
@php
    $location = \Igniter\Local\Facades\Location::current();
    $locationName = $location ? $location->getName() : 'Restaurant';
@endphp

<div class="container pt-4 pb-5">
    <div class="card mb-3 bg-white">
        <div class="card-body py-2">
            <a class="text-decoration-none" href="{{ page_url('reservations') }}">
                <i class="fa fa-arrow-left"></i> Back to Restaurants
            </a>
        </div>
    </div>
    
    <div class="card bg-white">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fa fa-calendar-check me-2"></i>Reserve a Table at {{ $locationName }}</h4>
        </div>
        <div class="card-body">
            <form id="reservationForm">
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
                            <input type="text" name="first_name" class="form-control form-control-lg" placeholder="Enter your name" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="you@email.com" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="tel" name="telephone" class="form-control" placeholder="+256 7XX XXX" required>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="comment" class="form-control" rows="2" placeholder="Allergies, occasion, seating preference..."></textarea>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                    <i class="fa fa-check me-2"></i>Confirm Reservation
                </button>
            </form>
        </div>
    </div>
</div>

<style>
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
.flatpickr-weekday {
    color: white !important;
}
span.flatpickr-weekday {
    color: white !important;
}
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month {
    fill: white !important;
    color: white !important;
}
.flatpickr-months .flatpickr-prev-month:hover svg, 
.flatpickr-months .flatpickr-next-month:hover svg {
    fill: #fff !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr as a popup calendar
    if (typeof flatpickr !== 'undefined') {
        var fp = flatpickr("#reservationDatePicker", {
            minDate: "today",
            maxDate: new Date().fp_incr(60),
            dateFormat: "l, F j, Y",
            altInput: false,
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('reservationDate').value = instance.formatDate(selectedDates[0], "Y-m-d");
            }
        });
        // Set initial date
        document.getElementById('reservationDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('reservationDatePicker').value = fp.formatDate(new Date(), "l, F j, Y");
    }
    
    // Form submission
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';
        
        setTimeout(function() {
            alert('✓ Reservation request submitted!\n\nWe will confirm your booking shortly via email/SMS.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>Confirm Reservation';
        }, 1000);
    });
});
</script>
