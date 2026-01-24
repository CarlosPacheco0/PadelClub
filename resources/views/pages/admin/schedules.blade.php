@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/schedules.css'])
@endpush

@section('content')
    <div class="">
        <h1 class="content-title">Gestión de Horarios</h1>

        <div class="actions">
            <button class="btn" onclick="openCreateModal()">Nuevo Horario</button>
        </div>

        <!-- Tabla de horarios -->
        <table class="table">
            <thead>
                <tr>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="scheduleTable">

                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->start_time->format('H:i') }}</td>
                        <td>{{ $schedule->end_time->format('H:i') }}</td>
                        <td class="actions-table">
                            <button class="btn btn-edit" onclick="openEditModal({{ $schedule }})">Editar</button>

                            {{-- Boton de eliminar registro --}}
                            <form action="{{ route('schedule.delete') }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="id" value="{{ $schedule->id }}">
                                <button class="btn btn-delete" type="submit">Eliminar</button>
                            </form>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- Modal Crear / Editar -->
    <div class="modal" id="scheduleModal">
        <div class="modal-content">
            <h2 id="modalTitle">Nuevo Horario</h2>

            <form id="scheduleForm" method="POST">
                @csrf

                <input type="hidden" name="_method" id="_method" value="PUT">
                <input type="hidden" name="id" id="scheduleId">

                <label>Hora Inicio:</label>
                <input type="time" name="start" id="start" required>

                <label>Hora Fin:</label>
                <input type="time" name="end" id="end" required>

                <label for="status">Estado:</label>
                <select name="status" id="status" required>
                    <option>-- Seleccione una opción --</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>

                <div class="modal-buttons">
                    <button type="button" onclick="closeModal()">Cancelar</button>
                    <button type="submit" id="submitBtn">Guardar</button>
                </div>
            </form>
        </div>
    </div>


    {{-- JS  --}}
    <script>
        const modal = document.getElementById('scheduleModal');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('scheduleForm');

        // URL GUARDAR / EDITAR 
        const URL_STORE = "{{ route('schedule.create') }}";
        const URL_UPDATE = "{{ route('schedule.update') }}";

        function openCreateModal() {
            title.textContent = 'Nuevo Horario';
            submitBtn.textContent = 'Guardar';

            form.reset();
            form.action = URL_STORE; // Indicar accion del formulario
            document.getElementById('_method').value = ''; // Definir metodo del Formulario

            document.getElementById('scheduleId').value = '';

            modal.style.display = 'flex';
        }

        function openEditModal(schedule) {
            title.textContent = 'Editar Horario';
            submitBtn.textContent = 'Actualizar';

            document.getElementById('scheduleId').value = schedule.id;
            document.getElementById('start').value = schedule.start_time;
            document.getElementById('end').value = schedule.end_time;
            document.getElementById('status').value = schedule.status;

            form.action = URL_UPDATE; // Indicar accion del formulario
            document.getElementById('_method').value = 'PUT'; // Definir metodo del Formulario

            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }
    </script>
@endsection
