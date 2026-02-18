/* ==========================================================================
   1. CONFIGURACIÓN Y ESTADO GLOBAL
   ========================================================================== */
// Leemos la configuración global que debe ser definida en el Blade.
// Asegúrate de que en tu vista Blade definas window.SCHEDULE_CONFIG con las claves exactas.
const { url_store, url_get_info, url_delete } = window.SCHEDULE_CONFIG;

const today = new Date();
let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);

// TODO: Para que esto sea real, tu backend debería pasar un array de fechas ocupadas.
const busyDates = [];

/* ==========================================================================
   2. REFERENCIAS UI (DOM)
   ========================================================================== */
const schedulesCounter = document.querySelector('.schedules-selecteds');
const schedules_free = document.querySelector('.schedule-list.free');
const schedules_added = document.querySelector('.schedule-list.added');

/* ==========================================================================
   3. NAVEGACIÓN DEL CALENDARIO
   ========================================================================== */
function prevMonth() {
    const prev = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth() - 1,
        1,
    );
    // Bloqueo simple para no ir al pasado
    if (
        prev.getFullYear() < today.getFullYear() ||
        (prev.getFullYear() === today.getFullYear() &&
            prev.getMonth() < today.getMonth())
    )
        return;

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

/* ==========================================================================
   4. RENDERIZADO PRINCIPAL
   ========================================================================== */
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const calendarTitle = document.getElementById('calendarTitle');
    const calendarDays = document.getElementById('calendarDays');
    const monthNames = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre',
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

        let isToday =
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
                ? 'is-today'
                : '';
        let hasEvents = busyDates.includes(dateStr) ? 'has-events' : '';

        calendarDays.innerHTML += `<div class="calendar-day ${isToday} ${hasEvents}" onclick="selectDay(this)">${day}</div>`;
    }

    // Control de botón "Anterior"
    const prevBtn = document.getElementById('prevBtn');
    if (
        currentDate.getFullYear() === today.getFullYear() &&
        currentDate.getMonth() === today.getMonth()
    ) {
        prevBtn.disabled = true;
    } else {
        prevBtn.disabled = false;
    }
}

/* ==========================================================================
   5. INTERACCIÓN Y SELECCIÓN DE DÍAS
   ========================================================================== */
function selectDay(dayElement) {
    // Visual Toggle solo en UI
    document
        .querySelectorAll('.day-selected')
        .forEach((d) => d.classList.remove('day-selected'));
    dayElement.classList.add('day-selected');

    let date =
        currentDate.getFullYear() +
        '-' +
        String(currentDate.getMonth() + 1).padStart(2, '0') +
        '-' +
        dayElement.textContent.trim().padStart(2, '0');

    // Resetear UI mientras carga
    schedules_free.innerHTML =
        '<div class="empty-state"><span>Cargando...</span></div>';
    schedules_added.innerHTML =
        '<div class="empty-state"><span>Cargando...</span></div>';

    // Validación extra para evitar errores de fetch undefined
    if (!url_get_info) {
        showToast('error', 'Error', 'Error de sistema: Ruta no definida.');
        return;
    }

    fetch(`${url_get_info}?date=${date}`)
        .then((res) => res.json())
        .then((data) => {
            const schedulesFree = Array.isArray(data.free) ? data.free : [];
            const schedulesAdded = Array.isArray(data.added) ? data.added : [];

            // 1. Renderizar Disponibles (Chips)
            schedules_free.innerHTML = '';
            if (schedulesFree.length > 0) {
                schedulesFree.forEach((item) => {
                    schedules_free.innerHTML += `
                            <label class="schedule-chip">
                                <input type="checkbox" data-id="${item.id}" onchange="updateSchedulesCount()">
                                <span class="chip-content">
                                    <span class="time">${item.start_time.slice(0, 5)} - ${item.end_time.slice(0, 5)}</span>
                                    <span class="check-icon">✔</span>
                                </span>
                            </label>`;
                });
            } else {
                schedules_free.innerHTML = `<div class="empty-state"><span>No hay cupos disponibles</span></div>`;
            }

            // 2. Renderizar Asignados (Chips Rojos)
            schedules_added.innerHTML = '';
            if (schedulesAdded.length > 0) {
                schedulesAdded.forEach((item) => {
                    schedules_added.innerHTML += `
                            <label class="schedule-chip danger-chip">
                                <input type="checkbox" data-id="${item.id}" onchange="countDeleteSchedule()">
                                <span class="chip-content">
                                    <span class="time">${item.schedule.start_time.slice(0, 5)} - ${item.schedule.end_time.slice(0, 5)}</span>
                                    <span class="check-icon">✕</span>
                                </span>
                            </label>`;
                });
            } else {
                schedules_added.innerHTML = `<div class="empty-state"><span>Nada registrado hoy</span></div>`;
            }

            updateSchedulesCount(); // Resetear contador a 0
            countDeleteSchedule(); // Resetear estado botón eliminar
        })
        .catch((error) => {
            console.error(error);
            schedules_free.innerHTML =
                '<div class="empty-state"><span>Error al cargar</span></div>';
            schedules_added.innerHTML = '';
        });
}

/* ==========================================================================
   6. LÓGICA DE UI (CONTADORES)
   ========================================================================== */
function updateSchedulesCount() {
    const count = document.querySelectorAll(
        '.schedule-list.free input:checked',
    ).length;
    schedulesCounter.textContent = count;
}

function countDeleteSchedule() {
    const count = document.querySelectorAll(
        '.schedule-list.added input:checked',
    ).length;
    const btn_delete = document.querySelector('.btn-delete');

    if (count > 0) {
        btn_delete.classList.add('active');
        btn_delete.textContent = `Eliminar (${count}) Seleccionados`;
    } else {
        btn_delete.classList.remove('active');
        btn_delete.textContent = `Eliminar Seleccionados`;
    }
}

/* ==========================================================================
   7. OPERACIONES DE DATOS (API)
   ========================================================================== */
function saveData() {
    const selectedDay = document.querySelector('.day-selected');
    if (!selectedDay) {
        alert('Selecciona una fecha primero');
        return;
    }

    const date = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;
    const schedules = Array.from(
        document.querySelectorAll('.schedule-list.free input:checked'),
    ).map((input) => Number(input.dataset.id));

    if (schedules.length === 0) {
        alert('Selecciona al menos un horario para asignar');
        return;
    }

    if (!url_store) {
        console.error('Configuración faltante: url_store');
        alert('Error de sistema: Ruta de guardado no configurada.');
        return;
    }

    fetch(url_store, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        },
        body: JSON.stringify({
            dates: [date],
            schedules: schedules,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            selectDay(selectedDay);

            if (!data) return;
            showToast(data.status, data.title, data.message);
        })
        .catch((e) => {
            console.error('Error:', e);
            showToast('error', 'Error', 'No fue posible asignar los horarios.');
        });
}

function deleteSchedules() {
    const selectedDay = document.querySelector('.day-selected');
    const schedules = Array.from(
        document.querySelectorAll('.schedule-list.added input:checked'),
    ).map((input) => Number(input.dataset.id));

    if (!selectedDay || schedules.length === 0) return;

    const date = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;

    if (!confirm('¿Estás seguro de eliminar estos horarios?')) return;

    if (!url_delete) {
        console.error('Configuración faltante: url_delete');
        alert('Error de sistema: Ruta de eliminación no configurada.');
        return;
    }

    fetch(url_delete, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        },
        body: JSON.stringify({
            date,
            schedules,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            alert(data.message || 'Eliminado correctamente');
            selectDay(selectedDay);
        })
        .catch((e) => {
            console.log('Error:', e);
        });
}

/* ==========================================================================
   8. EXPOSICIÓN GLOBAL E INICIALIZACIÓN
   ========================================================================== */
window.saveData = saveData;
window.deleteSchedules = deleteSchedules;

window.prevMonth = prevMonth;
window.nextMonth = nextMonth;
window.goToCurrentMonth = goToCurrentMonth;

// Función necesaria porque se llama desde el HTML generado dinámicamente
window.selectDay = selectDay;
window.updateSchedulesCount = updateSchedulesCount;
window.countDeleteSchedule = countDeleteSchedule;

// Iniciar
renderCalendar();
