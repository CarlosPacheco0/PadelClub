/* =====================================================
   CONFIGURACIÓN DESDE BLADE
===================================================== */
const { schedulesUrl, reservationUrl, csrfToken } = window.RESERVATION_CONFIG;

/* =====================================================
   ESTADO GLOBAL
===================================================== */
let fieldSelected = null;
let dateSelected = null;

const today = new Date();
let currentDate = new Date(today.getFullYear(), today.getMonth(), 1); 

/* =====================================================
   ELEMENTOS DOM
===================================================== */
const calendarDiv = document.querySelector('.calendar-modal');
const datesContainer = document.querySelector('.date-container .dates');
const schedulesDiv = document.querySelector('#schedules div');

/* ==== FECHAS RÁPIDAS ==== */
function generateDates(days = 3) {
    datesContainer.innerHTML = '';

    for (let i = 0; i < days; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);

        const isoDate = date.toISOString().split('T')[0];
        const dayNum = date.getDate();

        let title = '';
        if (i === 0) title = 'Hoy';
        else if (i === 1) title = 'Mañana';
        else if (i === 2) title = 'Pasado mañana';
        else title = date.toLocaleDateString('es-ES', { weekday: 'long' });

        datesContainer.innerHTML += `
            <div class="date-card" data-date="${isoDate}" onclick="selectDate(this)">
                <div class="date-header">
                    <span class="icon">📅</span>
                    <span class="title">${title}</span>
                </div>
                <div class="date-value">
                    <span class="day">${dayNum}</span>
                </div>
                <button class="date-action">
                    <span class="action">Seleccionar fecha</span>
                </button>
            </div>
        `;
    }

    datesContainer.innerHTML += `
        <div class="more-dates" onclick="renderCalendar()">
            <div class="date-card">
                <div class="date-header">📅 Más fechas</div>
                <button class="date-action">
                    Ver fechas
                </button>
            </div>
        </div>
    `;
}

generateDates();

/* ==== SELECCIONAR CANCHA ==== */
function selectField(element) {
    // Si ya está seleccionada → deseleccionar
    if (element.classList.contains('field-selected')) {
        element.classList.remove('field-selected');
        fieldSelected = null;
        return;
    }

    // Quitar selección a las demás
    document
        .querySelectorAll('.field-card')
        .forEach((card) => card.classList.remove('field-selected'));

    // Seleccionar la nueva
    element.classList.add('field-selected');
    fieldSelected = element.dataset.id;

    if (dateSelected) getSchedulesFree();
}

/* ==== SELECCIONAR FECHA ==== */
function selectDate(element) {
    // Cerrar calendario al seleccionar fecha
    closeCalendar();

    // Si ya está seleccionada → deseleccionar
    if (element.classList.contains('date-card-active')) {
        element.classList.remove('date-card-active');
        dateSelected = null;
        return;
    }

    // Quitar selección a las demás
    document
        .querySelectorAll('.date-card-active')
        .forEach((card) => card.classList.remove('date-card-active'));

    // Seleccionar la nueva
    element.classList.add('date-card-active');
    dateSelected = element.dataset.date;

    // Obtner los horarios disponibles
    getSchedulesFree();
}

/* =====================================================
   HORARIOS DISPONIBLES
===================================================== */
function getSchedulesFree() {
    if (!fieldSelected || !dateSelected) {
        showToast('error', 'Error', 'Debe seleccionar una cancha y fecha deseada');
        return;
    }

    calendarDiv.style.display = 'none';
    schedulesDiv.innerHTML = '';

    fetch(schedulesUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            field_id: fieldSelected,
            date: dateSelected,
        }),
    })
        .then((res) => res.json())
        .then((response) => {
            const schedules = response.data ?? response;

            console.log(response)

            // Realizar scroll automatico al final.
            setTimeout(() => {
                window.scrollTo({
                    top: document.documentElement.scrollHeight,
                    behavior: 'smooth',
                });
            }, 100);

            if (!schedules.length) {
                schedulesDiv.classList.add('schedules-empty');
                schedulesDiv.innerHTML = '<p>No hay horarios disponibles</p>';
                return;
            }

            schedules.forEach((schedule) => {
                schedulesDiv.classList.remove('schedules-empty');

                schedulesDiv.innerHTML += `
                <div class="schedule-card">
                    <span class="schedule-hour">${schedule.hour}</span>

                    <form method="POST" action="${reservationUrl}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="field_id" value="${fieldSelected}">
                        <input type="hidden" name="date" value="${dateSelected}">
                        <input type="hidden" name="schedule_id" value="${schedule.id}">

                        <button type="submit" class="btn-reservar">
                            Reservar
                        </button>
                    </form>
                </div>
            `;
            });
        })
        .catch(() => {
            schedulesDiv.innerHTML = '<p>Error al cargar horarios</p>';
        });
}

/* =====================================================
   CALENDARIO
===================================================== */
function renderCalendar() {
    calendarDiv.style.display = 'flex';

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

    for (let i = 0; i < firstDay; i++) {
        calendarDays.innerHTML += `<div class="inactive"></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const m = String(month + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        const date = `${year}-${m}-${d}`;

        calendarDays.innerHTML += `
            <div class="calendar-day" data-date="${date}" onclick="selectDate(this)">
                ${day}
            </div>
        `;
    }

    const prevBtn = document.getElementById('prevBtn');
    prevBtn.disabled =
        currentDate.getFullYear() === today.getFullYear() &&
        currentDate.getMonth() === today.getMonth();
}

/* =====================================================
   NAVEGACIÓN CALENDARIO
===================================================== */
function prevMonth() {
    const prev = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth() - 1,
        1,
    );

    if (
        prev.getFullYear() < today.getFullYear() ||
        (prev.getFullYear() === today.getFullYear() &&
            prev.getMonth() < today.getMonth())
    )
        return;

    currentDate = prev;
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

/* ==== CERRAR CALENDARIO ==== */
function closeCalendar() {
    calendarDiv.style.display = 'none';
}

/* =====================================================
   EXPONER FUNCIONES (onclick)
===================================================== */
window.selectField = selectField;
window.selectDate = selectDate;
window.getSchedulesFree = getSchedulesFree;
window.renderCalendar = renderCalendar;
window.prevMonth = prevMonth;
window.nextMonth = nextMonth;
window.goToCurrentMonth = goToCurrentMonth;

window.closeCalendar = closeCalendar;
