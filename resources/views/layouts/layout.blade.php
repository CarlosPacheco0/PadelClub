<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pádel Club | Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Codigo de verificación CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @vite(['resources/css/layout.css', 'resources/css/navbar.css', 'resources/css/alerts.css', 'resources/js/app.js'])

    @stack('styles')

    @stack('scripts')


</head>

<body>

    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">🎾 Pádel Club</div>

            {{-- Menu superior --}}
            <x-navbar></x-navbar>

        </div>
    </header>

    {{-- Contenido dinámico --}}
    <main class="content">

        {{-- Alertas desde js  --}}
        <div id="js-alert" class="alert alert-fixed d-none" role="alert"></div>


        {{-- Alertas correspondientes desde el backend --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showAlert('success', @json(session('success')));
                });
            </script>
        @endif


        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showAlert('error', @json(session('error')));
                });
            </script>
        @endif


        @yield('content')
    </main>

</body>

</html>
