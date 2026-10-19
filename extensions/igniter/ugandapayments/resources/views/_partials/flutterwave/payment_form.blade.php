<div class="flutterwave-payment-form mt-3 p-3 bg-light rounded">
    <div class="mb-3">
        <label class="form-label fw-bold">Select Payment Method</label>
        <div class="payment-method-options">
            <div class="row g-2">
                <div class="col-6">
                    <label class="payment-method-option">
                        <input type="radio" name="flw_method" value="card" class="btn-check" checked>
                        <div class="btn btn-outline-primary w-100 py-3">
                            <i class="fa fa-credit-card fa-2x mb-2 d-block"></i>
                            <strong>Card</strong>
                            <small class="d-block text-muted">Visa, Mastercard</small>
                        </div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="payment-method-option">
                        <input type="radio" name="flw_method" value="mobilemoney" class="btn-check">
                        <div class="btn btn-outline-success w-100 py-3">
                            <i class="fa fa-mobile-alt fa-2x mb-2 d-block"></i>
                            <strong>Mobile Money</strong>
                            <small class="d-block text-muted">MTN, Airtel</small>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info small mb-0">
        <i class="fa fa-info-circle me-2"></i>
        <strong>Test Mode:</strong> Payment will be simulated. No actual charge.
    </div>

    <style>
    .payment-method-option input:checked + .btn-outline-primary {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .payment-method-option input:checked + .btn-outline-success {
        background-color: #198754;
        color: #fff;
        border-color: #198754;
    }
    </style>
</div>
