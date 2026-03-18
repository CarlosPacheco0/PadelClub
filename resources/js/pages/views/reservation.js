/**
 * Gestión de Reservas - Panel Administrativo
 */

/* =====================================================
   CONFIGURACIÓN DESDE BLADE
===================================================== */
// Recuperamos las rutas generadas por Laravel (Blade) para crear y actualizar
const { fields_free } = window.RESERVATION_CONFIG;

// Variables globales de elementos del DOM
const inputDate = document.getElementById('res_date');
const selectFields = document.getElementById('res_field');
const btnDelete = document.getElementById('btn-confirm-cancellation');
let formToDelete = null;

/**
 * Abre el panel lateral de edición y carga la info inicial
 */
function openReservationModal(reservation) {
    // 1. Obtener info de campos dinámicos
    getInfo(reservation.field_id, reservation.date, reservation);

    // 2. Apertura del Modal (Side Panel)
    document.getElementById('editReservationOverlay').classList.add('active');
    document.getElementById('editReservationPanel').classList.add('active');

    // 3. Asignación de valores
    document.getElementById('res_id').value = reservation.id;
    document.getElementById('res_user').value = reservation.user.name;

    // Formateo de fecha
    const dateFormatted = reservation.date.split('T')[0];
    inputDate.value = dateFormatted;

    document.getElementById('res_status').value = reservation.status_reservation;
    document.getElementById('observation').value = textFormat(reservation.observation);

    // 4. Eventos de cambio (usamos onchange para evitar acumulaciones)
    inputDate.onchange = () => onFieldOrDateChange(reservation);
    selectFields.onchange = () => onFieldOrDateChange(reservation);
}

/**
 * Cierra el panel de edición
 */
function closeReservationModal() {
    document.getElementById('editReservationOverlay').classList.remove('active');
    document.getElementById('editReservationPanel').classList.remove('active');
}

/**
 * Consulta al servidor los horarios y canchas disponibles
 */
function getInfo(fieldId, date, reservation) {
    if (!fieldId || !date) {
        showToast('info', 'Atención', 'Datos incompletos para la búsqueda.');
        return;
    }

    fetch(`${fields_free}?field_id=${fieldId}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            let fields = data.fields;
            let schedules = data.schedules;

            // Renderizar Canchas
            selectFields.innerHTML = '';
            fields.forEach(field => {
                const name = textFormat(field.name);
                selectFields.innerHTML += `<option value="${field.id}">${name}</option>`;
            });
            selectFields.value = fieldId;

            // Renderizar Horarios
            const selectSchedules = document.getElementById('res_schedule');
            selectSchedules.innerHTML = '<option value="">-- Seleccione una opción --</option>';

            // Si es la fecha original, asegurar que el horario actual de la reserva aparezca
            if (date == reservation.date && !schedules.some(s => s.id === reservation.schedule_id)) {
                schedules.push({
                    id: reservation.schedule_id,
                    hour: `${reservation.schedule.start_time} - ${reservation.schedule.end_time}`
                });
                schedules.sort((a, b) => a.id - b.id);
            }

            schedules.forEach(s => {
                selectSchedules.innerHTML += `<option value="${s.id}">${s.hour}</option>`;
            });

            if (date == reservation.date) {
                selectSchedules.value = reservation.schedule_id;
            }
        })
        .catch(err => console.error("Error fetching info:", err));
}

function onFieldOrDateChange(reservation) {
    const fId = selectFields.value;
    const d = inputDate.value;
    if (fId && d) getInfo(fId, d, reservation);
}

/**
 * Lógica de Confirmación de Cancelación
 */
function confirmCancellation(event, fieldName) {
    event.preventDefault();
    formToDelete = event.currentTarget;
    document.getElementById('fieldName').textContent = fieldName;
    document.getElementById('customConfirm').classList.add('active');
}

function closeConfimation() {
    document.getElementById('customConfirm').classList.remove('active');
    formToDelete = null;
}

// Evento para el botón de aceptar en el modal de confirmación
if (btnDelete) {
    btnDelete.onclick = () => {
        if (formToDelete) formToDelete.submit();
    };
}

/**
 * Función auxiliar para limpiar texto (si no la tienes definida en otro lado)
 */
function textFormat(text) {
    return text ? text.trim() : '';
}

window.openReservationModal = openReservationModal;
window.closeReservationModal = closeReservationModal;


window.confirmCancellation = confirmCancellation;
window.closeConfimation = closeConfimation;