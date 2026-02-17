@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/fields.css'])
@endpush

@section('content')
    <h2 class="content-title">Gestión de canchas</h2>

    <section class="filters">
        <div>
            <label for="tipo">Estado:</label>
            <select id="tipo">
                <option value="todos">Todos</option>
                <option value="activas">Activas</option>
                <option value="inactivas">Inactivas</option>
            </select>
        </div>
    </section>

    <div class="actions">
        <button class="btn" id="openModal" onclick="openCreateModal()">Agregar nueva cancha</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @forelse  ($fields as $field)
                <tr>
                    <td>{{ $field->name }}</td>
                    <td>{{ $field->description }}</td>
                    <td>{{ $field->status == 0 ? 'Inactiva' : 'Activa' }}</td>
                    <td class="actions-table">
                        {{-- <button class="btn btn-reservar">Reservar</button>

                        @if ($field->status == 1)
                            <button class="btn btn-bloquear">Bloquear</button>
                        @endif

                        <button class="btn btn-ver">Ver Reserva</button> --}}

                        <!-- Botón Editar -->
                        <button class="btn btn-edit" onclick="openEditModal({{ $field }})">Editar</button>

                        {{-- Boton de eliminar registro --}}
                        <form action="{{ route('field.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="field_id" value="{{ $field->id }}">
                            <button class="btn btn-delete" type="submit">Eliminar</button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay registros para mostrar</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal Crear / Editar Cancha --}}
    <div class="modal" id="fieldModal">
        <div class="modal-content">
            <h2 id="modalTitle">Nueva cancha</h2>

            <form id="fieldForm" method="POST">
                @csrf

                <input type="hidden" name="_method" id="_method">
                <input type="hidden" name="field_id" id="field_id">

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" name="desc" id="description" required>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" id="status" required>
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-save" id="submitBtn">Guardar</button>
                </div>
            </form>
        </div>
    </div>



    {{-- JS --}}
    <script>
        const modal = document.getElementById('fieldModal');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('fieldForm');

        const _method = document.getElementById('_method');
        const field_id = document.getElementById('field_id');

        const name = document.getElementById('name');
        const description = document.getElementById('description');
        const status = document.getElementById('status');

        const URL_STORE = "{{ route('field.save') }}";
        const URL_UPDATE = "{{ route('field.update') }}";

        // CREAR
        function openCreateModal() {
            // title.textContent = 'Nueva cancha';
            submitBtn.textContent = 'Guardar';

            form.reset();
            form.action = URL_STORE;
            _method.value = '';
            field_id.value = '';
            status.value = 1;

            modal.style.display = 'block';
        }

        // EDITAR
        function openEditModal(field) {
            title.textContent = 'Editar cancha';
            submitBtn.textContent = 'Actualizar';

            field_id.value = field.id;
            name.value = field.name;
            description.value = field.description;
            status.value = field.status;

            form.action = URL_UPDATE;
            _method.value = 'PUT';

            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        // Cerrar click fuera
        // window.addEventListener('click', (e) => {
        //     if (e.target === modal) closeModal();
        // });
    </script>
@endsection
