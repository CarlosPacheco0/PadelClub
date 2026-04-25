@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/club/club-settings.css'])
@endpush

@section('content')
    <header class="topbar">
        <div style="color: var(--text-muted);"><i class="fas fa-cog"></i> Configuración de Sede</div>
        <div style="color: white; font-weight: bold;">Pádel Center Norte</div>
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
                <div class="avatar-circle">
                    <i class="fas fa-camera"></i>
                </div>
                <h3 style="color: white; font-size: 1rem; margin-bottom: 0.5rem;">Logo del Club</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; text-align: center; margin-bottom: 1.5rem;">
                    Recomendado: 500x500px, formato PNG o JPG.
                </p>
                <button type="button" class="btn-outline" style="width: 100%;">Subir Imagen</button>
            </div>

            <div class="glass-panel settings-main">
                <form action="#" method="POST">

                    <h3
                        style="color: white; font-size: 1.1rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                        Información Básica
                    </h3>

                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre del Club</label>
                            <input type="text" name="name" class="custom-input" value="Pádel Center Norte" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Teléfono de Reservas</label>
                            <input type="text" name="contact_phone" class="custom-input" value="320 123 4567" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="city" class="custom-input" value="Ocaña" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dirección Exacta</label>
                            <input type="text" name="address" class="custom-input"
                                value="Sector El Bosque, Vía Principal" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción del Club (Opcional)</label>
                        <textarea name="description" class="custom-input" rows="4" style="resize: vertical; padding-left: 1rem;"
                            placeholder="Cuéntale a los deportistas sobre tus canchas, servicios extra, parqueadero..."></textarea>
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
@endsection
