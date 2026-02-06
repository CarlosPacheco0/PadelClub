@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@section('content')
    <!-- HERO SECTION: Estilo limpio con imagen a la derecha -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-text">
                <h1>El software ideal para <span class="highlight">reservar tu cancha</span></h1>
                <p>Automatiza y gestiona tus reservas de pádel. Rápido, fácil y desde cualquier dispositivo.</p>

                <div class="hero-actions">
                    <a href="{{ route('reservation') }}" class="btn-primary">
                        Reservar ahora
                    </a>
                    <a href="#features" class="btn-outline">
                        Ver cómo funciona
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <!-- IMAGEN DEL MOCKUP: Reemplaza 'src' con la ruta de tu imagen de celular -->
                <!-- He puesto un placeholder visual por ahora -->
                <div class="phone-mockup">
                    <img src="{{ Vite::asset('resources/images/home.jpg') }}" alt="Vista previa de la aplicación con una cancha de pádel">
                </div>
                <!-- Decoración de fondo (Blob) -->
                <div class="blob-bg"></div>
            </div>
        </div>
    </section>

    <!-- WAVE DIVIDER: Separador estilo "Ola" con gradiente -->
    <div class="wave-divider">
        <div class="wave-content">
            <h2>¿Cómo funciona?</h2>
            <p>Descubre lo fácil que es jugar con nosotros</p>
        </div>
        <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1"
                d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>

    <!-- FEATURES: Grid limpio con iconos -->
    <section class="features" id="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon-container">
                    <span class="icon">📅</span>
                </div>
                <h3>Elige la fecha</h3>
                <p>Navega por nuestro calendario y encuentra el día perfecto para tu partido.</p>
            </div>

            <div class="feature-card">
                <div class="icon-container">
                    <span class="icon">🎾</span>
                </div>
                <h3>Selecciona la cancha</h3>
                <p>Visualiza las canchas disponibles y elige tu favorita al instante.</p>
            </div>

            <div class="feature-card">
                <div class="icon-container">
                    <span class="icon">⏰</span>
                </div>
                <h3>Escoge el horario</h3>
                <p>Horarios organizados para que nunca pierdas tu tiempo de juego.</p>
            </div>

            <div class="feature-card">
                <div class="icon-container">
                    <span class="icon">✅</span>
                </div>
                <h3>Confirma y juega</h3>
                <p>Finaliza tu reserva en segundos y recibe confirmación inmediata.</p>
            </div>
        </div>
    </section>

    <!-- CTA: Sección final -->
    <section class="cta-container">
        <div class="cta">
            <div class="cta-content">
                <h2>¿Listo para el partido?</h2>
                <p>No hagas esperar a tu equipo. Reserva ahora en segundos.</p>
                <a href="#" class="btn-white">Crear cuenta gratis</a>
            </div>
            <div class="cta-decoration"></div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <p>© 2026 Pádel Club · Todos los derechos reservados</p>
    </footer>
@endsection
