<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?v=0.1" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}?v=0.1" rel="stylesheet">
    <link href="{{ asset('css/adminlte.min.css') }}?v=0.1" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}?v=0.1" rel="stylesheet">
    <link href="{{ asset('css/OverlayScrollbars.min.css') }}?v=0.1" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}?v=0.1" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed" data-panel-auto-height-mode="height">
    <div id="app" class="wrapper">
        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <div class="animation__shake">Cargando...</div>
        </div> --}}

        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')
        {{-- @if (Auth::user()->authorizeRoles(['superadmin']))
        @include('layouts.sidebar-super')
        @else
        @include('layouts.sidebar-admin')
        @endif --}}

        <!-- Main content -->
        <main>
            @yield('content')
        </main>

        <!-- Main Footer -->
        @include('layouts.footer')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?v=0.1"></script>
    <script src="{{ asset('js/select2.full.min.js') }}?v=0.1"></script>
    <script src="{{ asset('js/adminlte.min.js') }}?v=0.1"></script>
    <script src="{{ asset('js/OverlayScrollbars.min.js') }}?v=0.1"></script>
    @yield('scripts')
</body>
</html>
