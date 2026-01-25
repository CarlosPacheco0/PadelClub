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
                <h3 class="">Resumen</h3>

                <div class="info">
                    {{-- Información de la cancha  --}}
                    <div class="info-field">
                        <p><strong>Cancha:</strong> {{ $field->name }}</p>
                        <p><strong>Fecha:</strong> {{ $dateReservation->format('d/m/Y') }}</p>
                        <p>
                            <strong>Horario:</strong>
                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                        </p>
                    </div>

                    {{-- Información del cliente  --}}
                    <div class="info-client">
                        <p><strong>Cliente:</strong> {{ $user->name }}</p>
                        <p><strong>Teléfono:</strong> {{ $user->phone }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
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
                        <label>Observaciones (opcional)</label>
                        <textarea rows="3" name="observation" maxlength="300"></textarea>
                    </div>

                    <button class="btn-confirmar" type="submit">Confirmar reserva</button>
                </form>

            </div>

        </div>
    </section>
@endsection
