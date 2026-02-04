@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/rateManagement.css'])
@endpush

@section('content')
    <div class="container-tarifas">

        {{-- SECCIÓN 1: FORMULARIO DE CREACIÓN MASIVA (El que ya diseñamos) --}}
        <div class="pricing-card mb-5">
            <div class="pricing-header">
                <i class="fas fa-tags"></i>
                <h3>Configuración Masiva de Tarifas</h3>
            </div>

            <form action="{{ route('rate.store') }}" method="POST">
                @csrf
                <!-- Selección de Días -->
                <div class="section-group">
                    <span class="section-label">1. Selecciona los días</span>
                    <div class="days-container">
                        <label class="day-pill">
                            <input type="checkbox" name="days[]" value="lunes-viernes">
                            <span class="pill-content">Lunes a Viernes</span>
                        </label>
                        <label class="day-pill">
                            <input type="checkbox" name="days[]" value="sabado">
                            <span class="pill-content">Sábado</span>
                        </label>
                        <label class="day-pill">
                            <input type="checkbox" name="days[]" value="domingo">
                            <span class="pill-content">Domingo</span>
                        </label>
                    </div>
                </div>

                <!-- Inputs de Rango y Precio -->
                <div class="section-group">
                    <span class="section-label">2. Define Rango y Precio</span>
                    <div class="inputs-row">
                        <div class="input-field">
                            <label>Desde</label>
                            <input type="time" name="start_time" required>
                        </div>
                        <div class="input-field">
                            <label>Hasta</label>
                            <input type="time" name="end_time" required>
                        </div>
                        <div class="input-field">
                            <label>Precio por hora ($)</label>
                            <input type="number" name="price" step="0.01" placeholder="0.00" required>
                        </div>
                        <button type="submit" class="btn-submit">Aplicar Tarifa</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- SECCIÓN 2: TABLA DE VISUALIZACIÓN Y EDICIÓN --}}
        <div class="pricing-card">
            <div class="pricing-header space-between">
                <div class="flex-center">
                    <i class="fas fa-list-alt"></i>
                    <h3>Tarifas Vigentes</h3>
                </div>
                {{-- Filtro rápido opcional --}}
                <div class="filters">
                    <select id="filtroDia" class="form-select-sm">
                        <option value="all">Todos los días</option>
                        <option value="1">Lunes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-tarifas">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Precio</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rates as $rate)
                            <tr>
                                <td>
                                    @php
                                        $nameDays = [
                                            1 => 'Lunes',
                                            2 => 'Martes',
                                            3 => 'Miércoles',
                                            4 => 'Jueves',
                                            5 => 'Viernes',
                                            6 => 'Sábado',
                                            7 => 'Domingo',
                                        ];
                                        $claseBadge = $rate->day_week >= 6 ? 'badge-weekend' : 'badge-weekday';
                                    @endphp
                                    <span class="badge {{ $claseBadge }}">
                                        {{ $nameDays[$rate->day_week] }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">
                                    {{ $rate->start_time->format('H:i') }} - {{ $rate->end_time->format('H:i') }}
                                </td>
                                <td class="text-price">${{ number_format($rate->price, 2) }}</td>
                                <td>
                                <td class="text-right">
                                    <!-- Botón Editar (Abre Modal Individual) -->
                                    <button class="btn-icon edit"
                                        onclick="editarTarifa({{ $rate->id }}, '{{ $rate->price }}')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulario Eliminar -->
                                    <form action="{{ route('rate.delete', $rate->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete"
                                            onclick="return confirm('¿Eliminar esta tarifa?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="pagination-container">
                {{-- {{ $tarifas->links() }} --}}
            </div>
        </div>
    </div>

    {{-- MODAL SIMPLE PARA EDICIÓN RÁPIDA (Script al final) --}}
    <div id="editModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h4>Editar Precio Individual</h4>
            <form id="formEditar" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="input-field">
                    <label>Nuevo Precio</label>
                    <input type="number" name="precio" id="modalPrecio" step="0.01" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambio</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editarTarifa(id, precioActual) {
            // Configuramos la acción del formulario dinámicamente
            const form = document.getElementById('formEditar');
            form.action = `/tarifas/${id}`; // Asegúrate de que esta ruta exista en tu web.php

            // Ponemos el valor actual en el input
            document.getElementById('modalPrecio').value = precioActual;

            // Mostramos el modal
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
@endsection
