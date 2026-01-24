/* =====================================================
   CONFIGURACIÓN DESDE BLADE
===================================================== */
const {
    schedulesUrl,
    reservationUrl,
    csrfToken
} = window.RESERVATION_CONFIG;


/* =====================================================
   ESTADO GLOBAL
===================================================== */
let fieldSelected = null;

const today = new Date();
let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);


/* =====================================================
   ELEMENTOS DOM
===================================================== */
const calendarDiv    = document.querySelector('.calendar-modal');
const datesContainer = document.querySelector('.date-container .dates');
const schedulesDiv   = document.getElementById('schedules');


/* =====================================================
   FECHAS RÁPIDAS
===================================================== */
function generateDates(days = 3) {
    datesContainer.innerHTML = '';

    for (let i = 0; i < days; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);

        const isoDate = date.toISOString().split('T')[0];
        const dayNum  = date.getDate();

        let title = '';
        if (i === 0) title = 'Hoy';
        else if (i === 1) title = 'Mañana';
        else if (i === 2) title = 'Pasado mañana';
        else title = date.toLocaleDateString('es-ES', { weekday: 'long' });

        datesContainer.innerHTML += `
            <div class="date-card" onclick="getSchedulesFree('${isoDate}')">
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
        <div class="more-dates">
            <div class="date-card">
                <div class="date-header">📅 Más fechas</div>
                <button class="date-action" onclick="renderCalendar()">
                    Ver fechas
                </button>
            </div>
        </div>
    `;
}

generateDates();


/* =====================================================
   CANCHAS
===================================================== */
function selectField(element) {

    // Si ya está seleccionada → deseleccionar
    if (element.classList.contains('field-active')) {
        element.classList.remove('field-active');
        fieldSelected = null;
        return;
    }

    // Quitar selección a las demás
    document.querySelectorAll('.field-card')
        .forEach(card => card.classList.remove('field-active'));

    // Seleccionar la nueva
    element.classList.add('field-active');
    fieldSelected = element.dataset.id;
}



/* =====================================================
   HORARIOS DISPONIBLES
===================================================== */
function getSchedulesFree(date) {

    if (!fieldSelected || !date) return;

    calendarDiv.style.display = 'none';
    schedulesDiv.innerHTML = '';

    fetch(schedulesUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            field_id: fieldSelected,
            date: date
        })
    })
    .then(res => res.json())
    .then(response => {

        const schedules = response.data ?? response;

        if (!schedules.length) {
            schedulesDiv.innerHTML = '<p>No hay horarios disponibles</p>';
            return;
        }

        schedules.forEach(schedule => {
            schedulesDiv.innerHTML += `
                <div class="schedule-card free">
                    <span class="schedule-hour">${schedule.hour}</span>

                    <form method="POST" action="${reservationUrl}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="field_id" value="${fieldSelected}">
                        <input type="hidden" name="date" value="${date}">
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

    calendarDiv.style.display = 'block';

    const year  = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const calendarTitle = document.getElementById('calendarTitle');
    const calendarDays  = document.getElementById('calendarDays');

    const monthNames = [
        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
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
            <div class="calendar-day" onclick="getSchedulesFree('${date}')">
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
    const prev = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);

    if (
        prev.getFullYear() < today.getFullYear() ||
        (prev.getFullYear() === today.getFullYear() && prev.getMonth() < today.getMonth())
    ) return;

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


/* =====================================================
   EXPONER FUNCIONES (onclick)
===================================================== */
window.selectField       = selectField;
window.getSchedulesFree  = getSchedulesFree;
window.renderCalendar    = renderCalendar;
window.prevMonth         = prevMonth;
window.nextMonth         = nextMonth;
window.goToCurrentMonth  = goToCurrentMonth;
