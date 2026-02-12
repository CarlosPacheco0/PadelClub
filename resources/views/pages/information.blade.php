@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/information.css'])
@endpush

@section('content')
    <section class="page-container">
        
        <div class="header-section text-center">
            <h1 class="content-title">Información del Club</h1>
            <p class="text-muted">
                Conoce nuestras instalaciones, consulta los horarios y revisa las normas para disfrutar de tu mejor partido.
            </p>
        </div>

        <div class="info-grid">

            <div class="info-card">
                <div class="card-icon icon-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="3" x2="12" y2="21"></line>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
                <div class="card-content">
                    <h3>Canchas Profesionales</h3>
                    <ul>
                        <li>Canchas de pádel con medidas WPT</li>
                        <li>Iluminación LED nocturna</li>
                        <li>Cristales panorámicos de seguridad</li>
                        <li>Mantenimiento diario de césped</li>
                    </ul>
                </div>
            </div>

            <div class="info-card">
                <div class="card-icon icon-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="card-content">
                    <h3>Horarios de Atención</h3>
                    <ul class="schedule-list">
                        <li>
                            <span>Lunes a Viernes</span>
                            <span class="time">08:00 - 22:00</span>
                        </li>
                        <li>
                            <span>Sábados</span>
                            <span class="time">08:00 - 20:00</span>
                        </li>
                        <li>
                            <span>Domingos</span>
                            <span class="time">09:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="info-card">
                <div class="card-icon icon-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="card-content">
                    <h3>Reglas Básicas</h3>
                    <ul>
                        <li>Llegar 10 minutos antes del turno</li>
                        <li>Uso obligatorio de calzado adecuado</li>
                        <li>Respetar estrictamente el horario</li>
                        <li>Mantener el orden y limpieza</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>
@endsection