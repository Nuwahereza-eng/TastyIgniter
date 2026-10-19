<div class="flutterwave-payment-form">
    <div class="mb-3">
        <label class="form-label fw-bold">Select Payment Method</label>
        <div class="payment-method-options">
            <div class="row g-2">
                <div class="col-6">
                    <label class="payment-method-option">
                        <input type="radio" name="flw_method" value="card" class="btn-check" required>
                        <div class="btn btn-outline-primary w-100 py-3">
                            <i class="fa fa-credit-card fa-2x mb-2 d-block"></i>
                            <strong>Card</strong>
                            <small class="d-block text-muted">Visa, Mastercard</small>
                        </div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="payment-method-option">
                        <input type="radio" name="flw_method" value="mobilemoneygug" class="btn-check">
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
    
    <!-- Card Details (shown when card is selected) -->
    <div id="flw-card-details" class="d-none">
        <div class="alert alert-secondary small">
            <i class="fa fa-lock me-2"></i>
            You'll be redirected to Flutterwave's secure payment page to enter your card details.
        </div>
    </div>
    
    <!-- Mobile Money Details (shown when mobile money is selected) -->
    <div id="flw-mobile-details" class="d-none">
        <div class="mb-3">
            <label for="flw_phone" class="form-label fw-bold">Mobile Money Number</label>
            <div class="input-group">
                <span class="input-group-text bg-dark text-white">+256</span>
                <input type="tel" 
                       class="form-control form-control-lg" 
                       id="flw_phone" 
                       name="flw_phone" 
                       placeholder="7XXXXXXXX"
                       pattern="[0-9]{9}"
                       maxlength="9">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Network</label>
            <div class="d-flex gap-2">
                <label class="flex-fill">
                    <input type="radio" name="flw_network" value="MTN" class="btn-check">
                    <span class="btn btn-outline-warning w-100">MTN</span>
                </label>
                <label class="flex-fill">
                    <input type="radio" name="flw_network" value="AIRTEL" class="btn-check">
                    <span class="btn btn-outline-danger w-100">Airtel</span>
                </label>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info small mt-3">
        <i class="fa fa-shield-alt me-2"></i>
        <strong>Secure Payment:</strong> All payments are processed securely by Flutterwave.
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardDetails = document.getElementById('flw-card-details');
        const mobileDetails = document.getElementById('flw-mobile-details');
        const methodRadios = document.querySelectorAll('input[name="flw_method"]');

        methodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'card') {
                    cardDetails.classList.remove('d-none');
                    mobileDetails.classList.add('d-none');
                } else {
                    cardDetails.classList.add('d-none');
                    mobileDetails.classList.remove('d-none');
                }
            });
        });
    });
    </script>

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
    .payment-method-option input:checked + .btn-outline-warning {
        background-color: #ffc107;
        color: #000;
    }
    .payment-method-option input:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: #fff;
    }
    </style>
</div>
