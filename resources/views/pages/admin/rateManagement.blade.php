@extends('layouts.layout')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>

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
                        @php
                            // Definimos los días en un array simple: ID => [Letra, Nombre]
                            $weekDays = [
                                1 => ['L', 'Lun'],
                                2 => ['M', 'Mar'],
                                3 => ['M', 'Mié'],
                                4 => ['J', 'Jue'],
                                5 => ['V', 'Vie'],
                                6 => ['S', 'Sáb'],
                                7 => ['D', 'Dom'],
                            ];
                        @endphp

                        @foreach ($weekDays as $id => $dayInfo)
                            <label class="day-toggle">
                                <input type="checkbox" name="days[]" value="{{ $id }}" {{-- Mantiene la selección si falla la validación --}}
                                    {{ is_array(old('days')) && in_array($id, old('days')) ? 'checked' : '' }}>

                                {{-- Agrega clase 'weekend' si es Sábado (6) o Domingo (7) --}}
                                <div class="day-circle {{ $id >= 6 ? 'weekend' : '' }}">
                                    {{ $dayInfo[0] }}
                                </div>

                                <span class="day-label">{{ $dayInfo[1] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="separator"></div>

                <div class="config-grid">

                    <div class="config-group time-group">
                        <label class="section-title">2. Rango Horario</label>
                        <div class="time-inputs">

                            <div class="time-box clockpicker" data-placement="bottom" data-align="top"
                                data-autoclose="true">
                                <span class="time-label">Desde</span>
                                <div class="time-value">
                                    <input type="text" name="start_time" class="digital-input" placeholder="00:00"
                                        readonly required value="{{ old('end_time') }}">
                                    <i class="fas fa-clock time-icon"></i>
                                </div>
                            </div>

                            <div class="time-arrow">➜</div>

                            <div class="time-box clockpicker" data-placement="bottom" data-align="top"
                                data-autoclose="true">
                                <span class="time-label">Hasta</span>
                                <div class="time-value">
                                    <input type="text" name="end_time" class="digital-input" placeholder="00:00" readonly
                                        required value="{{ old('end_time') }}">
                                    <i class="fas fa-clock time-icon"></i>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="config-group price-group">
                        <label class="section-title">3. Valor por Hora</label>
                        <div class="price-input-wrapper">
                            <span class="currency">$</span>

                            <input type="text" id="priceDisplay" class="price-input" placeholder="0" required
                                onkeyup="formatPrice(this)" autocomplete="off"
                                value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}">

                            <input type="hidden" name="price" id="priceReal" value="{{ old('price') }}">
                        </div>
                    </div>

                    <div class="config-group action-group">
                        <label class="section-title" style="visibility: hidden">Acción</label>
                        <button type="submit" class="btn-create-rate" onclick="return prepareSubmit(event)">
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
                            <tr class="rate-row" data-day="{{ $rate->day_of_week }}">
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
                                        $shortDays = [
                                            1 => 'LUN',
                                            2 => 'MAR',
                                            3 => 'MIE',
                                            4 => 'JUE',
                                            5 => 'VIE',
                                            6 => 'SAB',
                                            7 => 'DOM',
                                        ];
                                        $claseBadge = $rate->day_of_week >= 6 ? 'badge-weekend' : 'badge-weekday';
                                    @endphp
                                    <span class="badge {{ $claseBadge }}">
                                        {{ $nameDays[$rate->day_of_week] }}
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

                        @if ($rates->isEmpty())
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    <i class="fas fa-info-circle"
                                        style="margin-bottom: 10px; display: block; font-size: 24px;"></i>
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
            <p class="text-muted mb-4" style="font-size: 0.9rem;">
                Modifica el valor para este bloque horario específico.
            </p>

            <form id="formEditar" method="POST" action="#" onsubmit="return prepareEditSubmit()">
                @csrf
                @method('PUT')

                <div class="input-field">
                    <label>Nuevo Precio por Hora ($)</label>
                    <div class="price-input-wrapper">
                        <span class="currency" style="left: 12px; font-size: 1rem;">$</span>

                        <input type="text" id="modalPrecioDisplay" class="price-input" style="padding-left: 24px;"
                            required autocomplete="off" oninput="formatModalPrice(this)" placeholder="0">

                        <input type="hidden" name="id" id="modalRateID">
                        <input type="hidden" name="price" id="modalPrecioReal">
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
        // A. Inicializar el Reloj Redondo
        $(document).ready(function() {
            $('.clockpicker').clockpicker({
                // donetext: 'ACEPTAR', // Botón de confirmación
                twelvehour: false, // Usar formato 24h (cambiar a true si quieres AM/PM)
                vibrate: true // Vibrar en móviles al seleccionar
            });
        });

        // B. Máscara de Miles (Ej: 20.000)
        function formatPrice(input) {
            // 1. Obtener valor limpio (solo números)
            let value = input.value.replace(/\D/g, "");

            // 2. Guardar en hidden input
            document.getElementById('priceReal').value = value;

            // 3. Formatear visualmente
            if (value !== "") {
                // Formato con puntos de miles (es-CO = Colombia/Latam)
                input.value = new Intl.NumberFormat('es-CO').format(value);
            } else {
                input.value = "";
            }
        }

        // C. Antes de enviar
        function prepareSubmit(event) {
            // 1. Obtener los inputs
            let startInput = document.querySelector('input[name="start_time"]');
            let endInput = document.querySelector('input[name="end_time"]');
            let daysInputs = document.querySelectorAll('input[name="days[]"]:checked');

            let start = startInput.value;
            let end = endInput.value;

            // --- VALIDACIÓN A: Días Seleccionados ---
            if (daysInputs.length === 0) {
                showToast('info', 'Atención', 'Debes seleccionar al menos un día de la semana.');
                if (event) event.preventDefault();
                return false;
            }

            // --- VALIDACIÓN B: Campos Vacíos ---
            if (!start || !end) {
                showToast('info', 'Atención', 'Por favor selecciona una hora de Inicio y Fin.');
                if (event) event.preventDefault(); // Detiene el envío
                return false;
            }

            // --- VALIDACIÓN C: Lógica de Horario (Inicio < Fin) ---
            // Convertimos "14:30" a 1430 para poder compararlos como números
            let startNum = parseInt(start.replace(':', ''));
            let endNum = parseInt(end.replace(':', ''));

            if (startNum >= endNum) {
                showToast('error', 'Error', 'La hora de inicio (' + start +
                    ') no puede ser mayor o igual a la hora final (' + end + ').');


                // Ponemos el borde rojo para que vea dónde está el error
                endInput.style.border = "2px solid #ef4444";

                if (event) event.preventDefault(); // ¡AQUI DETENEMOS EL ENVÍO!
                return false;
            }

            // Si todo está bien, quitamos bordes rojos
            endInput.style.border = "";

            // --- PASO FINAL: Limpiar el Precio para Laravel ---
            let priceDisplay = document.getElementById('priceDisplay').value;
            if (!priceDisplay || priceDisplay <= 0) {
                showToast('info', 'Atención', 'Debes ingresar un precio válido mayor a 0.');

                if (event) event.preventDefault();
                return false;
            }

            // Quitar puntos y comas antes de enviar
            let cleanPrice = priceDisplay.replace(/\./g, "").replace(/,/g, "");
            document.getElementById('priceReal').value = cleanPrice;

            return true; // Dejamos pasar el formulario
        }

        // 1. ABRIR MODAL Y CARGAR DATOS
        function editarTarifa(id, precioActual) {

            // Limpieza del precio (asegurar que sea número)
            let precioNum = parseFloat(precioActual);

            // A. Llenar el input OCULTO (valor puro)
            document.getElementById('modalRateID').value = id;
            document.getElementById('modalPrecioReal').value = precioNum;

            // B. Llenar el input VISIBLE (con puntos de miles)
            // Usamos Intl para formatear 20000 -> 20.000
            let precioFormateado = new Intl.NumberFormat('es-CO').format(precioNum);
            document.getElementById('modalPrecioDisplay').value = precioFormateado;

            // Mostrar el modal
            document.getElementById('editModal').style.display = 'flex';
        }

        // 2. CERRAR MODAL
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // 3. MÁSCARA MIENTRAS ESCRIBES EN EL MODAL
        function formatModalPrice(input) {
            // Quitar todo lo que no sea número
            let value = input.value.replace(/\D/g, "");

            // Guardar valor limpio para Laravel
            document.getElementById('modalPrecioReal').value = value;

            // Poner puntos visualmente
            if (value !== "") {
                input.value = new Intl.NumberFormat('es-CO').format(value);
            } else {
                input.value = "";
            }
        }

        // 4. PREPARAR ENVÍO (Seguridad)
        function prepareEditSubmit() {

            let rateID = document.getElementById('modalRateID').value;
            if (!rateID) {
                showToast('error', 'Error', 'Error critico: No se identifico la tarifa.');
                return false;
            }

            // Asegurar que el input hidden tenga el valor sin puntos
            let displayVal = document.getElementById('modalPrecioDisplay').value;
            let cleanVal = displayVal.replace(/\./g, "").replace(/,/g, "");

            // if (!cleanVal || cleanVal <= 0) {
            //     showToast('info', 'Atención', 'Debes ingresar un precio válido mayor a 0.');
            //     return false;
            // }

            document.getElementById('modalPrecioReal').value = cleanVal;
            return true;
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
