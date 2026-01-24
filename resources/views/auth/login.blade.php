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
    <form class="auth-card" method="POST" action="{{ route('login') }}">

        @csrf

        <h2>Iniciar Sesión</h2>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn">Ingresar</button>

        <p class="auth-link">
            ¿No tienes cuenta? <a href="/register">Regístrate</a>
        </p>
    </form>
</div>

</body>
</html>
