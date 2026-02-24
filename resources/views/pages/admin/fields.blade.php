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
        <button class="btn btn-primary" id="openModal" onclick="openCreateModal()">Agregar nueva cancha</button>
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
    <div id="modalOverlay" class="drawer-overlay" onclick="closeModal()"></div>

    <!-- Side Panel / Modal -->
    <div class="side-panel" id="fieldModal">
        <div class="panel-header">
            <div>
                <h2 id="modalTitle" class="panel-title">Nueva cancha</h2>
                <p class="panel-subtitle" id="panelSubtitle">Completa la información técnica</p>
            </div>
            <button onclick="closeModal()" class="btn-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="fieldForm" method="POST" class="panel-container">
            @csrf
            <div class="panel-body">
                <input type="hidden" name="_method" id="_method">
                <input type="hidden" name="field_id" id="field_id">

                <div class="form-group">
                    <label class="form-label">Nombre de la cancha</label>
                    <input type="text" name="name" id="name" class="form-input"
                        placeholder="Ej: Cancha Central Pro" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="desc" id="description" class="form-input" rows="4"
                        placeholder="Detalles de la superficie, ubicación..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Estado de disponibilidad</label>
                    <select name="status" id="status" class="form-input" required>
                        <option value="1">Activa (Disponible para reservas)</option>
                        <option value="0">Inactiva (Mantenimiento / Cerrada)</option>
                    </select>
                </div>

                <div class="info-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>Al desactivar una cancha, los usuarios no podrán verla en el calendario de reservas.</p>
                </div>
            </div>

            <div class="panel-footer">
                <button type="submit" class="btn btn-primary" id="submitBtn">Guardar cancha</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            </div>
        </form>
    </div>


    {{-- JS --}}
    <script>
        const modal = document.getElementById('fieldModal');
        const overlay = document.getElementById('modalOverlay');
        const title = document.getElementById('modalTitle');
        const subtitle = document.getElementById('panelSubtitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('fieldForm');

        const _method = document.getElementById('_method');
        const field_id = document.getElementById('field_id');
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const statusInput = document.getElementById('status');

        const URL_STORE = "{{ route('field.save') }}";
        const URL_UPDATE = "{{ route('field.update') }}";

        function openCreateModal() {
            title.textContent = 'Nueva cancha';
            subtitle.textContent = 'Registra un nuevo espacio en el club';
            submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Guardar cancha';

            form.reset();
            form.action = URL_STORE;
            _method.value = '';
            field_id.value = '';
            statusInput.value = 1;

            togglePanel(true);
        }

        function openEditModal(field) {
            title.textContent = 'Editar cancha';
            // subtitle.textContent = `Editando ID: #${field.id}`;
            submitBtn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Actualizar datos';

            field_id.value = field.id;
            nameInput.value = field.name;
            descInput.value = field.description;
            statusInput.value = field.status;

            form.action = URL_UPDATE;
            _method.value = 'PUT';

            togglePanel(true);
        }

        function togglePanel(isOpen) {
            if (isOpen) {
                modal.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Evita scroll de fondo
            } else {
                modal.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        function closeModal() {
            togglePanel(false);
        }

        // Soporte para tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endsection
