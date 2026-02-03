---
description: Default layout
---
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ App::getLocale() }}" class="h-100">
<head>
    @include('igniter-orange::includes.head')
    
    <!-- Uganda Custom Styles -->
    <link rel="stylesheet" href="{{ asset('themes/demo/assets/css/uganda-custom.css') }}">
    
    <!-- AI Chatbot Styles -->
    <link rel="stylesheet" href="{{ asset('themes/demo/assets/css/chatbot.css') }}">
    
    @livewireStyles
</head>
<body class="d-flex flex-column h-100 {{ $this->page->bodyClass }}">

<header class="header">
    @include('igniter-orange::includes.header')
</header>

<main role="main">
    <div id="page-wrapper">
        @themePage
    </div>
</main>

@unless($this->page->hideFooter)
<footer class="footer mt-auto">
    @include('igniter-orange::includes.footer')
</footer>
@endunless
<livewire:igniter-orange::utils.modal/>
<livewire:igniter-orange::utils.flash-message/>
@include('igniter-orange::includes.eucookiebanner')
@livewireScripts
@include('igniter-orange::includes.scripts')

<!-- Uganda Custom JavaScript -->
<script src="{{ asset('themes/demo/assets/js/uganda-enhancements.js') }}"></script>

<!-- AI Chatbot -->
<script src="{{ asset('themes/demo/assets/js/chatbot.js') }}"></script>
</body>
</html>
