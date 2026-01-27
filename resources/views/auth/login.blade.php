<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/auth.css'])
</head>

<body>

    <div class="auth-container">

        <div class="auth-card card-login">

            <!-- BOTÓN VOLVER (NO ES FORM) -->
            <a href="{{ url('/') }}" class="btn-back">
                ← Volver al inicio
            </a>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h1>🎾 Pádel Club</h1>
                <p class="subtitle">Inicia sesión para continuar</p>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                        placeholder="correo@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary">
                    Ingresar
                </button>

                <p class="footer-text">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}">Crear cuenta</a>
                </p>
            </form>

        </div>

    </div>

</body>

</html>
