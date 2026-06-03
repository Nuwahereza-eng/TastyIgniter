---
title: igniter.orange::default.checkout_success_title
layout: default
permalink: /checkout/success/:hash?

'[igniter-orange::order-preview]': []
'[igniter-orange::leave-review]':
    type: order
---
<div class="container">
    <div class="row py-4">
        <div class="col-sm-9 m-auto">
            <livewire:igniter-orange::order-preview />

            @php
                $trackHash = request()->route('hash');
            @endphp
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                <a href="{{ url('/track-order') }}{{ $trackHash ? '?order='.urlencode($trackHash) : '' }}"
                   class="btn btn-lg px-4"
                   style="background-color: #ff4900; border-color: #ff4900; color: #fff;">
                    <i class="fa fa-motorcycle me-2"></i>Track Your Order
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="fa fa-home me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Normalise the order number shown on the vendor success card so it matches
// the format used everywhere else in the app: UGA-NNNNN.
// The vendor livewire prints "Order # 29" \u2014 we rewrite that to "Order # UGA-00029".
(function () {
    function pad(n) { return 'UGA-' + String(n).padStart(5, '0'); }
    function rewrite(root) {
        (root || document).querySelectorAll('h1,h2,h3,h4,h5,h6').forEach(function (el) {
            // Match the vendor pattern: "...Order # 29" or "...Order No. 29"
            var m = el.textContent && el.textContent.match(/(.*?)([#:]\s*)(\d+)\s*$/i);
            if (m && /order/i.test(m[1])) {
                var num = parseInt(m[3], 10);
                if (num > 0) {
                    el.textContent = m[1] + m[2] + pad(num);
                    el.setAttribute('data-formatted-order', pad(num));
                }
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () { rewrite(); });
    // Re-apply after Livewire patches the DOM (poll updates the preview every 120s).
    document.addEventListener('livewire:navigated', function () { rewrite(); });
    if (window.Livewire && window.Livewire.hook) {
        try {
            window.Livewire.hook('message.processed', function (_msg, _comp) { rewrite(); });
            window.Livewire.hook('morph.updated', function () { rewrite(); });
        } catch (e) { /* livewire v2 / v3 differences \u2014 best-effort */ }
    }
})();
</script>
