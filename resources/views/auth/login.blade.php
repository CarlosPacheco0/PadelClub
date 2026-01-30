<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Pádel Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css'])
</head>
<body>

    <div class="split-screen">
        
        <!-- IZQUIERDA: Visual / Marca (Solo visible en Desktop) -->
        <div class="visual-side">
            <div class="visual-content">
                <h2>Bienvenido de nuevo</h2>
                <p>Gestiona tus reservas, encuentra compañeros y mejora tu juego en las mejores canchas de la ciudad.</p>
            </div>
        </div>

        <!-- DERECHA: Formulario -->
        <div class="form-side">
            
            <a href="{{ route('home') }}" class="btn-back">
                ← Volver
            </a>

            <div class="form-container">
                <div class="header-text">
                    <h1>Iniciar Sesión</h1>
                    <p class="subtitle">Ingresa tus datos para acceder a tu cuenta.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" required autocomplete="email" autofocus
                            placeholder="ejemplo@correo.com">
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
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('register') }}">Regístrate gratis</a>
                    </p>
                </form>
            </div>
        </div>

    </div>

</body>
</html>