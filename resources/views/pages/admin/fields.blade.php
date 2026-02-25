@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/fields.css'])
@endpush

@section('content')
    <h2 class="content-title">Gestión de canchas</h2>

    <section class="filters">
        <div class="filter-group">
            <label for="tipo">Estado:</label>
            <select id="tipo" class="form-select">
                <option value="todos">Todos</option>
                <option value="activas">Solo Activas</option>
                <option value="inactivas">Solo Inactivas</option>
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
                    <td class="row-status">{{ $field->status == 0 ? 'Inactiva' : 'Activa' }}</td>
                    <td class="actions-table">

                        <!-- Botón Editar -->
                        <button class="btn btn-edit" onclick="openEditModal({{ $field }})">Editar</button>

                        {{-- Boton de eliminar registro --}}
                        <form action="{{ route('field.delete') }}" method="POST"
                            onsubmit="confirmarEliminacion(event, '{{ $field->id }}', '{{ $field->name }}')">
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
    <div id="modalOverlay" class="drawer-overlay">
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
                            placeholder="Ej: Cancha Central Pro" autocomplete="off" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="desc" id="description" class="form-input" rows="4"
                            placeholder="Detalles de la superficie, ubicación..." autocomplete="off">{{ old('desc') }}</textarea>
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Guardar cancha</button>
                </div>
            </form>
        </div>
    </div>

    <div id="customConfirm" class="confirm-overlay">
        <div class="confirm-card">
            <div class="confirm-icon">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 class="confirm-title">¿Eliminar cancha?</h3>
            <p class="confirm-text">
                Estás a punto de eliminar la cancha <strong id="fieldName" style="color: #fff;"></strong>.
                Esta acción no se puede deshacer.
            </p>
            <div class="confirm-actions">
                <button type="button" class="btn-confirm-cancel" onclick="cerrarConfirmacion()">Cancelar</button>
                <button type="button" class="btn-confirm-delete" id="btnAceptarEliminar">Eliminar ahora</button>
            </div>
        </div>
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

        let formToDelete = null;

        const btnDelete = document.getElementById('btnAceptarEliminar');

        // Lógica de Filtrado por Estado
        const filterStatusInput = document.getElementById('tipo');
        const tableRows = document.querySelectorAll('.table tbody tr');

        filterStatusInput.onchange = () => {
            const statusTerm = filterStatusInput.value; // "todos", "activas", "inactivas"

            tableRows.forEach(row => {
                const rowTable = row.querySelector('.row-status');

                // Si la fila está vacía, no hacemos nada
                if (!rowTable) return;

                const statusText = rowTable.textContent.trim().toLowerCase(); // "activa" o "inactiva"

                let matchesStatus = true;
                if (statusTerm === 'activas') matchesStatus = (statusText === 'activa');
                if (statusTerm === 'inactivas') matchesStatus = (statusText === 'inactiva');

                // Mostrar u ocultar con una transición suave
                if (matchesStatus) {
                    row.style.display = '';
                    row.style.opacity = '1';
                } else {
                    row.style.display = 'none';
                }
            });
        };


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
            submitBtn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Actualizar datos';

            field_id.value = field.id;
            nameInput.value = field.name;
            descInput.value = field.description;
            statusInput.value = field.status;

            form.action = URL_UPDATE;
            _method.value = 'PUT';

            togglePanel(true);
        }

        function confirmarEliminacion(event, field, name) {
            event.preventDefault(); // Detenemos el envío automático

            // Guardamos la referencia del formulario que disparó el evento
            formToDelete = event.currentTarget;

            // Seteamos el nombre en el modal
            document.getElementById('fieldName').textContent = name;

            // Mostramos el modal
            const modal = document.getElementById('customConfirm');
            modal.classList.add('active');
        }

        function cerrarConfirmacion() {
            document.getElementById('customConfirm').classList.remove('active');
            formToDelete = null;
        }

        // Escuchamos el clic en el botón de eliminar del modal
        btnDelete.onclick = () => {
            if (formToDelete) {
                formToDelete.submit(); // Enviamos el formulario original
            }
        };

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


        @if (session('info') || old())
            window.onload = function() {
                // Si hay un mensaje de info en la sesión, 
                // abrimos el modal automáticamente al recargar la página.
                document.getElementById('fieldModal').classList.add('active');
                document.getElementById('modalOverlay').classList.add('active');

                let field_id = "{{ old('field_id') }}";

                if (field_id) {

                    let old_data = {
                        id: field_id,
                        name: "{{ old('name') }}",
                        description: "{{ old('desc') }}",
                        status: "{{ old('status') }}"
                    }
                    openEditModal(old_data);                    
                } else {
                    openCreateModal();
                }

            };
        @endif
    </script>
@endsection
