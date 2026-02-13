---
title: 'Reservation Confirmed'
layout: default
permalink: /reservation/success
---
@php
    $reservationId = request()->get('id');
    $transactionId = request()->get('tx');
    
    $reservation = null;
    if ($reservationId) {
        $reservation = DB::table('reservations')
            ->where('reservation_id', $reservationId)
            ->first();
    }
@endphp

<div class="container pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($reservation)
                <!-- Success Header -->
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fa fa-check fa-3x"></i>
                    </div>
                    <h2 class="text-success mb-2">Reservation Confirmed!</h2>
                    <p class="text-muted">Your commitment fee has been received. We'll see you soon!</p>
                </div>

                <!-- Reservation Details Card -->
                <div class="card bg-white mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-calendar-check me-2"></i>Reservation Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="fa fa-hashtag text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Reservation ID</small>
                                        <p class="mb-0 fw-bold">#{{ $reservation->reservation_id }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="fa fa-calendar text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Date</small>
                                        <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($reservation->reserve_date)->format('l, F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="fa fa-clock text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Time</small>
                                        <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($reservation->reserve_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="fa fa-users text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Guests</small>
                                        <p class="mb-0 fw-bold">{{ $reservation->guest_num }} {{ $reservation->guest_num == 1 ? 'Guest' : 'Guests' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div class="card bg-white mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-receipt me-2"></i>Payment Receipt</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Commitment Fee</span>
                            <span class="fw-bold">UGX {{ number_format($reservation->commitment_fee) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Payment Method</span>
                            <span class="fw-bold text-capitalize">
                                @if($reservation->fee_payment_method === 'wallet')
                                    <i class="fa fa-wallet me-1"></i>Tasty Wallet
                                @elseif($reservation->fee_payment_method === 'mtn')
                                    MTN Mobile Money
                                @elseif($reservation->fee_payment_method === 'airtel')
                                    Airtel Money
                                @else
                                    {{ $reservation->fee_payment_method }}
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Transaction ID</span>
                            <span class="fw-bold font-monospace">{{ $reservation->fee_transaction_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Payment Status</span>
                            @if($reservation->fee_paid)
                                <span class="badge bg-success"><i class="fa fa-check me-1"></i>Paid</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="alert alert-info">
                    <h6><i class="fa fa-info-circle me-2"></i>Important Notes</h6>
                    <ul class="mb-0 ps-3">
                        <li>Your commitment fee of <strong>UGX {{ number_format($reservation->commitment_fee) }}</strong> will be deducted from your final bill.</li>
                        <li>Please arrive 10-15 minutes before your reservation time.</li>
                        <li>If you need to cancel, please do so at least 24 hours in advance.</li>
                        <li>A confirmation has been sent to <strong>{{ $reservation->email }}</strong>.</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-3">
                    <a href="{{ page_url('account.reservations') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fa fa-list me-2"></i>My Reservations
                    </a>
                    <a href="/" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="fa fa-home me-2"></i>Back to Home
                    </a>
                </div>
            @else
                <!-- No Reservation Found -->
                <div class="text-center py-5">
                    <i class="fa fa-exclamation-circle fa-4x text-warning mb-3"></i>
                    <h3>Reservation Not Found</h3>
                    <p class="text-muted">We couldn't find the reservation details. Please check your email for confirmation.</p>
                    <a href="/" class="btn btn-primary">
                        <i class="fa fa-home me-2"></i>Back to Home
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
