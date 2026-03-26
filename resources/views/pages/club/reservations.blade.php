@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/reservations.css'])
@endpush

@section('content')
    <h2 class="content-title">Gestión de reservas</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Cancha</th>
                <th>Fecha de reserva</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($reservations as $reservation)
                <tr>
                    <td>{{ ucfirst($reservation->user->name) }}</td>
                    <td>{{ ucfirst($reservation->field->name) }}</td>
                    <td>{{ $reservation->date->format('d-m-Y') }}</td>
                    <td>
                        {{ $reservation->schedule->start_time->format('H:i') . ' - ' . $reservation->schedule->end_time->format('H:i') }}
                    </td>
                    <td>{{ $reservation->status_reservation }}</td>
                    <td>{{ ucfirst($reservation->observation) }}</td>

                    <td class="actions-table">
                        <button
                            class="btn btn-edit {{ $reservation->status_reservation == 'cancelada' ? 'btn-disabled' : '' }}"
                            onclick='openReservationModal({{ $reservation }})'>
                            Editar
                        </button>

                        <form id="form-cancel-{{ $reservation->id }}" method="POST" action="{{ route('res.cancel') }}"
                            onsubmit="confirmCancellation(event, '{{ $reservation->date->format('d-m-Y') }}')">
                            @csrf
                            @method('PUT')

                            <button
                                class="btn btn-delete {{ $reservation->status_reservation == 'cancelada' ? 'btn-disabled' : '' }}"
                                type="submit">Cancelar</button>


                            <input type="hidden" name="id" value="{{ $reservation->id }}">
                            <input type="hidden" name="flag" value="admin">
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-data">No hay registros para mostrar</td>
                </tr>
            @endforelse

        </tbody>
    </table>


    <!-- Modal Editar Reserva -->
    <div id="editReservationOverlay" class="drawer-overlay">
        <div class="side-panel" id="editReservationPanel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Editar Reserva</h2>
                    <p class="panel-subtitle">Modifica los detalles de la reserva</p>
                </div>
                <button type="button" onclick="closeReservationModal()" class="btn-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('reservation.update') }}" method="POST" class="panel-container">
                @csrf
                @method('PUT')

                <div class="panel-body">
                    <input type="hidden" name="reservation_id" id="res_id">

                    <div class="form-group">
                        <label class="form-label">Usuario</label>
                        <input type="text" id="res_user" class="form-input" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cancha</label>
                        <select name="field_id" id="res_field" class="form-input" required></select>
                    </div>

                    <div class="form-group" style="display: flex; gap: 1rem;">
                        <div style="flex: 1;">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="date" id="res_date" class="form-input" required>
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label">Horario</label>
                            <select name="schedule_id" id="res_schedule" class="form-input" required></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="status" id="res_status" class="form-input" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="completada">Completada</option>
                        </select>
                    </div>

                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Al actualizar el estado de la reserva, el sistema aplicará los cambios al calendario general.</p>
                    </div>

                    <div class="form-group form-observation">
                        <label class="form-label">Observación</label>
                        <textarea name="observation" id="observation" class="form-input" rows="3" maxlength="300"></textarea>
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeReservationModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-arrows-rotate"></i>Actualizar Reserva</button>
                </div>
            </form>
        </div>
    </div>


    <div id="customConfirm" class="confirm-overlay">
        <div class="confirm-card">
            <div class="confirm-icon">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 class="confirm-title">¿Cancelar reserva?</h3>
            <p class="confirm-text">
                Estás a punto de cancelar la reserva para la fecha <strong id="fieldName" style="color: #fff;"></strong>.
                Esta acción no se puede deshacer.
            </p>
            <div class="confirm-actions">
                <button type="button" class="btn-confirm-cancel" onclick="closeConfimation()">Cancelar</button>
                <button type="button" class="btn-confirm-delete" id="btn-confirm-cancellation">Cancelar reserva</button>
            </div>
        </div>
    </div>

    {{-- JS --}}
    @push('scripts')
        <script>
            window.RESERVATION_CONFIG = {
                fields_free: "{{ route('fields.free') }}"
            };
        </script>

        @vite(['resources/js/pages/views/reservation.js'])
    @endpush
@endsection
