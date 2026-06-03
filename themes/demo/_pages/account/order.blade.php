---
title: igniter.orange::default.account_order_title
layout: default
permalink: /account/order/:hash
security: all

'[igniter-orange::order-preview]':
    hideReorderBtn: false
    showCancelButton: true
'[igniter-orange::leave-review]':
    type: order
---
<div class="container">
    <div class="row py-4">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu"/>
        </div>

        <div class="col-sm-10">
            <livewire:igniter-orange::order-preview/>
        </div>
    </div>
</div>

<script>
// Normalise the vendor order heading "Order # 29" to "Order # UGA-00029".
(function () {
    function pad(n) { return 'UGA-' + String(n).padStart(5, '0'); }
    function rewrite(root) {
        (root || document).querySelectorAll('h1,h2,h3,h4,h5,h6').forEach(function (el) {
            var m = el.textContent && el.textContent.match(/(.*?)([#:]\s*)(\d+)\s*$/i);
            if (m && /order/i.test(m[1])) {
                var num = parseInt(m[3], 10);
                if (num > 0) el.textContent = m[1] + m[2] + pad(num);
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () { rewrite(); });
    if (window.Livewire && window.Livewire.hook) {
        try {
            window.Livewire.hook('message.processed', function () { rewrite(); });
            window.Livewire.hook('morph.updated', function () { rewrite(); });
        } catch (e) {}
    }
})();
</script>
