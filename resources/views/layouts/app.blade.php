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

    <!-- Styles now uses the build css asset compilation file css/app.css-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
    @yield('head')
</head>
<body class="@yield('body-class')">
    <div id="app">
        @include('topNavbar')
        <main class="@yield('main-class', 'py-4')">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
