@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/information.css'])
@endpush

@section('content')
    <section class="section">
        <h2>Información del club</h2>
        <p class="text-muted">
            Conoce nuestras normas, horarios y recomendaciones
        </p>

        <div class="info-grid">

            <div class="info-box">
                <h3>🏟️ Canchas</h3>
                <ul>
                    <li>Canchas de pádel profesionales</li>
                    <li>Iluminación nocturna</li>
                    <li>Mantenimiento diario</li>
                </ul>
            </div>

            <div class="info-box">
                <h3>⏰ Horarios</h3>
                <ul>
                    <li>Lunes a Viernes: 08:00 - 22:00</li>
                    <li>Sábados: 08:00 - 20:00</li>
                    <li>Domingos: 09:00 - 18:00</li>
                </ul>
            </div>

            <div class="info-box">
                <h3>📋 Reglas básicas</h3>
                <ul>
                    <li>Llegar 10 minutos antes</li>
                    <li>Uso obligatorio de calzado adecuado</li>
                    <li>Respetar el horario reservado</li>
                </ul>
            </div>

        </div>
    </section>
@endsection
