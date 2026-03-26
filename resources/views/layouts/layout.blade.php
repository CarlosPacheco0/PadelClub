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
    @vite(['resources/css/layout.css', 'resources/css/alerts.css', 'resources/js/app.js'])

    @stack('styles')

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    {{-- <header class="navbar">
        <div class="container nav-content">
            <div class="logo">🎾 Pádel Club</div> --}}

    {{-- Menu superior --}}
    {{-- <x-navbar></x-navbar> --}}

    {{-- </div>
    </header> --}}

    <aside class="sidebar">
        <x-nav-link route="login" label="Login" />
        <x-nav-link route="register" label="Registro" />

        <div class="nav-menu">
            {{-- <a href="">⚙️ Configuración</a> --}}

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    🚪 Cerrar sesión
                </button>
            </form>
        </div>

        <div class="brand-logo">
            <i class="fas fa-futbol" style="color: var(--accent-light); font-size: 2.25rem;"></i>
            <span style="font-size: 1.875rem; font-weight: 800; color: var(--text-main);">Sport<span
                    style="color: var(--accent-light);">Book</span>
        </div>
        <ul class="nav-menu">
            <li><a href="#" class="nav-item active"><i class="fas fa-chart-line"></i> Visión Global</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-building"></i> Todos los Clubes</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-users"></i> Deportistas</a></li>
        </ul>
        <ul class="nav-menu">
            <li><a href="#" class="nav-item active"><i class="fas fa-calendar-check"></i> Reservas de Hoy</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-chart-bar"></i> Mis Ingresos</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-cog"></i> Configurar Sede</a></li>
        </ul>
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
