@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/schedule_assignment.css'])
@endpush

@section('content')
    <div class="page">

        <h2 class="page-title">Asignar horarios por fechas</h2>

        <div class="assignment-layout">

            <!-- CALENDARIO -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <button id="prevBtn" onclick="prevMonth()" class="button-action">‹</button>

                    <h3 id="calendarTitle"></h3>

                    <div class="calendar-actions">
                        <button onclick="goToCurrentMonth()" class="btn-today">Hoy</button>
                        <button onclick="nextMonth()" class="button-action">›</button>
                    </div>
                </div>


                <div class="calendar-weekdays">
                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mié</div>
                    <div>Jue</div>
                    <div>Vie</div>
                    <div>Sáb</div>
                    <div>Dom</div>
                </div>

                <div class="calendar-days" id="calendarDays"></div>
            </div>


            <!-- HORARIOS DISPONIBLES -->
            <div class="card schedules-card">
                <h3 class="card-title">⏰ Horarios disponibles</h3>

                <div class="schedule-list free">
                    <p class="empty-data">No hay una fecha seleccionada</p>
                </div>

                <div class="summary">
                    {{-- <p>📅 Fechas seleccionadas: <strong class="dates-selecteds">0</strong></p> --}}
                    <p>⏰ Horarios seleccionados: <strong class="schedules-selecteds">0</strong></p>
                </div>

                <button class="assign-btn" onclick="saveData()">
                    Asignar horarios
                </button>
            </div>

            {{-- HORARIOS ASIGNADOS  --}}
            <div class="card schedules-card">
                <h3 class="card-title">⏰ Horarios asignados</h3>

                <div class="schedule-list added">
                    <p class="empty-data">No hay horarios registrados</p>
                </div>

                {{-- <div class="summary">
                    <p>📅 Fechas seleccionadas: <strong class="dates-selecteds">0</strong></p>
                    <p>⏰ Horarios seleccionados: <strong class="schedules-selecteds">0</strong></p>
                </div> --}}

                <button class="btn btn-delete" onclick="deleteSchedules()">
                    Eliminar horario
                </button>
            </div>

        </div>

    </div>

    {{-- JS  --}}
    <script>
        const today = new Date(); // fecha real
        let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);

        // Almacena la cantidad de horarios seleccioados
        const schedulesCounter = document.querySelector('.schedules-selecteds');

        // Almacena cantidad de fechas seleccionadas
        // const datesCounter = document.querySelector('.dates-selecteds');


        // Lista de horarios disponibles
        const schedules_free = document.querySelector('.schedule-list');

        // Lista horarios asignados
        const schedules_added = document.querySelector('.schedule-list.added');



        // URL paara almacenar los horarios seleccionados por fecha
        const URL_STORE = "{{ route('assignment.store') }}";

        // URL para obtener la info del dia seleccionado
        const URL_GET_INFO = "{{ route('assignment.info') }}";

        // URL para eliminar horarios asignados
        const URL_DELETE = "{{ route('assignment.delete') }}";


        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const calendarTitle = document.getElementById('calendarTitle');
            const calendarDays = document.getElementById('calendarDays');

            const monthNames = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            calendarTitle.textContent = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = '';

            // Primer día del mes (lunes = 0)
            let firstDay = new Date(year, month, 1).getDay();
            firstDay = firstDay === 0 ? 6 : firstDay - 1;

            // Total días del mes
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Espacios vacíos antes del día 1
            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += `<div class="inactive"></div>`;
            }

            // Días del mes
            for (let day = 1; day <= daysInMonth; day++) {
                calendarDays.innerHTML += `
                <div class="calendar-day" onclick="selectDay(this)">${day}</div>
            `;
            }

            const prevBtn = document.getElementById('prevBtn');

            // Deshabilitar botón si estamos en mes actual
            if (
                currentDate.getFullYear() === today.getFullYear() &&
                currentDate.getMonth() === today.getMonth()
            ) {
                prevBtn.disabled = true;
                prevBtn.style.opacity = 0.4;
                prevBtn.style.cursor = 'not-allowed';
            } else {
                prevBtn.disabled = false;
                prevBtn.style.opacity = 1;
                prevBtn.style.cursor = 'pointer';
            }
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        // Bloquear ir a meses anteriores al actual
        function prevMonth() {
            const prev = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth() - 1,
                1
            );

            // No permitir ir antes del mes actual
            if (
                prev.getFullYear() < today.getFullYear() ||
                (
                    prev.getFullYear() === today.getFullYear() &&
                    prev.getMonth() < today.getMonth()
                )
            ) {
                return; // bloquea
            }

            currentDate = prev;
            renderCalendar();
        }

        // Ir al mes actual
        function goToCurrentMonth() {
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
            renderCalendar();
        }

        // Seleccionar fecha
        function selectDay(day) {

            day.classList.toggle('day-selected');

            // updateDatesCount(); // Actualizar contador de fechas seleccionadas

            let date = currentDate.getFullYear() + '-' +
                String(currentDate.getMonth() + 1).padStart(2, '0') + '-' +
                day.textContent.trim().padStart(2, '0');

            // Obtener info del dia seleccionado (Horarios asignados y disponibles)
            fetch(`${URL_GET_INFO}?date=${date}`)
                .then(res => res.json())
                .then(data => {

                    const schedulesFree = Array.isArray(data.free) ? data.free : []; // Horarios disponibles
                    const schedulesAdded = Array.isArray(data.added) ? data.added :
                []; // Horarios añadidos a una fecha 

                    // Renderizar HTML de Horarios disponibles
                    schedules_free.innerHTML = '';

                    if (schedulesFree.length > 0) {

                        schedules_free.style.display = 'grid';

                        schedulesFree.forEach(item => {
                            schedules_free.innerHTML += `
                            <label class="schedule-item">
                                <input type="checkbox" data-id="${item.id}" onchange="updateSchedulesCount()">
                                ${item.start_time} - ${item.end_time}
                            </label>
                        `;
                        });

                    } else {
                        schedules_free.style.display = 'block';
                        schedules_free.innerHTML += `
                            <p class="empty-data">No hay horarios disponibles</p>
                        `;
                    }

                    // Renderizar horarios asignados a una fecha
                    schedules_added.innerHTML = '';

                    if (schedulesAdded.length > 0) {

                        schedules_added.style.display = 'grid';

                        schedulesAdded.forEach(item => {
                            schedules_added.innerHTML += `
                            <label class="schedule-item">
                                <input type="checkbox" data-id="${item.id}" onchange="countDeleteSchedule()">
                                ${item.schedule.start_time} - ${item.schedule.end_time}
                            </label>
                        `;
                        });

                    } else {
                        schedules_added.style.display = 'block';
                        schedules_added.innerHTML += `
                            <p class="empty-data">No hay horarios registrados</p>
                        `;
                    }



                })


        }

        // Contador de horarios seleccionados
        function updateSchedulesCount() {
            let count = 0;

            const scheduleCheckboxes = document.querySelectorAll('.schedule-item input');

            scheduleCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    count++;
                }
            });

            schedulesCounter.textContent = count;

        }

        // // Contador de fechas seleccionadas
        // function updateDatesCount() {
        //     let count = 0;

        //     const datesBox = document.querySelectorAll('.calendar-day');

        //     datesBox.forEach(date => {
        //         if (date.classList.contains('day-selected')) {
        //             count++;
        //         }
        //     });

        //     datesCounter.textContent = count;
        // }

        // Contador para eliminar horarios
        function countDeleteSchedule() {
            let count = 0;

            const scheduleCheckboxes = schedules_added.querySelectorAll('.schedule-item input');
            const btn_delete = document.querySelector('.btn-delete');

            scheduleCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    count++;
                }
            });

            // Si se selecciona al menos uno, se habilita el botón de eliminar
            if (count > 0) {
                btn_delete.style.pointerEvents = 'all';
                btn_delete.style.opacity = '1';
            }

        }

        renderCalendar();

        // Asignar horarios seleccionados
        function saveData() {

            // Formar array de fecha seleccionada (YYYY-MM-DD)
            const dates = Array.from(document.querySelectorAll('.day-selected')).map(day => {
                const dayNumber = day.textContent.trim().padStart(2, '0');
                const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                const year = currentDate.getFullYear();

                return `${year}-${month}-${dayNumber}`;
            });

            // HORARIOS seleccionados
            const schedules = Array.from(
                document.querySelectorAll('.schedule-item input:checked')
            ).map(input => Number(input.dataset.id));

            if (dates.length === 0 || schedules.length === 0) {
                alert('Debes seleccionar al menos una fecha y un horario');
                return;
            }

            fetch(URL_STORE, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        dates: dates,
                        schedules: schedules
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    alert('Horarios asignados correctamente');

                    // opcional: limpiar UI
                    // location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('Error al guardar');
                });

        }

        // Eliminar horarios asignados
        function deleteSchedules() {

            // Formar array de los horarios seleccionados.
            const schedules = Array.from(
                schedules_added.querySelectorAll('.schedule-item input:checked')
            ).map(input => Number(input.dataset.id));


            const selectedDay = document.querySelector('.day-selected');

            const dayNumber = selectedDay.textContent.trim().padStart(2, '0');
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const year = currentDate.getFullYear();

            const date = `${year}-${month}-${dayNumber}`;


            if (!selectedDay || schedules.length === 0) {
                alert('Debes seleccionar al menos una fecha y un horario');
                return;
            }


            // Enviar petición de eliminacion
            fetch(URL_DELETE, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        date: date,
                        schedules: schedules
                    })
                })
                .then(response => response.json())
                .then(data => {

                    alert(data.message);

                    // opcional: limpiar UI
                    // location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('Error al guardar');
                });

        }
    </script>
@endsection
