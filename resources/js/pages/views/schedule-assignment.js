/* ==========================================================================
   1. CONFIGURACIÓN Y ESTADO GLOBAL
   ========================================================================== */
// Leemos la configuración global que debe ser definida en el Blade.
// Asegúrate de que en tu vista Blade definas window.SCHEDULE_CONFIG con las claves exactas.
const { url_store, url_get_info, url_delete } = window.SCHEDULE_CONFIG;

const today = new Date();
let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);

let schedulesLoaded;
let dates_string = '';

// TODO: Para que esto sea real, tu backend debería pasar un array de fechas ocupadas.
const busyDates = [];

/* ==========================================================================
   2. REFERENCIAS UI (DOM)
   ========================================================================== */
const schedulesCounter = document.querySelector('.schedules-selecteds');
const schedules_free = document.querySelector('.schedule-list.free');
const schedules_added = document.querySelector('.schedule-list.added');

const btn_save = document.getElementById('save-btn');
const btn_delete = document.getElementById('delete-btn');

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
async function selectDay(dayElement) {
    // Seleccionar dias
    dayElement.classList.toggle('day-selected');

    dates_string = ''; // Setiamos como string vacio al realizar la seleccion

    document.querySelectorAll('.day-selected').forEach((day) => {
        dates_string +=
            currentDate.getFullYear() +
            '-' +
            String(currentDate.getMonth() + 1).padStart(2, '0') +
            '-' +
            day.textContent.trim().padStart(2, '0') +
            ',';
    });

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

    if (dates_string) schedulesLoaded = await loadSchedules();

    schedulesToRender(dates_string);
}

/* ==========================================================================
   6. CARGA Y RENDERIZADO DE HORARIOS
   ========================================================================== */

async function loadSchedules() {
    try {
        const res = await fetch(`${url_get_info}?date=${dates_string}`);
        const data = await res.json();

        // Almacenas en una variable
        const misHorarios = data;
        return misHorarios;
    } catch (error) {
        console.error(error);
        schedules_free.innerHTML =
            '<div class="empty-state"><span>Error al cargar</span></div>';
    }
}

function schedulesToRender(date) {
    const schedulesFree =
        schedulesLoaded && Array.isArray(schedulesLoaded.free)
            ? schedulesLoaded.free
            : [];
    const schedulesAdded =
        schedulesLoaded && Array.isArray(schedulesLoaded.added)
            ? schedulesLoaded.added
            : [];

    if (date) {
        // 1. Renderizar Disponibles (Chips)
        schedules_free.innerHTML = '';
        if (schedulesFree.length > 0) {
            btn_save.classList.remove('btn-disabled');

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
            btn_save.classList.add('btn-disabled');
            schedules_free.innerHTML = `<div class="empty-state"><span>No hay horarios disponibles</span></div>`;
        }

        // 2. Renderizar Asignados (Chips Rojos)
        schedules_added.innerHTML = '';
        if (schedulesAdded.length > 0) {
            btn_delete.classList.remove('btn-disabled');

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
            btn_delete.classList.add('btn-disabled');
            schedules_added.innerHTML = `<div class="empty-state"><span>Sin horarios asignados</span></div>`;
        }
    } else {

        document
                .querySelectorAll('.day-selected')
                .forEach((day) => day.classList.remove('day-selected'));

        schedules_free.innerHTML = `<div class="empty-state"><span>Selecciona una fecha primero</span></div>`;
        schedules_added.innerHTML = `<div class="empty-state"><span>Selecciona una fecha primero</span></div>`;
    }

    updateSchedulesCount(); // Resetear contador a 0
    countDeleteSchedule(); // Resetear estado botón eliminar
}

/* ==========================================================================
   7. LÓGICA DE UI (CONTADORES)
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
   8. OPERACIONES DE DATOS (API)
   ========================================================================== */
function saveData() {
    const selectedDay = document.querySelector('.day-selected');
    if (!selectedDay) {
        showToast('info', 'Atención', 'Selecciona una fecha primero.');
        return;
    }

    // const date = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;

    const schedules = Array.from(
        document.querySelectorAll('.schedule-list.free input:checked'),
    ).map((input) => Number(input.dataset.id));

    if (schedules.length === 0) {
        showToast(
            'info',
            'Atención',
            'Selecciona al menos un horario para asignar.',
        );
        return;
    }

    if (!url_store) {
        showToast(
            'error',
            'Error',
            'Error de sistema: Ruta de guardado no configurada.',
        );
        return;
    }

    dates_string = dates_string.slice(0, -1); // Eliminamos la utlima coma de la cadena
    const dates_array = dates_string.split(','); // Convertimos la fechas seleccionadas en array


    fetch(url_store, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        },
        body: JSON.stringify({
            dates: dates_array,
            schedules: schedules,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            showToast(data.status, data.title, data.message);

            // if (data.status == 'success') selectDay(selectedDay);
            schedulesLoaded = [];
            if (data.status == 'success') schedulesToRender('');
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

    if (!selectedDay || schedules.length === 0) {
        showToast(
            'info',
            'Atención',
            'Selecciona al menos un horario para eliminar.',
        );
        return;
    }

    const date = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${selectedDay.textContent.trim().padStart(2, '0')}`;

    Swal.fire({
        title: 'Eliminar asignación',
        text: '¿Está seguro de querer eliminar los horarios asignados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No',
        customClass: {
            popup: 'swal-popup',
            confirmButton: 'swal-confirm',
            cancelButton: 'swal-cancel',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            if (!url_delete) {
                showToast(
                    'error',
                    'Error',
                    'Error de sistema: Ruta de guardado no configurada.',
                );
                return;
            }

            fetch(url_delete, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
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
                    if (!data) return;

                    showToast(data.status, data.title, data.message);

                    // 2. IMPORTANTE: Recargar la vista para quitar los elementos borrados
                    if (data.status === 'success') schedulesToRender('');
                })
                .catch((e) => {
                    console.error('Error:', e);
                    showToast(
                        'error',
                        'Error',
                        'No fue posible eliminar el horario asignado.',
                    );
                });
        }
    });
}

/* ==========================================================================
   9. EXPOSICIÓN GLOBAL E INICIALIZACIÓN
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
