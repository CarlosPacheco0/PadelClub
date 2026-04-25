<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SportBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/layout.css', 'resources/css/auth/auth.css', 'resources/js/app.js'])

</head>

<body>

    <div class="hero-section">
        <div class="hero-bg">
            <img src="{{ Vite::asset('resources/images/sport.jpg') }}"
                alt="Deportes">
            <div class="hero-overlay-color"></div>
            <div class="hero-overlay-gradient"></div>
        </div>

        <div class="hero-content">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                <i class="fas fa-futbol" style="color: var(--accent-light); font-size: 2.25rem;"></i>
                <span style="font-size: 1.875rem; font-weight: 800; color: var(--text-main);">Sport<span
                        style="color: var(--accent-light);">Book</span></span>
            </div>
            <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2;">La red deportiva
                más grande de Ocaña.</h1>
            <p style="color: var(--text-indigo); font-size: 1.125rem; line-height: 1.6;">Únete a miles de deportistas.
                Reserva tu escenario favorito en segundos o gestiona tu club desde un solo lugar.</p>

            <div class="social-proof">
                <div class="avatar-group">
                    <img src="https://i.pravatar.cc/100?img=11" alt="Usuario">
                    <img src="https://i.pravatar.cc/100?img=33" alt="Usuario">
                    <img src="https://i.pravatar.cc/100?img=12" alt="Usuario">
                    <div class="avatar-counter">+2k</div>
                </div>
                <span class="social-text">Deportistas activos</span>
            </div>

        </div>
    </div>

    <div class="form-section">
        <a href="{{ route('martketplace') }}" class="back-link"><i class="fas fa-arrow-left"></i> Volver al inicio</a>

        <div class="glass-panel">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: var(--text-main); font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                    Bienvenido de nuevo</h2>
                <p>Ingresa tus credenciales para acceder a tu cuenta.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="custom-input"
                            placeholder="ejemplo@correo.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="custom-input"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <label
                        style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
                        <input type="checkbox" name="remember-me"> Recordarme
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-primary">
                    Ingresar a mi cuenta <i class="fas fa-sign-in-alt"></i>
                </button>
            </form>

            <div class="divider"><span class="divider-text">¿No tienes cuenta?</span></div>

            <div class="action-grid">
                <button class="btn-outline"><i class="fas fa-user" style="color: var(--brand-light)"></i> Soy
                    Jugador</button>
                <button class="btn-outline"><i class="fas fa-building" style="color: var(--accent-light)"></i> Soy
                    Club</button>
            </div>
        </div>
    </div>
</body>

</html>
