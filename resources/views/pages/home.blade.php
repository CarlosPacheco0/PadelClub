@extends('layouts.layout')

@section('content')
    <section class="hero">
        <div class="hero-content">
            <h1>Reserva tu cancha de pádel</h1>
            <p>Rápido, fácil y desde cualquier dispositivo</p>
            <a href="{{ route('reservation') }}" class="btn-primary">Reservar ahora</a>
        </div>
    </section>

    <section class="section">
        <h2>CONTENIDO</h2>

        <div class="cards">
            <a href="{{ route('reservation') }}" class="card">
                <h3>Reservas</h3>
                <p>Consulta horarios disponibles y reserva.</p>
            </a>

            <a href="{{ route('information') }}" class="card">
                <h3>Información</h3>
                <p>Conoce nuestras canchas y normas.</p>
            </a>

            <a href="{{ route('contact') }}" class="card">
                <h3>Contacto</h3>
                <p>Escríbenos si tienes dudas.</p>
            </a>
        </div>
    </section>

    <footer class="footer">
        © 2026 Pádel Club
    </footer>
@endsection
