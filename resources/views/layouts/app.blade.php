<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Scripts now uses the build js asset compilation file js/app.js -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles: Compiled Sass variables/base and navbar -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navigation/topNavbar.css') }}" rel="stylesheet">

    <!-- Additional fonts for branding -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Barlow:wght@300;400;500;600&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">

    @stack('styles')
    @yield('head')
</head>
<body class="@yield('body-class')">
    <div id="app">
        @if (!trim($__env->yieldContent('hide_steam_nav')))
            @include('topNavbar')
        @endif
        <main class="@yield('main-class', 'py-4')">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
