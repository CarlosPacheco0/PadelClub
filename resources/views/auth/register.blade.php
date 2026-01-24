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
    <form class="auth-card" method="POST" action="{{ route('register.store') }}">

        @csrf

        <h2>Registro</h2>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="number" name="phone" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn">Registrarse</button>

        <p class="auth-link">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </form>
</div>

</body>
</html>
