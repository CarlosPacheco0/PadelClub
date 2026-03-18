import Swal from 'sweetalert2';

window.Swal = Swal; // 👈 hacerlo global

/**
 * Muestra una alerta Toast
 * @param {string} type - 'success', 'error', o 'info'
 * @param {string} title - Título en negrita
 * @param {string} message - Mensaje descriptivo
 */

// Alertas desde el js
window.showToast = function (type, title, message) {
    const container = document.getElementById('toast-container');

    // Iconos según el tipo
    const icons = {
        success: 'fas fa-check',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
    };

    // Crear elemento
    const toast = document.createElement('div');
    toast.className = `toast-card toast-${type}`;

    toast.innerHTML = `
        <div class="toast-icon">
            <i class="${icons[type] || icons.info}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Animación de entrada (pequeño delay para que CSS detecte el cambio)
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // Auto eliminar después de 4 segundos
    setTimeout(() => {
        toast.classList.remove('show');
        // Esperar a que termine la transición CSS para remover del DOM
        setTimeout(() => toast.remove(), 500);
    }, 4000);
};

// Funcionalidad el submenu
document.addEventListener('click', (e) => {
    document.querySelectorAll('.nav-dropdown').forEach((drop) => {
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

// Formatea el texto: Capitaliza la primera letra y pone el resto en minúscula
function textFormat(text) {
    if (!text || typeof text !== 'string') return '';
    return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}


// Inicializar
window.textFormat = textFormat;
