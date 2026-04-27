@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/club/club-settings.css'])
@endpush

@section('content')
    <header class="topbar">
        <div style="color: var(--text-muted);"><i class="fas fa-cog"></i> Configuración de Sede</div>
        <div style="color: white; font-weight: bold;">{{ $club->name }}</div>
    </header>

    <div class="content-area">
        <div style="margin-bottom: 1.5rem;">
            <h2 style="color: white; font-size: 1.5rem;">Detalles del Club</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                Actualiza la información pública de tu sede deportiva para que los jugadores te encuentren.
            </p>
        </div>

        <div class="settings-container">
            <div class="glass-panel settings-sidebar">
                @if ($club->logo_path)
                    <button id="btn_delete_logo" class="btn-delete-icon" type="button" onclick="removeLogo()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                @endif

                <div class="avatar-circle" onclick="document.getElementById('logo_input').click()"
                    style="cursor: pointer; overflow: hidden; position: relative;">
                    @if ($club->logo_path)
                        <img id="logo_preview" src="{{ asset('storage/' . $club->logo_path) }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
                        <i class="fas fa-camera" id="camera_icon" style="display: none"></i>
                    @else
                        <i class="fas fa-camera" id="camera_icon"></i>
                        <img id="logo_preview" src=""
                            style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    @endif
                </div>

                <h3 style="color: white; font-size: 1rem; margin-top: 1rem; margin-bottom: 0.5rem;">Logo del Club</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; text-align: center; margin-bottom: 1.5rem;">
                    {{-- Recomendado: 500x500px, formato PNG o JPG. --}}
                    Recomendado: formato PNG o JPG.
                </p>

                <button type="button" class="btn-outline" style="width: 100%;" onclick="load_image()">
                    Subir Imagen
                </button>
            </div>

            <div class="glass-panel settings-main">
                <form action="{{ route('update_club_settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3
                        style="color: white; font-size: 1.1rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                        Información Básica
                    </h3>

                    {{-- INPUTS NECESARIO QUE ESTAN OCULTOS --}}
                    <input type="text" name="club_id" id="club_id" hidden> {{-- Club ID --}}

                    <input type="file" id="logo_input" name="logo" accept="image/*" style="display: none;">
                    {{-- Logo Club --}}

                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre del Club</label>
                            <input type="text" name="name" class="custom-input" value="{{ $club->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Teléfono de Reservas</label>
                            <input type="text" name="contact_phone" class="custom-input"
                                value="{{ $club->contact_phone }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">País</label>
                            <select name="country_id" class="custom-input" required>
                                <option value="" hidden>Seleccione una opción</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id', $loc_club['city_id']) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Departamento/Provincia</label>
                            <select name="dep_id" class="custom-input" required>
                                <option value="" hidden>Seleccione una opción</option>
                                @foreach ($departments as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ old('country_id', $loc_club['dep_id']) == $dep->id ? 'selected' : '' }}>
                                        {{ $dep->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <select name="city_id" class="custom-input" required>
                                <option value="" hidden>Seleccione una opción</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('country_id', $loc_club['city_id']) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dirección Exacta</label>
                            <input type="text" name="address" class="custom-input" value="{{ $club->address }}"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción del Club (Opcional)</label>
                        <textarea name="description" class="custom-input" rows="4"
                            style="resize: vertical; padding-left: 1rem; resize: none"
                            placeholder="Cuéntale a los deportistas sobre tus canchas, servicios extra, parqueadero...">{{ $club->description }}</textarea>
                    </div>

                    <div class="divider"></div>

                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" class="btn-outline">Descartar Cambios</button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Guardar Información
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('logo_input').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('logo_preview');
                const icon = document.getElementById('camera_icon');

                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (icon) icon.style.display = 'none';
            }
        }

        function load_image() {
            document.getElementById('logo_input').click()
        }

        // Función para quitar la imagen
        function removeLogo(event) {
            // event.stopPropagation(); // IMPORTANTE: evita que se abra el buscador de archivos al hacer clic en la X

            const preview = document.getElementById('logo_preview');
            const icon = document.getElementById('camera_icon');
            const btnDelete = document.getElementById('btn_delete_logo');
            const fileInput = document.getElementById('logo_input');
            const removeInput = document.getElementById('remove_logo_input');

            // 1. Limpiar visualmente
            preview.src = "";
            preview.style.display = "none";
            if (icon) icon.style.display = "block";
            btnDelete.style.display = "none";

            // 2. Resetear inputs
            fileInput.value = ""; // Limpia el archivo seleccionado actualmente
            removeInput.value = "1"; // Marca para borrar en la base de datos
        }
    </script>
@endsection
