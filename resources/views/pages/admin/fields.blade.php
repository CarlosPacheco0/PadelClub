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
                    <td>{{ ucfirst($field->name) }}</td>
                    <td>{{ ucfirst($field->description) }}</td>
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
                            placeholder="Detalles de la superficie, ubicación..." autocomplete="off" maxlength="200">{{ old('desc') }}</textarea>
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
    @push('scripts')
        <script>
            window.FIELDS_CONFIG = {
                url_store: "{{ route('field.save') }}",
                url_update: "{{ route('field.update') }}"
            };

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

        @vite(['resources/js/pages/views/fields.js'])
    @endpush
@endsection
