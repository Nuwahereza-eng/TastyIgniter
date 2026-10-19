<div class="mobile-money-payment-form mt-3 p-3 bg-light rounded">
    <div class="mb-3">
        <label class="form-label fw-bold">Select Provider</label>
        <div class="provider-options d-flex gap-3">
            <label class="provider-option flex-fill">
                <input type="radio" name="provider" value="mtn" class="btn-check" checked>
                <div class="btn btn-outline-warning w-100 py-3">
                    <i class="fa fa-mobile-alt me-2"></i>
                    <strong>MTN Mobile Money</strong>
                </div>
            </label>
            <label class="provider-option flex-fill">
                <input type="radio" name="provider" value="airtel" class="btn-check">
                <div class="btn btn-outline-danger w-100 py-3">
                    <i class="fa fa-mobile-alt me-2"></i>
                    <strong>Airtel Money</strong>
                </div>
            </label>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="mobile_number" class="form-label fw-bold">Mobile Money Number</label>
        <div class="input-group">
            <span class="input-group-text bg-dark text-white">+256</span>
            <input type="tel" 
                   class="form-control form-control-lg" 
                   id="mobile_number" 
                   name="mobile_number" 
                   placeholder="7XXXXXXXX" 
                   maxlength="9">
        </div>
        <div class="form-text">Enter your 9-digit mobile money number</div>
    </div>
    
    <div class="alert alert-info small mb-0">
        <i class="fa fa-info-circle me-2"></i>
        <strong>Test Mode:</strong> Payment will be simulated. No actual charge.
    </div>

    <style>
    .provider-option input:checked + .btn-outline-warning {
        background-color: #ffc107;
        color: #000;
        border-color: #ffc107;
    }
    .provider-option input:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    </style>
</div>
