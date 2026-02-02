---
title: igniter.orange::default.checkout_title
layout: default
permalink: /checkout

'[igniter-orange::checkout]': []
'[igniter-orange::fulfillment-modal]':
    hideDeliveryAddress: true
---
@php
    // Require login to checkout - redirect guests to login page
    if (!Auth::check()) {
        flash()->warning('Please login or register to complete your order.');
        return redirect()->to(page_url('account.login'));
    }
@endphp
<div class="container">
    <div class="col-lg-10 mx-auto">
        <livewire:igniter-orange::checkout />
    </div>
</div>
<livewire:igniter-orange::fulfillment-modal />
