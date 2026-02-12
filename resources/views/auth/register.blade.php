<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Pádel Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/layout.css', 'resources/css/auth.css'])
</head>
<body class="register-mode"> <!-- Clase extra para ajustar ancho -->

    <div class="split-screen">
        
        <!-- IZQUIERDA: Visual / Marca -->
        <div class="visual-side">
            <div class="visual-content">
                <h2>Únete al Club</h2>
                <p>Crea tu cuenta en segundos y empieza a reservar tus canchas favoritas hoy mismo.</p>
            </div>
        </div>

        <!-- DERECHA: Formulario -->
        <div class="form-side">
            
            <a href="{{ route('home') }}" class="btn-back">
                ← Volver
            </a>

            <div class="form-container" style="max-width: 550px;"> <!-- Un poco más ancho para registro -->
                <div class="header-text">
                    <h1>Crear cuenta</h1>
                    <p class="subtitle">Completa el formulario para registrarte.</p>
                </div>

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nombre completo</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Juan Pérez" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                placeholder="099 999 9999">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="correo@email.com">
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" required placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Registrarme
                    </button>

                    <p class="footer-text">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}">Inicia sesión aquí</a>
                    </p>
                </form>
            </div>
        </div>

    </div>

</body>
</html>