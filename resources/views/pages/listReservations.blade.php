@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/reservations.css'])
@endpush

@section('content')

    <h2 class="content-title">Mis reservas</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Cancha</th>
                <th>Fecha de reserva</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ $reservation->field->name }}</td>
                    <td>{{ $reservation->date->format('d-m-Y') }}</td>
                    <td>
                        {{ $reservation->schedule->start_time->format('H:i') . ' - ' . $reservation->schedule->end_time->format('H:i') }}
                    </td>
                    <td>{{ $reservation->status_reservation }}</td>
                    {{-- <td class="actions-table">
                        <button class="btn btn-edit" onclick='openReservationModal({{ $reservation }})'>
                            Editar
                        </button>
                        
                        <form action="{{ route('reservation.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="id" value="{{ $reservation->id }}">
                            <button class="btn btn-delete" type="submit">Eliminar</button>
                        </form>
                    </td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-data">No hay registros para mostrar</td>
                </tr>
            @endforelse

        </tbody>
    </table>


    <!-- Modal Editar Reserva -->
    <div id="editReservationModal" class="modal">
        <div class="modal-content">

            <h2 id="modalTitle">Editar Reserva</h2>

            <form action="{{ route('reservation.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ID oculto -->
                <input type="hidden" name="reservation_id" id="res_id">

                <div>
                    <label>Usuario</label>
                    <input type="text" id="res_user" disabled>
                </div>

                <div>
                    <label>Cancha</label>
                    <select name="field_id" id="res_field" required></select>
                </div>

                <div>
                    <label>Fecha</label>
                    <input type="date" name="date" id="res_date" required>
                </div>

                <div>
                    <label>Horario</label>
                    <select name="schedule_id" id="res_schedule" required></select>
                </div>

                <div>
                    <label>Estado</label>
                    <select name="status" id="res_status" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="completada">Completada</option>
                    </select>
                </div>

                <button class="btn btn-cancel" onclick="closeReservationModal()">Cancelar</button>
                <button type="submit" class="btn btn-save">Actualizar Reserva</button>
            </form>
        </div>
    </div>

    {{-- JS --}}
    <script>
        const inputDate = document.getElementById('res_date');
        const selectFields = document.getElementById('res_field');

        function openReservationModal(reservation) {

            // Obtener info de campos dinamicos
            getInfo(reservation.field.id, reservation.date, reservation);

            // === Asignamiento de valores ===
            document.getElementById('editReservationModal').style.display = 'block';

            // ID de la reserva y nombre de usuario
            document.getElementById('res_id').value = reservation.id;
            document.getElementById('res_user').value = reservation.client.name;


            // Fecha de la reserva
            const isoDate = reservation.date;
            const dateFormatted = isoDate.split('T')[0];

            inputDate.value = dateFormatted;

            // Estado de la reserva
            document.getElementById('res_status').value = reservation.status_reservation;


            // Evento al cambiar de cancha o fecha
            inputDate.addEventListener('change', () => {
                onFieldOrDateChange(reservation)
            });

            selectFields.addEventListener('change', () => {
                onFieldOrDateChange(reservation)
            });


        }

        function closeReservationModal() {
            document.getElementById('editReservationModal').style.display = 'none';
        }

        function getInfo(field_id, date, reservation) {

            const URL_INFO = "{{ route('fieldsFree') }}";

            fetch(`${URL_INFO}?field_id=${field_id}&date=${date}`)
                .then(res => res.json())
                .then(data => {

                    let fields = data.fields;
                    let schedules = data.schedules;

                    // Información de las canchas
                    selectFields.innerHTML = '';

                    fields.forEach(field => {

                        selectFields.innerHTML +=
                            `<option value="${field.id}">${field.name}</option>`;

                    })

                    // Asignar valor inicial
                    selectFields.value = field_id;


                    // Información de los horarios
                    const selectSchedules = document.getElementById('res_schedule');
                    selectSchedules.innerHTML = `<option value="">-- Seleccione un horario --</option>`;

                    // Validamos que el Horarios actual de la reserva
                    // no exista para agregarlos manualmente
                    if (!schedules.some(s => s.id === reservation.schedule_id)) {

                        // Agregamos el horario actual al array
                        schedules = [
                            ...schedules,
                            {
                                id: reservation.schedule_id,
                                hour: `${reservation.schedule.start_time} - ${reservation.schedule.end_time}`
                            }
                        ];

                        // Ordenarmos de manera ASC por el ID
                        schedules.sort((a, b) => a.id - b.id);

                    }

                    schedules.forEach(schedule => {

                        selectSchedules.innerHTML +=
                            `<option value="${schedule.id}">
                                ${schedule.hour}
                            </option>`;

                    })

                    if (date == reservation.date) {
                        // Definimos el valor por defecto
                        document.getElementById('res_schedule').value = reservation.schedule_id;
                    }

                })

        }

        function onFieldOrDateChange(reservation) {
            const fieldId = selectFields.value;
            const date = inputDate.value;

            if (!fieldId || !date) return;

            getInfo(fieldId, date, reservation);
        }
    </script>
@endsection
