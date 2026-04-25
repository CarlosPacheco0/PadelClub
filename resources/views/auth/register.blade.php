<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - SportBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/layout.css', 'resources/css/auth/auth.css', 'resources/js/app.js'])

</head>

<body>

    <main class="content">
        <div id="toast-container" class="toast-container"></div>
        <x-alert-view />
    </main>

    <div class="hero-section">
        <div class="hero-bg">
            <img src="https://img.freepik.com/foto-gratis/herramientas-deportivas_53876-138077.jpg?semt=ais_hybrid&w=740&q=80"
                alt="Deportes">
            <div class="hero-overlay-color"></div>
            <div class="hero-overlay-gradient"></div>
        </div>

        <div class="hero-content">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                <i class="fas fa-futbol text-accent" style="font-size: 2.25rem;"></i>
                <span class="logo-text">Sport<span class="text-accent">Book</span></span>
            </div>
            <h1 class="hero-title">Tu próximo partido empieza aquí.</h1>
            <ul style="list-style: none; margin-top: 1.5rem; color: var(--text-indigo); line-height: 2;">
                <li><i class="fas fa-check-circle text-accent" style="margin-right: 0.5rem;"></i> Encuentra canchas
                    disponibles en tiempo real.</li>
                <li><i class="fas fa-check-circle text-accent" style="margin-right: 0.5rem;"></i> Reserva sin llamar por
                    teléfono.</li>
                <li><i class="fas fa-check-circle text-accent" style="margin-right: 0.5rem;"></i> Si eres dueño,
                    gestiona tu negocio sin estrés.</li>
            </ul>
        </div>
    </div>

    <div class="form-section">

        <a href="{{ route('login') }}" class="back-link">
            ¿Ya tienes cuenta? <span style="color: var(--brand-light); font-weight: bold;">Inicia Sesión</span>
        </a>

        <div class="glass-panel auth-scrollable" style="margin-top: 2rem;">

            <div class="text-center mb-6">
                <h2 class="text-white" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Únete a la
                    plataforma</h2>
                <p>Selecciona tu perfil para comenzar</p>
            </div>

            <div class="toggle-container">
                <button id="btn-jugador" class="btn-toggle active" onclick="switchTab('jugador')">
                    <i class="fas fa-user" style="margin-right: 0.5rem;"></i> Soy Jugador
                </button>
                <button id="btn-club" class="btn-toggle" onclick="switchTab('club')">
                    <i class="fas fa-building" style="margin-right: 0.5rem;"></i> Soy un Club
                </button>
            </div>

            <form id="form-jugador" action="{{ route('player_register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label text-white">Nombre Completo</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="name" class="custom-input" placeholder="Ej. Juan Pérez" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label"
                        style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Teléfono</label>
                    <input type="text" name="phone" class="custom-input" style="padding-left: 1rem; width: 100%;"
                        placeholder="Ej. 320 123 4567" maxlength="20" required>
                </div>

                <div class="form-group">
                    <label class="form-label text-white">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="custom-input" placeholder="juan@correo.com"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-white">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="custom-input" placeholder="Mínimo 8 caracteres"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-white">Confirmar Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="custom-input"
                            placeholder="Repite tu contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-4">
                    Crear cuenta de jugador <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <form id="form-club" action="{{ route('club_register') }}" method="POST" class="form-hidden">
                @csrf

                <div style="border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <h3 style="color: var(--brand-light); font-size: 0.875rem; font-weight: 700; margin-bottom: 1rem;">
                        <i class="fas fa-store" style="margin-right: 0.5rem;"></i> Datos del Escenario
                    </h3>

                    <div class="form-group">
                        <input type="text" name="club_name" class="custom-input"
                            placeholder="Nombre del Club (Ej. Pádel Norte)" maxlength="100" required>
                    </div>

                    <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <select name="city" class="custom-input" style="width: 35%; padding-left: 1rem;">
                            <option value="Ocaña">Ocaña</option>
                            <option value="Abrego">Ábrego</option>
                        </select>
                        <input type="text" name="address" class="custom-input"
                            style="width: 65%; padding-left: 1rem;" placeholder="Dirección exacta" maxlength="255"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teléfono del Club</label>
                        <input type="text" name="contact_phone" class="custom-input"
                            placeholder="Ej. 320 123 4567" required>
                    </div>

                </div>

                <div>
                    <h3 style="color: var(--brand-light); font-size: 0.875rem; font-weight: 700; margin-bottom: 1rem;">
                        <i class="fas fa-user-tie" style="margin-right: 0.5rem;"></i> Datos del Administrador
                    </h3>

                    <div class="form-group">
                        <input type="text" name="admin_name" class="custom-input" style="padding-left: 1rem;"
                            placeholder="Tu nombre completo" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="custom-input" style="padding-left: 1rem;"
                            placeholder="Correo para panel admin" maxlength="255" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label"
                            style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Contraseña</label>
                        <input type="password" name="password" class="custom-input"
                            style="padding-left: 1rem; width: 100%;" placeholder="Crea una contraseña segura"
                            maxlength="255" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label"
                            style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Confirmar
                            Contraseña</label>
                        <input type="password" name="password_confirmation" class="custom-input"
                            style="padding-left: 1rem; width: 100%;" placeholder="Confirma tu contraseña"
                            maxlength="255" required>
                    </div>
                </div>

                <label class="checkbox-group" style="margin: 1.5rem 0;">
                    <input type="checkbox" name="terms" required>
                    <span style="color: var(--text-muted); font-size: 0.75rem;">Acepto los términos y política de
                        datos.</span>
                </label>

                <button type="submit" class="btn-primary" style="background-color: var(--brand);">
                    Registrar mi Club <i class="fas fa-check"></i>
                </button>
            </form>

        </div>
    </div>

    <script>
        function switchTab(tab) {
            const btnJugador = document.getElementById('btn-jugador');
            const btnClub = document.getElementById('btn-club');
            const formJugador = document.getElementById('form-jugador');
            const formClub = document.getElementById('form-club');

            if (tab === 'jugador') {
                btnJugador.classList.add('active');
                btnClub.classList.remove('active');
                formJugador.classList.remove('form-hidden');
                formClub.classList.add('form-hidden');
            } else {
                btnClub.classList.add('active');
                btnJugador.classList.remove('active');
                formClub.classList.remove('form-hidden');
                formJugador.classList.add('form-hidden');
            }
        }
    </script>
</body>

</html>
