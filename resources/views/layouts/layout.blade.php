<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Maestro - SportBook</title>
    {{-- <title>Mi Club - SportBook</title> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Codigo de verificación CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- @vite(['resources/css/layout.css', 'resources/css/navbar.css', 'resources/css/alerts.css', 'resources/js/app.js']) --}}
    @vite(['resources/css/layout.css', 'resources/css/navbar.css', 'resources/css/alerts.css', 'resources/js/app.js'])

    @stack('styles')

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <aside class="sidebar">

        <div class="brand-logo">
            <i class="fas fa-futbol" style="color: var(--accent-light); font-size: 2.25rem;"></i>
            <span style="font-size: 1.875rem; font-weight: 800; color: var(--text-main);">Sport<span
                    style="color: var(--accent-light);">Book</span>
        </div>

        <x-navbar></x-navbar>

    </aside>

    {{-- Contenido dinámico --}}
    <main class="main-content">

        {{-- Alertas desde js  --}}
        {{-- <div id="js-alert" class="alert alert-fixed d-none" role="alert"></div> --}}
        <div id="toast-container" class="toast-container"></div>

        <x-alert-view></x-alert-view>

        @yield('content')
    </main>

</body>

</html>
