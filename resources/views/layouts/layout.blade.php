<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pádel Club | Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Codigo de verificación CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @vite(['resources/css/layout.css', 'resources/css/navbar.css'])
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
        @yield('content')
    </main>

</body>

<script>
    function setupDropdown(triggerId, dropdownId) {
        const trigger = document.getElementById(triggerId);
        const dropdown = document.getElementById(dropdownId);

        if (trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();

                // 1️⃣ Cerrar todos los dropdowns abiertos
                document.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.style.display = 'none';
                    }
                });

                // 2️⃣ Alternar el actual
                dropdown.style.display =
                    dropdown.style.display === 'flex' ? 'none' : 'flex';
            });
        }

        // 3️⃣ Cerrar al hacer click fuera
        document.addEventListener('click', function() {
            dropdown.style.display = 'none';
        });
    }

    // Inicializar
    setupDropdown('userTrigger', 'userDropdown');
    setupDropdown('scheduleTrigger', 'scheduleDropdown');
</script>


</html>
