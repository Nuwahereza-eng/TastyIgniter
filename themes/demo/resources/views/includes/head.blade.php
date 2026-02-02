{!! get_metas() !!}
<meta name="csrf-token" content="{{ csrf_token() }}">
@if ($favicon = $theme->favicon)
    <link href="{{ media_url($favicon) }}" rel="shortcut icon" type="image/ico">
@elseif ($site_logo !== 'no_photo.png')
    @php
        try {
            $faviconUrl = media_thumb($site_logo, ['width' => 64, 'height' => 64]);
        } catch (\Exception $e) {
            // Fallback if image processing fails
            $faviconUrl = media_url($site_logo);
        }
    @endphp
    <link href="{{ $faviconUrl }}" rel="shortcut icon" type="image/ico">
@else
    {!! get_favicon() !!}
@endif
<title>{{ lang(get_title()).lang('igniter.orange::default.title_separator').setting('site_name') }}</title>
@if ($page->description)
    <meta name="description" content="{{ $page->description }}">
@endif
@if ($page->keywords)
    <meta name="keywords" content="{{ $page->keywords }}">
@endif
@unless($theme->font['download'] ?? FALSE)
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="{{$theme->font['url'] ?? 'https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap'}}" rel="stylesheet">
@else
    @googlefonts
@endunless
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<!-- Igniter Orange Theme CSS (direct include as fallback) -->
<link rel="stylesheet" href="{{ asset('vendor/igniter-orange/css/app.css') }}">
@themeStyles
@if (!empty($theme->custom_css))
    <style>{{$theme->custom_css}}</style>
@endif
