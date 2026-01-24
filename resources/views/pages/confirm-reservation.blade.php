@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/layout.css'])
@endpush

@section('content')
    <section class="section">
        <h2>Confirmar reserva</h2>
        <p class="text-muted">Completa los datos para finalizar la reserva</p>

        <div class="resumen">
            <h3>Resumen</h3>
            <p><strong>Cancha:</strong> {{ $field->name }}</p>
            <p><strong>Fecha:</strong> {{ $dateReservation->format('d/m/Y') }}</p>
            <p>
                <strong>Horario:</strong>
                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
            </p>
        </div>

        <form class="formulario" action="{{ route('reservation.save') }}" method="POST">

            {{-- Añadir token para validar que el formulario es creado por mi --}}
            @csrf

            {{-- Inputs de valores adicionales --}}
            <input type="hidden" name="field_id" value="{{ $field->id }}">
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="date" value="{{ $dateReservation }}">


            <div class="campo">
                <label>Nombre completo</label>
                <input type="text" name="name" value="{{ $user->name }}">
            </div>

            <div class="campo">
                <label>Teléfono</label>
                <input type="tel" name="phone" value="{{ $user->phone }}">
            </div>

            <div class="campo">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ $user->email }}">
            </div>

            <div class="campo">
                <label>Observaciones (opcional)</label>
                <textarea rows="3" name="observation"></textarea>
            </div>

            <button class="btn-confirmar" type="submit">Confirmar reserva</button>
        </form>
    </section>
@endsection
