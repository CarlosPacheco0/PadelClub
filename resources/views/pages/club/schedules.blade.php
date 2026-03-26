@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/schedules.css'])
@endpush

@section('content')
    <div class="admin-content">
        
        <div class="page-header">
            <div>
                <h1 class="content-title">Gestión de Horarios</h1>
                <p style="color: var(--text-muted); margin: 5px 0 0 0; font-size: 0.9rem;">
                    Configura los bloques de tiempo disponibles.
                </p>
            </div>

            <div class="actions">
                <button class="btn-neon" onclick="openCreateModal()">
                    <span>+</span> Nuevo Horario
                </button>
            </div>
        </div>

        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="scheduleTable">
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td style="font-weight: 600; color: #fff;">
                                {{ $schedule->start_time->format('H:i') }}
                            </td>
                            <td style="font-weight: 600; color: #fff;">
                                {{ $schedule->end_time->format('H:i') }}
                            </td>
                            <td>
                                @if($schedule->status) 
                                    <span style="color:#34d399; background:rgba(52, 211, 153, 0.1); padding:4px 10px; border-radius:99px; font-size:0.75rem; border:1px solid rgba(52, 211, 153, 0.2)">Activo</span>
                                @else
                                    <span style="color:#9ca3af; background:rgba(255,255,255,0.05); padding:4px 10px; border-radius:99px; font-size:0.75rem;">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-table">
                                    <button class="btn-icon edit" onclick="openEditModal({{ $schedule }})">
                                        Editar
                                    </button>

                                    <form action="{{ route('schedule.delete') }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este horario?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $schedule->id }}">
                                        <button class="btn-icon delete" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="modal" id="scheduleModal">
        <div class="modal-content">
            <h2 id="modalTitle" class="modal-title">Nuevo Horario</h2>

            <form id="scheduleForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="_method" value="PUT">
                <input type="hidden" name="id" id="scheduleId">

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Hora Inicio</label>
                        <input type="time" name="start" id="start" class="input-dark" required>
                    </div>

                    <div class="form-group">
                        <label>Hora Fin</label>
                        <input type="time" name="end" id="end" class="input-dark" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" id="status" class="input-dark" required>
                        <option value="1">Activo (Disponible para reservas)</option>
                        <option value="0">Inactivo (Oculto)</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" id="submitBtn" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS --}}
    <script>
        const modal = document.getElementById('scheduleModal');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('scheduleForm');

        const URL_STORE = "{{ route('schedule.create') }}";
        const URL_UPDATE = "{{ route('schedule.update') }}";

        function openCreateModal() {
            title.textContent = 'Nuevo Horario';
            submitBtn.textContent = 'Crear Horario';
            form.reset();
            form.action = URL_STORE;
            document.getElementById('_method').value = 'POST'; // POST para crear
            document.getElementById('scheduleId').value = '';
            
            // Animación de entrada
            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('show'); }, 10);
        }

        function openEditModal(schedule) {
            title.textContent = 'Editar Horario';
            submitBtn.textContent = 'Actualizar';
            
            // Llenar datos
            document.getElementById('scheduleId').value = schedule.id;
            // Asegúrate de que el formato de schedule.start_time sea compatible con input time (HH:mm)
            // Si viene con segundos (HH:mm:ss), corta los últimos 3 chars
            document.getElementById('start').value = schedule.start_time.substring(0, 5);
            document.getElementById('end').value = schedule.end_time.substring(0, 5);
            document.getElementById('status').value = schedule.status;

            form.action = URL_UPDATE;
            document.getElementById('_method').value = 'PUT';

            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('show'); }, 10);
        }

        function closeModal() {
            modal.classList.remove('show');
            setTimeout(() => { modal.style.display = 'none'; }, 300); // Esperar animación
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection