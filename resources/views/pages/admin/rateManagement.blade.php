@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/rateManagement.css'])
@endpush

@section('content')
    <div class="container-tarifas">

        {{-- SECCIÓN 1: CONFIGURADOR DE TARIFAS (NEXT GEN UI) --}}
        <div class="pricing-card mb-5">
            <div class="pricing-header">
                <div class="header-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div class="header-text">
                    <h3>Nueva Regla de Tarifa</h3>
                    <p class="text-muted">Configura días específicos y rangos horarios para tus canchas.</p>
                </div>
            </div>

            <form action="{{ route('rate.store') }}" method="POST">
                @csrf
                
                <div class="config-section">
                    <label class="section-title">1. ¿Qué días aplica esta tarifa?</label>
                    <div class="week-selector">
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="1">
                            <div class="day-circle">L</div>
                            <span class="day-label">Lun</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="2">
                            <div class="day-circle">M</div>
                            <span class="day-label">Mar</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="3">
                            <div class="day-circle">M</div>
                            <span class="day-label">Mié</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="4">
                            <div class="day-circle">J</div>
                            <span class="day-label">Jue</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="5">
                            <div class="day-circle">V</div>
                            <span class="day-label">Vie</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="6">
                            <div class="day-circle weekend">S</div>
                            <span class="day-label">Sáb</span>
                        </label>
                        <label class="day-toggle">
                            <input type="checkbox" name="days[]" value="7">
                            <div class="day-circle weekend">D</div>
                            <span class="day-label">Dom</span>
                        </label>
                    </div>
                </div>

                <div class="separator"></div>

                <div class="config-grid">
                    
                    <div class="config-group time-group">
                        <label class="section-title">2. Rango Horario</label>
                        <div class="time-inputs">
                            <div class="time-box">
                                <span class="time-label">Desde</span>
                                <input type="time" name="start_time" required class="digital-input">
                            </div>
                            <div class="time-arrow">➜</div>
                            <div class="time-box">
                                <span class="time-label">Hasta</span>
                                <input type="time" name="end_time" required class="digital-input">
                            </div>
                        </div>
                    </div>

                    <div class="config-group price-group">
                        <label class="section-title">3. Valor por Hora</label>
                        <div class="price-input-wrapper">
                            <span class="currency">$</span>
                            <input type="number" name="price" step="0.01" placeholder="00.00" required class="price-input">
                        </div>
                    </div>

                    <div class="config-group action-group">
                        <label class="section-title" style="visibility: hidden;">Acción</label> <button type="submit" class="btn-create-rate">
                            <i class="fas fa-plus-circle"></i> Crear Tarifa
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- SECCIÓN 2: TABLA DE VISUALIZACIÓN Y EDICIÓN --}}
        <div class="pricing-card">
            <div class="pricing-header rates-header">
                <div class="header-text">
                    <h3>Tarifas Vigentes</h3>
                    <p class="text-muted">Lista completa de reglas activas.</p>
                </div>
                
                {{-- Filtro rápido --}}
                <div class="filters">
                    <select id="filtroDia" class="form-select-sm" onchange="filtrarTabla(this.value)">
                        <option value="all">Todos los días</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
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
                            <tr class="rate-row" data-day="{{ $rate->day_week }}">
                                <td>
                                    @php
                                        $nameDays = [
                                            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
                                            4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
                                        ];
                                        $shortDays = [
                                            1 => 'LUN', 2 => 'MAR', 3 => 'MIE',
                                            4 => 'JUE', 5 => 'VIE', 6 => 'SAB', 7 => 'DOM',
                                        ];
                                        $claseBadge = $rate->day_week >= 6 ? 'badge-weekend' : 'badge-weekday';
                                    @endphp
                                    <span class="badge {{ $claseBadge }}">
                                        {{ $nameDays[$rate->day_week] }}
                                    </span>
                                </td>
                                <td class="font-weight-bold text-white">
                                    {{ $rate->start_time->format('H:i') }} - {{ $rate->end_time->format('H:i') }}
                                </td>
                                <td class="text-price">${{ number_format($rate->price, 2) }}</td>
                                <td class="text-right">
                                    <button class="btn-icon edit"
                                        onclick="editarTarifa({{ $rate->id }}, '{{ $rate->price }}')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

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
                        
                        @if($rates->isEmpty())
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    <i class="fas fa-info-circle" style="margin-bottom: 10px; display: block; font-size: 24px;"></i>
                                    No hay tarifas configuradas aún.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container mt-4">
                {{-- {{ $rates->links() }} --}}
            </div>
        </div>
    </div>

    {{-- MODAL DE EDICIÓN RÁPIDA --}}
    <div id="editModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h4>Editar Precio Individual</h4>
            <p class="text-muted mb-4" style="font-size: 0.9rem;">Modifica el valor para este bloque horario específico.</p>
            
            <form id="formEditar" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="input-field">
                    <label>Nuevo Precio por Hora ($)</label>
                    <div class="price-input-wrapper">
                        <span class="currency" style="left: 12px; font-size: 1rem;">$</span>
                        <input type="number" name="price" id="modalPrecio" step="0.01" required 
                               class="price-input" style="padding-left: 24px;">
                    </div>
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
            const form = document.getElementById('formEditar');
            // Asegúrate de que esta ruta coincida con tu routes/web.php
            // Ejemplo: Route::put('/tarifas/{id}', [RateController::class, 'update'])->name('rate.update');
            form.action = `/tarifas/${id}`; 

            document.getElementById('modalPrecio').value = precioActual;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Script simple para filtrar la tabla en el cliente (opcional)
        function filtrarTabla(dia) {
            const filas = document.querySelectorAll('.rate-row');
            filas.forEach(fila => {
                if (dia === 'all' || fila.getAttribute('data-day') === dia) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }
    </script>
@endsection