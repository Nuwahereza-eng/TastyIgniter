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
        <div class="card-body">
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
            <form id="reservationForm" method="POST" action="{{ url('api/reservations') }}">
                @csrf
                <div class="row">
                    <!-- Calendar Column -->
                    <div class="col-md-7 mb-4">
                        <label class="form-label fw-bold">Select Date</label>
                        <div id="reservationCalendar"></div>
                        <input type="hidden" name="date" id="reservationDate" required>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Number of Guests</label>
                            <select name="guest" id="guestCount" class="form-select form-select-lg" required>
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Preferred Time</label>
                            <select name="time" id="reservationTime" class="form-select form-select-lg" required>
                                <option value="">Select a time</option>
                                @for($h = 10; $h <= 21; $h++)
                                    @foreach(['00', '30'] as $m)
                                        @php $time = sprintf('%02d:%s', $h, $m); @endphp
                                        <option value="{{ $time }}">{{ date('g:i A', strtotime($time)) }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Name</label>
                            <input type="text" name="first_name" class="form-control form-control-lg" placeholder="Enter your name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="telephone" class="form-control form-control-lg" placeholder="+256 7XX XXX XXX" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Special Requests (Optional)</label>
                            <textarea name="comment" class="form-control" rows="2" placeholder="Any special requests or notes..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                            <i class="fa fa-check me-2"></i>Confirm Reservation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flatpickr Inline Calendar Styles -->
<style>
#reservationCalendar {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
}
#reservationCalendar .flatpickr-calendar {
    box-shadow: none !important;
    width: 100% !important;
    max-width: 100% !important;
}
#reservationCalendar .flatpickr-day.selected {
    background: #FF4900 !important;
    border-color: #FF4900 !important;
}
#reservationCalendar .flatpickr-day:hover {
    background: #ffece6 !important;
}
.flatpickr-months {
    padding: 10px 0;
}
.flatpickr-current-month {
    font-size: 1.2em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr calendar
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#reservationCalendar", {
            inline: true,
            minDate: "today",
            maxDate: new Date().fp_incr(60), // 60 days from now
            dateFormat: "Y-m-d",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr) {
                document.getElementById('reservationDate').value = dateStr;
            }
        });
        // Set initial date
        document.getElementById('reservationDate').value = new Date().toISOString().split('T')[0];
    } else {
        console.error('Flatpickr not loaded!');
        // Fallback to regular date input
        document.getElementById('reservationCalendar').innerHTML = '<input type="date" name="date" class="form-control form-control-lg" required>';
    }
    
    // Form submission
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';
        
        // Show success message (in a real app, this would submit to the server)
        setTimeout(function() {
            alert('Reservation request submitted! We will confirm your booking shortly via email/SMS.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>Confirm Reservation';
        }, 1000);
    });
});
</script>
