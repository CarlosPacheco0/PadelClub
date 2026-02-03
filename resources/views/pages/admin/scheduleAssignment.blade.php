@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/schedule_assignment.css'])
@endpush

@section('content')
    <div class="page-container">

        <div class="header-section">
            <h2 class="page-title">Gestión de Horarios</h2>
            <p class="page-subtitle">Selecciona una fecha del calendario para asignar o eliminar disponibilidad.</p>
        </div>

        <div class="assignment-layout">

            <div class="left-column">
                <div class="calendar-card">
                    <div class="calendar-header">
                        <button id="prevBtn" onclick="prevMonth()" class="nav-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>

                        <h3 id="calendarTitle"></h3>

                        <div class="calendar-actions">
                            <button onclick="goToCurrentMonth()" class="btn-today">Hoy</button>
                            <button onclick="nextMonth()" class="nav-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </button>
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
            </div>

            <div class="right-column">

                <div class="card action-card">
                    <div class="card-header">
                        <div class="icon-box add">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Asignar Nuevos Horarios</h3>
                            <span class="card-desc">Marca los cupos que deseas habilitar</span>
                        </div>
                    </div>

                    <div class="schedule-container">
                        <div class="schedule-list free">
                            <div class="empty-state">
                                <span>👈 Selecciona una fecha primero</span>
                            </div>
                        </div>
                    </div>

                    <div class="action-footer">
                        <div class="summary-pill">
                            <span class="dot"></span> Seleccionados: <strong class="schedules-selecteds"
                                style="margin-left: 4px;">0</strong>
                        </div>
                        <button class="btn-primary" onclick="saveData()">
                            Guardar Asignación
                        </button>
                    </div>
                </div>

                <div class="card action-card">
                    <div class="card-header">
                        <div class="icon-box delete">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Horarios Registrados</h3>
                            <span class="card-desc">Marca los cupos para eliminarlos</span>
                        </div>
                    </div>

                    <div class="schedule-container">
                        <div class="schedule-list added">
                            <div class="empty-state">
                                <span>Sin horarios registrados</span>
                            </div>
                        </div>
                    </div>

                    <div class="action-footer">
                        <div style="flex-grow:1"></div> <button class="btn-danger btn-delete" onclick="deleteSchedules()">
                            Eliminar Seleccionados
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        const today = new Date();
        let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);

        // UI References
        const schedulesCounter = document.querySelector('.schedules-selecteds');
        const schedules_free = document.querySelector('.schedule-list.free');
        const schedules_added = document.querySelector('.schedule-list.added');

        // Rutas Laravel
        const URL_STORE = "{{ route('assignment.store') }}";
        const URL_GET_INFO = "{{ route('assignment.info') }}";
        const URL_DELETE = "{{ route('assignment.delete') }}";

        // ==========================================
        //  CONFIGURACIÓN DE PUNTOS EN CALENDARIO
        // ==========================================
        // TODO: Para que esto sea real, tu backend debería pasar un array de fechas ocupadas.
        // Ejemplo: const busyDates = @json($busyDates ?? []); 
        // Por ahora, usaré un array vacío para que no rompa.
        const busyDates = [];

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const calendarTitle = document.getElementById('calendarTitle');
            const calendarDays = document.getElementById('calendarDays');
            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ];

            calendarTitle.textContent = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = '';

            let firstDay = new Date(year, month, 1).getDay();
            firstDay = firstDay === 0 ? 6 : firstDay - 1;
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Espacios vacíos
            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += `<div style="cursor:default;"></div>`;
            }

            // Días
            for (let day = 1; day <= daysInMonth; day++) {
                // Formato YYYY-MM-DD para comparar con busyDates
                let dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                let isToday = (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) ?
                    'is-today' : '';
                let hasEvents = busyDates.includes(dateStr) ? 'has-events' : '';

                calendarDays.innerHTML +=
                    `<div class="calendar-day ${isToday} ${hasEvents}" onclick="selectDay(this)">${day}</div>`;
            }

            // Control de botón "Anterior"
            const prevBtn = document.getElementById('prevBtn');
            if (currentDate.getFullYear() === today.getFullYear() && currentDate.getMonth() === today.getMonth()) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }
        }

        function prevMonth() {
            const prev = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
            // Bloqueo simple para no ir al pasado
            if (prev.getFullYear() < today.getFullYear() || (prev.getFullYear() === today.getFullYear() && prev.getMonth() <
                    today.getMonth())) return;

            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        function goToCurrentMonth() {
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
            renderCalendar();
        }

        function selectDay(dayElement) {
            // Visual Toggle solo en UI (Opcional: permitir solo uno a la vez)
            document.querySelectorAll('.day-selected').forEach(d => d.classList.remove('day-selected'));
            dayElement.classList.add('day-selected');

            let date = currentDate.getFullYear() + '-' +
                String(currentDate.getMonth() + 1).padStart(2, '0') + '-' +
                dayElement.textContent.trim().padStart(2, '0');

            // Resetear UI mientras carga
            schedules_free.innerHTML = '<div class="empty-state"><span>Cargando...</span></div>';
            schedules_added.innerHTML = '<div class="empty-state"><span>Cargando...</span></div>';

            fetch(`${URL_GET_INFO}?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    const schedulesFree = Array.isArray(data.free) ? data.free : [];
                    const schedulesAdded = Array.isArray(data.added) ? data.added : [];

                    // 1. Renderizar Disponibles (Chips)
                    schedules_free.innerHTML = '';
                    if (schedulesFree.length > 0) {
                        schedulesFree.forEach(item => {
                            schedules_free.innerHTML += `
                            <label class="schedule-chip">
                                <input type="checkbox" data-id="${item.id}" onchange="updateSchedulesCount()">
                                <span class="chip-content">
                                    <span class="time">${item.start_time.slice(0,5)} - ${item.end_time.slice(0,5)}</span>
                                    <span class="check-icon">✔</span>
                                </span>
                            </label>`;
                        });
                    } else {
                        schedules_free.innerHTML =
                            `<div class="empty-state"><span>No hay cupos disponibles</span></div>`;
                    }

                    // 2. Renderizar Asignados (Chips Rojos)
                    schedules_added.innerHTML = '';
                    if (schedulesAdded.length > 0) {
                        schedulesAdded.forEach(item => {
                            schedules_added.innerHTML += `
                            <label class="schedule-chip danger-chip">
                                <input type="checkbox" data-id="${item.id}" onchange="countDeleteSchedule()">
                                <span class="chip-content">
                                    <span class="time">${item.schedule.start_time.slice(0,5)} - ${item.schedule.end_time.slice(0,5)}</span>
                                    <span class="check-icon">✕</span>
                                </span>
                            </label>`;
                        });
                    } else {
                        schedules_added.innerHTML = `<div class="empty-state"><span>Nada registrado hoy</span></div>`;
                    }

                    updateSchedulesCount(); // Resetear contador a 0
                    countDeleteSchedule(); // Resetear estado botón eliminar
                });
        }

        function updateSchedulesCount() {
            const count = document.querySelectorAll('.schedule-list.free input:checked').length;
            schedulesCounter.textContent = count;
        }

        function countDeleteSchedule() {
            const count = document.querySelectorAll('.schedule-list.added input:checked').length;
            const btn_delete = document.querySelector('.btn-delete');

            if (count > 0) {
                btn_delete.classList.add('active');
                btn_delete.textContent = `Eliminar (${count}) Seleccionados`;
            } else {
                btn_delete.classList.remove('active');
                btn_delete.textContent = `Eliminar Seleccionados`;
            }
        }

        function saveData() {
            const selectedDay = document.querySelector('.day-selected');
            if (!selectedDay) {
                alert('Selecciona una fecha primero');
                return;
            }

            const date =
                `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;
            const schedules = Array.from(document.querySelectorAll('.schedule-list.free input:checked')).map(input =>
                Number(input.dataset.id));

            if (schedules.length === 0) {
                alert('Selecciona al menos un horario para asignar');
                return;
            }

            fetch(URL_STORE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    dates: [date],
                    schedules: schedules
                }) // Se envía array de fechas para mantener compatibilidad
            }).then(r => r.json()).then(data => {
                alert('¡Guardado correctamente!');
                // Recargar el día actual para refrescar listas
                selectDay(selectedDay);
            }).catch(e => {
                console.error(e);
                alert('Error al guardar');
            });
        }

        function deleteSchedules() {
            const selectedDay = document.querySelector('.day-selected');
            const schedules = Array.from(document.querySelectorAll('.schedule-list.added input:checked')).map(input =>
                Number(input.dataset.id));

            if (!selectedDay || schedules.length === 0) return;

            const date =
                `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;

            if (!confirm('¿Estás seguro de eliminar estos horarios?')) return;

            fetch(URL_DELETE, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    date,
                    schedules
                })
            }).then(r => r.json()).then(data => {
                alert(data.message || 'Eliminado correctamente');
                selectDay(selectedDay);
            }).catch(e => alert('Error al eliminar'));
        }

        renderCalendar();
    </script>
@endsection
