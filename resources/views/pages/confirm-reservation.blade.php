@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/generate-reservation.css'])
@endpush

@section('content')
    <section class="section">
        <div id="confirm-reservation">
            <h2 class="content-title">Confirmar reserva</h2>
            <p class="text-muted">Confirma los datos para finalizar la reserva</p>

            <div class="resumen">
                <h3>Resumen de tu reserva</h3>

                <div class="info">
                    {{-- Información de la cancha  --}}
                    <div class="info-field">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p><strong>Cancha:</strong> {{ $field->name }}</p>
                            <p><strong>Fecha:</strong> {{ $dateReservation->format('d/m/Y') }}</p>
                            <p>
                                <strong>Horario:</strong>
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- Información del cliente  --}}
                    <div class="info-client">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p><strong>Cliente:</strong> {{ $user->name }}</p>
                            <p><strong>Teléfono:</strong> {{ $user->phone }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="info-client">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 100 100">
                                <path fill="#4f46e5"
                                    d="M54.284 44.798v-10.11c3.297.807 6.52 2.344 9.157 4.762l.011-.015c.197.157.436.265.708.265c.358 0 .665-.173.877-.428l.015.003l4.262-6.008l-.01-.005c.175-.202.291-.458.291-.746c0-.34-.153-.638-.387-.849c-3.953-3.651-9-5.843-14.924-6.502v-5.806h-.001c0-.637-.516-1.153-1.153-1.153h-4.578c-.637 0-1.153.516-1.153 1.153v5.659c-9.89 1.025-15.75 7.326-15.75 14.725c0 9.963 8.205 12.82 15.75 14.652v11.354c-4.845-.868-8.827-3.379-11.536-6.19q-.028-.03-.06-.058l-.052-.051l-.008.011a1.13 1.13 0 0 0-.719-.273a1.14 1.14 0 0 0-.998.608l-.014-.002l-4.125 6.124l.005.01a1.13 1.13 0 0 0-.292.748c0 .367.182.679.448.89l-.011.016c4.029 4.029 9.67 6.959 17.362 7.619v5.44c0 .637.516 1.153 1.153 1.153h4.578c.637 0 1.153-.517 1.153-1.153h.001V75.2c10.769-1.1 16.117-7.398 16.117-15.531c0-10.035-8.498-12.967-16.117-14.871m-6.886-1.686c-3.003-.951-5.055-2.051-5.055-4.176c0-2.49 1.832-4.248 5.055-4.688zm6.886 22.784V56.08c3.224 1.025 5.495 2.199 5.495 4.615c0 2.345-1.759 4.468-5.495 5.201" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p><strong>Costo:</strong> {{ $rate }}</p>
                        </div>
                    </div>
                </div>

                <form class="formulario" action="{{ route('reservation.save') }}" method="POST">

                    {{-- Añadir token para validar que el formulario es creado por mi --}}
                    @csrf

                    {{-- Inputs de valores adicionales --}}
                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    <input type="hidden" name="date" value="{{ $dateReservation }}">

                    {{-- Inputs para información del cliente  --}}
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="campo">
                        <label for="observation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                style="display: inline-block; vertical-align: middle; margin-right: 6px;">
                                <path fill="currentColor"
                                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 14H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                            Observaciones (opcional)
                        </label>
                        <textarea id="observation" name="observation" rows="4" maxlength="300"
                            placeholder="¿Tienes alguna solicitud especial? Escríbela aquí..."></textarea>
                        <small class="campo-hint">Máximo 300 caracteres</small>
                    </div>

                    <button class="btn-confirmar" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19L21 7l-1.41-1.41z" />
                        </svg>
                        Confirmar reserva
                    </button>
                </form>

            </div>

        </div>
    </section>
@endsection
