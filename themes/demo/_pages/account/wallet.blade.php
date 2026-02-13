---
title: 'Tasty Wallet'
layout: default
permalink: /account/wallet
security: customer

'[igniter-orange::account-settings]': []
---
@php
    $customer = \Igniter\User\Facades\Auth::customer();
    $wallet = null;
    $transactions = collect();
    
    if ($customer) {
        $wallet = \App\Models\TastyWallet::getOrCreateForCustomer($customer->customer_id);
        $transactions = $wallet->transactions()->orderBy('created_at', 'desc')->take(20)->get();
    }
@endphp

<div class="container">
    <div class="row py-5">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu" />
        </div>

        <div class="col-sm-10">
            <!-- Wallet Balance Card -->
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #FF4900 0%, #FF6B35 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-wallet fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0 opacity-75">Tasty Wallet Balance</h6>
                                    <h2 class="mb-0 fw-bold" id="wallet-balance">
                                        UGX {{ $wallet ? number_format($wallet->balance, 0) : '0' }}
                                    </h2>
                                </div>
                            </div>
                            <p class="mb-0 opacity-75">
                                <i class="fa fa-gift me-1"></i> Pay with wallet and earn 5% cashback on every order!
                            </p>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#depositModal">
                                    <i class="fa fa-plus me-2"></i> Deposit Funds
                                </button>
                                <button class="btn btn-outline-light w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                                    <i class="fa fa-minus me-2"></i> Withdraw Funds
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa fa-arrow-down fa-2x text-success"></i>
                            </div>
                            <h6>Total Deposits</h6>
                            <h5 class="text-success mb-0">
                                UGX {{ number_format($transactions->where('type', 'deposit')->sum('amount'), 0) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa fa-shopping-cart fa-2x text-warning"></i>
                            </div>
                            <h6>Orders Paid</h6>
                            <h5 class="text-warning mb-0">
                                UGX {{ number_format($transactions->where('type', 'payment')->sum('amount'), 0) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa fa-gift fa-2x text-info"></i>
                            </div>
                            <h6>Cashback Earned</h6>
                            <h5 class="text-info mb-0">
                                UGX {{ number_format($transactions->where('type', 'cashback')->sum('amount'), 0) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fa fa-history me-2"></i>Transaction History</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Reference</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Balance</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $tx)
                                        <tr>
                                            <td>
                                                <span class="d-inline-flex align-items-center">
                                                    <i class="fa {{ $tx->getTypeIcon() }} me-2"></i>
                                                    {{ $tx->getTypeLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $tx->description }}</td>
                                            <td><code class="small">{{ $tx->reference }}</code></td>
                                            <td class="text-end fw-bold {{ $tx->isCredit() ? 'text-success' : 'text-danger' }}">
                                                {{ $tx->isCredit() ? '+' : '-' }} UGX {{ number_format($tx->amount, 0) }}
                                            </td>
                                            <td class="text-end">UGX {{ number_format($tx->balance_after, 0) }}</td>
                                            <td class="text-muted small">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No transactions yet</h5>
                            <p class="text-muted">Make a deposit to get started!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fa fa-plus-circle text-success me-2"></i>Deposit to Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="depositForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount (UGX)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">UGX</span>
                            <input type="number" class="form-control" name="amount" min="1000" max="5000000" placeholder="Enter amount" required>
                        </div>
                        <div class="form-text">Minimum: UGX 1,000 | Maximum: UGX 5,000,000</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quick Amount</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="5000">5,000</button>
                            <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="10000">10,000</button>
                            <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="20000">20,000</button>
                            <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="50000">50,000</button>
                            <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="100000">100,000</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="payment-option w-100">
                                    <input type="radio" name="payment_method" value="mobilemoney" class="btn-check" checked>
                                    <div class="btn btn-outline-warning w-100 py-3">
                                        <i class="fa fa-mobile-alt fa-2x mb-2 d-block"></i>
                                        <strong>Mobile Money</strong>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="payment-option w-100">
                                    <input type="radio" name="payment_method" value="flutterwave" class="btn-check">
                                    <div class="btn btn-outline-primary w-100 py-3">
                                        <i class="fa fa-credit-card fa-2x mb-2 d-block"></i>
                                        <strong>Card</strong>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="mobilemoney-fields">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Provider</label>
                            <div class="d-flex gap-2">
                                <label class="flex-fill">
                                    <input type="radio" name="provider" value="mtn" class="btn-check" checked>
                                    <span class="btn btn-outline-warning w-100">MTN</span>
                                </label>
                                <label class="flex-fill">
                                    <input type="radio" name="provider" value="airtel" class="btn-check">
                                    <span class="btn btn-outline-danger w-100">Airtel</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+256</span>
                                <input type="tel" class="form-control" name="phone_number" placeholder="7XXXXXXXX" maxlength="9">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        <strong>Test Mode:</strong> Deposits are simulated. No actual charges.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fa fa-plus me-1"></i> Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fa fa-minus-circle text-danger me-2"></i>Withdraw from Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="withdrawForm">
                <div class="modal-body">
                    <div class="alert alert-secondary">
                        <div class="d-flex justify-content-between">
                            <span>Available Balance:</span>
                            <strong>UGX {{ $wallet ? number_format($wallet->balance, 0) : '0' }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount (UGX)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">UGX</span>
                            <input type="number" class="form-control" name="amount" min="1000" max="{{ $wallet ? $wallet->balance : 0 }}" placeholder="Enter amount" required>
                        </div>
                        <div class="form-text">Minimum: UGX 1,000</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Provider</label>
                        <div class="d-flex gap-2">
                            <label class="flex-fill">
                                <input type="radio" name="provider" value="mtn" class="btn-check" checked>
                                <span class="btn btn-outline-warning w-100">MTN Mobile Money</span>
                            </label>
                            <label class="flex-fill">
                                <input type="radio" name="provider" value="airtel" class="btn-check">
                                <span class="btn btn-outline-danger w-100">Airtel Money</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+256</span>
                            <input type="tel" class="form-control" name="phone_number" placeholder="7XXXXXXXX" maxlength="9" required>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        <strong>Test Mode:</strong> Withdrawals are simulated. No actual transfers.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fa fa-minus me-1"></i> Withdraw
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.payment-option input:checked + .btn-outline-warning,
input[type="radio"].btn-check:checked + .btn-outline-warning {
    background-color: #ffc107;
    color: #000;
    border-color: #ffc107;
}
.payment-option input:checked + .btn-outline-primary {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
input[type="radio"].btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    color: #fff;
    border-color: #dc3545;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick amount buttons
    document.querySelectorAll('.quick-amount').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelector('#depositForm input[name="amount"]').value = this.dataset.amount;
        });
    });

    // Toggle mobile money fields
    document.querySelectorAll('#depositForm input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('mobilemoney-fields').style.display = 
                this.value === 'mobilemoney' ? 'block' : 'none';
        });
    });

    // Deposit form
    document.getElementById('depositForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Processing...';

        try {
            const response = await fetch('/ajax/wallet/deposit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();
            
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deposit Successful!',
                        text: data.message,
                        confirmButtonColor: '#FF4900'
                    }).then(() => location.reload());
                } else {
                    alert('Deposit Successful! ' + data.message);
                    location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Deposit Failed',
                        text: data.error || 'Something went wrong',
                        confirmButtonColor: '#FF4900'
                    });
                } else {
                    alert('Deposit Failed: ' + (data.error || 'Something went wrong'));
                }
            }
        } catch (err) {
            console.error('Deposit error:', err);
            alert('Network error. Please try again.');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plus me-1"></i> Deposit';
    });

    // Withdraw form
    document.getElementById('withdrawForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('payment_method', 'mobilemoney');
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Processing...';

        try {
            const response = await fetch('/ajax/wallet/withdraw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();
            
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Withdrawal Successful!',
                        text: data.message,
                        confirmButtonColor: '#FF4900'
                    }).then(() => location.reload());
                } else {
                    alert('Withdrawal Successful! ' + data.message);
                    location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Withdrawal Failed',
                        text: data.error || 'Something went wrong',
                        confirmButtonColor: '#FF4900'
                    });
                } else {
                    alert('Withdrawal Failed: ' + (data.error || 'Something went wrong'));
                }
            }
        } catch (err) {
            console.error('Withdraw error:', err);
            alert('Network error. Please try again.');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-minus me-1"></i> Withdraw';
    });
});
</script>
@endpush
