@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/rateManagement.css'])
@endpush

@section('content')
    <div class="pricing-card">
        <div class="pricing-header">
            <i class="fas fa-tags"></i>
            <h3>Configuración de Tarifas</h3>
        </div>

        <form action="{{ route('schedules.rateManagement') }}" method="POST">
            @csrf

            <div class="section-group">
                <span class="section-label">1. Selecciona los días</span>
                <div class="days-container">
                    <label class="day-pill">
                        <input type="checkbox" name="dias[]" value="lunes-viernes">
                        <span class="pill-content">Lunes a Viernes</span>
                    </label>
                    <label class="day-pill">
                        <input type="checkbox" name="dias[]" value="sabado">
                        <span class="pill-content">Sábado</span>
                    </label>
                    <label class="day-pill">
                        <input type="checkbox" name="dias[]" value="domingo">
                        <span class="pill-content">Domingo</span>
                    </label>
                </div>
            </div>

            <div class="section-group">
                <span class="section-label">2. Define el rango y precio</span>
                <div class="inputs-row">
                    <div class="input-field">
                        <label>Desde</label>
                        <input type="time" name="hora_inicio" required>
                    </div>

                    <div class="input-field">
                        <label>Hasta</label>
                        <input type="time" name="hora_fin" required>
                    </div>

                    <div class="input-field">
                        <label>Precio ($)</label>
                        <input type="number" name="precio" step="0.01" placeholder="0.00" required>
                    </div>

                    <button type="submit" class="btn-submit">
                        Actualizar Rango
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>



    </script>
@endsection
