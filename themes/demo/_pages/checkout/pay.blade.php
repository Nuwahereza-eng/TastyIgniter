---
title: 'Complete your payment'
layout: default
permalink: /checkout/pay/:hash
---
@php
    $payHash = request()->route('hash');
    $payOrder = $payHash ? \Igniter\Cart\Models\Order::where('hash', $payHash)->first() : null;
@endphp
<div class="container">
    <div class="row py-5 mt-5">
        <div class="col-md-8 col-lg-6 m-auto">
            @if (!$payOrder)
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5>Order not found</h5>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-3">Back to Home</a>
                    </div>
                </div>
            @else
                {{-- Marz Pay mobile money: waiting for the customer to approve the prompt.
                     Order totals are shown here because the standard order preview
                     only displays orders that are already paid. --}}
                <div id="marzPanel" class="card shadow-sm"
                     data-hash="{{ $payHash }}"
                     data-success-url="{{ url('/checkout/success/'.$payHash) }}">
                    <div class="card-header bg-white d-flex justify-content-between">
                        <strong>Order UGA-{{ str_pad($payOrder->order_id, 5, '0', STR_PAD_LEFT) }}</strong>
                        <span class="fw-bold" style="color:#ff4900">UGX {{ number_format((float)$payOrder->order_total) }}</span>
                    </div>
                    <div class="card-body text-center py-4">
                        <div data-state="loading">
                            <div class="spinner-border text-secondary" role="status"></div>
                        </div>
                        <div data-state="waiting" class="d-none">
                            <div class="spinner-border text-warning mb-3" role="status"></div>
                            <h5 class="mb-1">Approve the payment on your phone</h5>
                            <p class="text-muted mb-2">
                                We sent a Mobile Money request to <strong data-phone></strong>.
                                Enter your PIN to confirm.
                            </p>
                            <div class="display-6 fw-bold" data-countdown>3:00</div>
                            <div class="progress mx-auto my-3" style="height: 6px; max-width: 320px;">
                                <div class="progress-bar bg-warning" data-progress style="width: 100%"></div>
                            </div>
                            <small class="text-muted d-block mb-3">No prompt? Dial *165# (MTN) or *185# (Airtel) and check pending approvals.</small>
                            <button type="button" class="btn btn-link btn-sm" data-action="cash">Pay cash on delivery instead</button>
                        </div>
                        <div data-state="paid" class="d-none">
                            <i class="fa fa-circle-check text-success fa-3x mb-3"></i>
                            <h5 class="mb-1" data-paid-title>Payment received</h5>
                            <p class="text-muted mb-0">Taking you to your order…</p>
                        </div>
                        <div data-state="failed" class="d-none">
                            <i class="fa fa-circle-xmark text-danger fa-3x mb-3"></i>
                            <h5 class="mb-1">Payment not completed</h5>
                            <p class="text-muted mb-3" data-failed-text>The payment request expired before it was approved.</p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button type="button" class="btn btn-warning" data-action="retry"><i class="fa fa-rotate-right me-1"></i>Resend payment request</button>
                                <button type="button" class="btn btn-outline-secondary" data-action="cash"><i class="fa fa-money-bill me-1"></i>Pay cash on delivery</button>
                            </div>
                        </div>
                        <div data-state="canceled" class="d-none">
                            <i class="fa fa-ban text-secondary fa-3x mb-3"></i>
                            <h5 class="mb-1">Order cancelled</h5>
                            <p class="text-muted mb-3">The payment was not completed, so this order was cancelled. You have not been charged.</p>
                            <a href="{{ url('/locations') }}" class="btn btn-primary">Order again</a>
                        </div>
                        <div class="small text-danger mt-3" data-error></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Polls the Marz payment until it is approved, declined or the prompt times
// out, then offers to resend the request or switch to cash on delivery.
(function () {
    var panel = document.getElementById('marzPanel');
    if (!panel) return;

    var base = '/ajax/payments/order/' + encodeURIComponent(panel.dataset.hash);
    var successUrl = panel.dataset.successUrl;
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var pollTimer = null, tickTimer = null, secondsLeft = 0, timeout = 180;

    function show(state) {
        panel.querySelectorAll('[data-state]').forEach(function (el) {
            el.classList.toggle('d-none', el.dataset.state !== state);
        });
    }

    function stopTimers() {
        clearTimeout(pollTimer);
        clearInterval(tickTimer);
    }

    function renderCountdown() {
        var m = Math.floor(secondsLeft / 60), s = secondsLeft % 60;
        panel.querySelector('[data-countdown]').textContent = m + ':' + String(s).padStart(2, '0');
        panel.querySelector('[data-progress]').style.width = Math.max(0, secondsLeft / timeout * 100) + '%';
    }

    function goToOrder(title) {
        stopTimers();
        panel.querySelector('[data-paid-title]').textContent = title;
        show('paid');
        setTimeout(function () { window.location.href = successUrl; }, 1500);
    }

    function apply(data) {
        if (!data || !data.success) {
            pollTimer = setTimeout(poll, 8000);
            return;
        }
        panel.querySelectorAll('[data-action="retry"]').forEach(function (b) { b.classList.toggle('d-none', !data.can_retry); });
        panel.querySelectorAll('[data-action="cash"]').forEach(function (b) { b.classList.toggle('d-none', !data.can_pay_cash); });

        switch (data.state) {
            case 'waiting':
                timeout = data.timeout || timeout;
                secondsLeft = data.seconds_left;
                panel.querySelector('[data-phone]').textContent = data.phone || 'your phone';
                renderCountdown();
                show('waiting');
                clearInterval(tickTimer);
                tickTimer = setInterval(function () {
                    secondsLeft = Math.max(0, secondsLeft - 1);
                    renderCountdown();
                }, 1000);
                clearTimeout(pollTimer);
                pollTimer = setTimeout(poll, 5000);
                break;
            case 'paid':
                goToOrder('Payment received');
                break;
            case 'cash':
                goToOrder('Paying cash on delivery');
                break;
            case 'expired':
            case 'declined':
                stopTimers();
                var text = data.state === 'declined'
                    ? 'The payment was declined or cancelled on your phone.'
                    : 'The payment request expired before it was approved.';
                if (!data.can_retry) text += ' You can still pay cash on delivery.';
                panel.querySelector('[data-failed-text]').textContent = text;
                show('failed');
                break;
            case 'canceled':
                stopTimers();
                show('canceled');
                break;
            default:
                // Not waiting on Marz (e.g. another payment method): show the order.
                stopTimers();
                window.location.href = successUrl;
        }
    }

    function poll() {
        fetch(base + '/status', {headers: {'Accept': 'application/json'}, credentials: 'same-origin'})
            .then(function (r) { return r.json(); })
            .then(apply)
            .catch(function () { pollTimer = setTimeout(poll, 8000); });
    }

    function post(action) {
        var error = panel.querySelector('[data-error]');
        error.textContent = '';
        return fetch(base + '/' + action, {
            method: 'POST',
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrf},
            credentials: 'same-origin',
        }).then(function (r) { return r.json(); }).then(function (res) {
            if (!res.success) error.textContent = res.message || 'Something went wrong. Please try again.';
            poll();
        });
    }

    panel.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action]');
        if (!btn) return;
        if (btn.dataset.action === 'cash' && !confirm('Pay with cash when your order is delivered?')) return;
        btn.disabled = true;
        stopTimers();
        post(btn.dataset.action).finally(function () { btn.disabled = false; });
    });

    poll();
})();
</script>
