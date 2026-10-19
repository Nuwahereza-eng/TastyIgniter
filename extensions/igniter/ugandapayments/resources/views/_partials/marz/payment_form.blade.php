<div class="marz-payment-wrapper">
<div class="marz-payment-form mt-3 p-3 bg-light rounded">
    <div class="d-flex align-items-center mb-3">
        <img src="/images/payments/marz.png" alt="Marz Wallet"
             style="width: 48px; height: 48px; object-fit: contain;"
             class="me-3"
             onerror="this.style.display='none'">
        <div>
            <strong>Marz Wallet</strong>
            <div class="text-muted small">Pay securely via Mobile Money or Card.</div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold mb-2">Payment Method</label>
        <div class="btn-group w-100" role="group" aria-label="Marz payment method">
            <input type="radio" class="btn-check" name="marz_method" id="marz-method-mtn" value="mtn" checked>
            <label class="btn btn-outline-warning d-flex align-items-center justify-content-center gap-2" for="marz-method-mtn">
                <img src="/images/payments/mtn.png" alt="MTN" style="height:22px;width:auto;object-fit:contain;" onerror="this.style.display='none'">
                <span>MTN Mobile Money</span>
            </label>

            <input type="radio" class="btn-check" name="marz_method" id="marz-method-airtel" value="airtel">
            <label class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2" for="marz-method-airtel">
                <img src="/images/payments/airtel.png" alt="Airtel" style="height:22px;width:auto;object-fit:contain;" onerror="this.style.display='none'">
                <span>Airtel Money</span>
            </label>

            <input type="radio" class="btn-check" name="marz_method" id="marz-method-card" value="card">
            <label class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2" for="marz-method-card">
                <i class="fa fa-credit-card"></i>
                <span>Card</span>
            </label>
        </div>
    </div>

    <div class="mb-2 marz-phone-wrap">
        <label for="marz_phone" class="form-label small fw-bold">Mobile Money Number</label>
        <input type="tel" class="form-control" id="marz_phone" name="marz_phone"
               placeholder="e.g. 0772123456 or +256772123456"
               pattern="[0-9+\s]{9,15}">
        <div class="form-text">You'll receive a prompt on this phone to approve the payment.</div>
    </div>

    <div class="alert alert-info small mb-0 mt-2 marz-card-info" style="display:none">
        <i class="fa fa-credit-card me-2"></i>
        You'll be redirected to Marz to enter your card details securely.
    </div>

    <div class="alert alert-warning small mb-0 mt-2 marz-momo-info">
        <i class="fa fa-mobile-alt me-2"></i>
        After clicking <strong>Confirm</strong>, check your phone for the Mobile Money prompt and enter your PIN to approve.
    </div>
</div>

<script>
(function () {
    function toggleMarzFields() {
        var method = document.querySelector('input[name="marz_method"]:checked');
        if (!method) return;
        var isCard = method.value === 'card';
        var phoneWrap = document.querySelector('.marz-phone-wrap');
        var cardInfo = document.querySelector('.marz-card-info');
        var momoInfo = document.querySelector('.marz-momo-info');
        if (phoneWrap) phoneWrap.style.display = isCard ? 'none' : '';
        if (cardInfo) cardInfo.style.display = isCard ? '' : 'none';
        if (momoInfo) momoInfo.style.display = isCard ? 'none' : '';
        var phone = document.getElementById('marz_phone');
        if (phone) phone.required = !isCard;
    }
    document.querySelectorAll('input[name="marz_method"]').forEach(function (el) {
        el.addEventListener('change', toggleMarzFields);
    });
    toggleMarzFields();

    // --- Processing overlay for Marz checkout ---
    function isMarzSelected() {
        // PayRegister stores the chosen code in input[name="payment"] or radio inputs
        var byHidden = document.querySelector('input[name="payment"]');
        if (byHidden && byHidden.value === 'marz') return true;
        var byRadio = document.querySelector('input[name="payment"]:checked, input[name="fields[payment]"]:checked');
        if (byRadio && byRadio.value === 'marz') return true;
        // Fallback: presence of our visible form means Marz panel is open
        return !!document.querySelector('.marz-payment-form');
    }

    function ensureOverlay() {
        var existing = document.getElementById('marz-processing-overlay');
        if (existing) return existing;
        var el = document.createElement('div');
        el.id = 'marz-processing-overlay';
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        el.innerHTML = ''
            + '<div class="marz-processing-card">'
            + '  <div class="spinner-border text-warning mb-3" style="width:3rem;height:3rem"><\/div>'
            + '  <h5 class="mb-1">Processing your payment…<\/h5>'
            + '  <p class="mb-1" id="marz-processing-msg">Contacting Marz Wallet, please wait.<\/p>'
            + '  <small class="text-muted">Do not refresh or close this page.<\/small>'
            + '<\/div>';
        var style = document.createElement('style');
        style.textContent = ''
            + '#marz-processing-overlay{position:fixed;inset:0;background:rgba(15,15,15,.6);'
            + '  display:flex;align-items:center;justify-content:center;z-index:99999;backdrop-filter:blur(2px);}'
            + '#marz-processing-overlay .marz-processing-card{background:#fff;border-radius:14px;'
            + '  padding:28px 36px;text-align:center;max-width:340px;box-shadow:0 12px 40px rgba(0,0,0,.25);}'
            + '#marz-processing-overlay .spinner-border{border-width:.3em}';
        document.head.appendChild(style);
        document.body.appendChild(el);
        return el;
    }

    function showProcessing(message) {
        var el = ensureOverlay();
        var msg = document.getElementById('marz-processing-msg');
        if (msg && message) msg.textContent = message;
        el.style.display = 'flex';
    }

    function hideProcessing() {
        var el = document.getElementById('marz-processing-overlay');
        if (el) el.style.display = 'none';
    }

    function attachSubmitHandler() {
        var btn = document.querySelector('[data-checkout-control="submit"], button.checkout-btn[type="submit"]');
        if (!btn || btn.__marzBound) return;
        btn.__marzBound = true;

        btn.addEventListener('click', function () {
            if (!isMarzSelected()) return;
            var method = (document.querySelector('input[name="marz_method"]:checked') || {}).value || 'card';
            var msg = method === 'card'
                ? 'Redirecting you to Marz Wallet to complete your card payment…'
                : 'Sending Mobile Money prompt to your phone. Approve it with your PIN to confirm.';
            showProcessing(msg);

            // Disable button to prevent double submits
            btn.classList.add('disabled');
            btn.setAttribute('disabled', 'disabled');
            var original = btn.innerHTML;
            btn.dataset.marzOriginal = original;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"><\/span>Processing…';
        });
    }

    // Re-attach when Livewire re-renders the checkout panel
    document.addEventListener('DOMContentLoaded', attachSubmitHandler);
    attachSubmitHandler();
    if (window.Livewire && typeof window.Livewire.hook === 'function') {
        window.Livewire.hook('message.processed', function () {
            attachSubmitHandler();
            // If a validation error came back from server, hide the overlay so user can fix it
            var hasInvalid = document.querySelector('.is-invalid, .invalid-feedback:not(:empty)');
            if (hasInvalid) {
                hideProcessing();
                var btn = document.querySelector('[data-checkout-control="submit"], button.checkout-btn[type="submit"]');
                if (btn && btn.dataset.marzOriginal) {
                    btn.innerHTML = btn.dataset.marzOriginal;
                    btn.classList.remove('disabled');
                    btn.removeAttribute('disabled');
                }
            }
        });
    }

    // Safety: if the page becomes hidden (navigation away to Marz), keep overlay
    // If user comes back via browser back button, hide it
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hideProcessing();
    });
})();
</script>
</div>
