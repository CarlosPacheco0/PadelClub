import Swal from 'sweetalert2';

import './pages/views/generate-reservation.js';
import './pages/views/layout.js';

window.Swal = Swal; // 👈 hacerlo global

// Alertas desde el js
window.showAlert = function (type, message) {
    const alert = document.getElementById('js-alert');
    if (!alert) return;

    alert.className = `alert alert-${type} alert-fixed`;
    alert.textContent = message;

    alert.classList.remove('d-none');

    setTimeout(() => {
        alert.classList.add('d-none');
    }, 3000);
};

// Funcionalidad el submenu
document.addEventListener('click', e => {
    document.querySelectorAll('.nav-dropdown').forEach(drop => {
        const trigger = drop.querySelector('.nav-trigger');

        if (trigger.contains(e.target)) {
            drop.classList.toggle('open');
        } else {
            drop.classList.remove('open');
        }
    });
});

// Selección de opciones del menu
const menuItems = document.querySelectorAll('.menu-item');
menuItems.forEach((item) => {
    item.onclick = () => menuSelected(item);
});

function menuSelected(item) {
    menuItems.forEach((item) => {
        item.classList.remove('active');
    });

    item.classList.add('active');
}

// Inicializar
// setupDropdown('userTrigger', 'userDropdown');
// setupDropdown('scheduleTrigger', 'scheduleDropdown');
// setupDropdown('reservationTrigger', 'reservationDropdown');
