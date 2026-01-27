@extends('layouts.layout')

@section('content')
       <!-- HERO -->
    <section class="hero">
      <div class="hero-content">
        <h1>Reserva tu cancha de pádel</h1>
        <p>Rápido, fácil y desde cualquier dispositivo</p>
        <a href="{{ route('reservation') }}" class="btn-primary">Reservar ahora</a>
      </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
      <h2>¿Cómo funciona?</h2>

      <div class="features-grid">
        <div class="feature-card">
          <span class="icon">📅</span>
          <h3>Elige la fecha</h3>
          <p>Selecciona el día disponible en el calendario.</p>
        </div>

        <div class="feature-card">
          <span class="icon">🎾</span>
          <h3>Selecciona la cancha</h3>
          <p>Consulta disponibilidad en tiempo real.</p>
        </div>

        <div class="feature-card">
          <span class="icon">⏰</span>
          <h3>Escoge el horario</h3>
          <p>Horarios claros y organizados.</p>
        </div>

        <div class="feature-card">
          <span class="icon">✅</span>
          <h3>Confirma la reserva</h3>
          <p>Reserva segura en segundos.</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta">
      <h2>Empieza ahora</h2>
      <p>Administra tus reservas sin llamadas ni complicaciones</p>
      <a href="#" class="btn-secondary">Crear cuenta</a>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
      © 2026 Pádel Club · Todos los derechos reservados
    </footer>
@endsection
