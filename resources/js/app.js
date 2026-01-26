import Swal from 'sweetalert2';

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
function setupDropdown(triggerId, dropdownId) {
    const trigger = document.getElementById(triggerId);
    const dropdown = document.getElementById(dropdownId);

    if (trigger) {
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();

            // 1️⃣ Cerrar todos los dropdowns abiertos
            document.querySelectorAll('.dropdown').forEach((d) => {
                if (d !== dropdown) {
                    d.style.display = 'none';
                }
            });

            // 2️⃣ Alternar el actual
            dropdown.style.display =
                dropdown.style.display === 'flex' ? 'none' : 'flex';
        });
    }

    // 3️⃣ Cerrar al hacer click fuera
    document.addEventListener('click', function () {
        if (dropdown) {
            dropdown.style.display = 'none';
        }
    });
}

// Inicializar
setupDropdown('userTrigger', 'userDropdown');
setupDropdown('scheduleTrigger', 'scheduleDropdown');
setupDropdown('reservationTrigger', 'reservationDropdown');
