{!! Assets::getJsVars() !!}

{{-- Load theme JavaScript directly (bypassing asset combiner to avoid hang issues) --}}
<script src="/vendor/igniter-orange/js/app.js"></script>

{{-- Load individual component JS files that are registered via Assets::addJs() --}}
<script src="/vendor/igniter-orange/js/modal.js"></script>
<script src="/vendor/igniter-orange/js/menus.js"></script>
<script src="/vendor/igniter-orange/js/cart-item.js"></script>
<script src="/vendor/igniter-orange/js/cart-item-options.js"></script>
<script src="/vendor/igniter-orange/js/fulfillment.js"></script>
<script src="/vendor/igniter-orange/js/checkout.js"></script>

@stack('scripts')
{!! $theme->ga_tracking_code ?? '' !!}
@if (!empty($theme->custom_js))
    <script type="text/javascript">{!! $theme->custom_js !!}</script>
@endif
