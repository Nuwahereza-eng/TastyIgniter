{!! Assets::getJsVars() !!}

{{-- Load theme JavaScript directly (bypassing asset combiner to avoid hang issues) --}}
<script src="/vendor/igniter-orange/js/app.js"></script>

@stack('scripts')
{!! $theme->ga_tracking_code ?? '' !!}
@if (!empty($theme->custom_js))
    <script type="text/javascript">{!! $theme->custom_js !!}</script>
@endif
