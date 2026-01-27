<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/auth.css'])
</head>

<body>

    <div class="auth-container">

        <a href="{{ route('home') }}" class="btn-back">
            ← Volver al inicio
        </a>


        <form class="auth-card card-register" method="POST" action="{{ route('register.store') }}">
            @csrf

            <h1>🎾 Crear cuenta</h1>
            <p class="subtitle">Regístrate y reserva en segundos</p>

            <div class="form-grid">

                <!-- COLUMNA IZQUIERDA -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="name">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="correo@email.com">
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                            placeholder="099 999 9999">
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="••••••••">
                    </div>
                </div>

            </div>

            <button type="submit" class="btn-primary">
                Registrarme
            </button>

            <p class="footer-text">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Iniciar sesión</a>
            </p>

        </form>

    </div>


</body>

</html>
