/* =====================================================
   CONFIGURACIÓN DESDE BLADE
===================================================== */
// Recuperamos las rutas generadas por Laravel (Blade) para crear y actualizar
const { url_store, url_update } = window.FIELDS_CONFIG;

/* =====================================================
   SELECCIÓN DE ELEMENTOS DEL DOM
===================================================== */
// Elementos generales del modal de formulario
const modal = document.getElementById('fieldModal');
const overlay = document.getElementById('modalOverlay');
const title = document.getElementById('modalTitle');
const subtitle = document.getElementById('panelSubtitle');
const submitBtn = document.getElementById('submitBtn');
const form = document.getElementById('fieldForm');

// Campos ocultos y de entrada de datos del formulario
const _method = document.getElementById('_method'); // Usado para simular PUT en Laravel
const field_id = document.getElementById('field_id');
const nameInput = document.getElementById('name');
const descInput = document.getElementById('description');
const statusInput = document.getElementById('status');

// Elementos para el modal de confirmación de eliminación
let formToDelete = null; // Guardará temporalmente el formulario de eliminación seleccionado
const btnDelete = document.getElementById('btnAceptarEliminar');

// Elementos para el filtrado de la tabla
const filterStatusInput = document.getElementById('tipo');
const tableRows = document.querySelectorAll('.table tbody tr');


/* =====================================================
   LÓGICA DE FILTRADO POR ESTADO (CLIENT-SIDE)
===================================================== */
filterStatusInput.onchange = () => {
    const statusTerm = filterStatusInput.value;
    let visibleCount = 0; // Contador para saber si la tabla quedó vacía

    tableRows.forEach(row => {
        const rowTable = row.querySelector('.row-status');
        
        // Si la fila no tiene la clase .row-status (ej. la fila de "No hay registros"), la saltamos
        if (!rowTable) return;

        const statusText = rowTable.textContent.trim().toLowerCase();
        let matchesStatus = true;

        // Evaluamos si la fila coincide con el filtro seleccionado
        if (statusTerm === 'activas') matchesStatus = (statusText === 'activa');
        if (statusTerm === 'inactivas') matchesStatus = (statusText === 'inactiva');

        // Aplicamos estilos de visibilidad según el resultado
        if (matchesStatus) {
            row.style.display = '';
            row.style.opacity = '1';
            visibleCount++; 
        } else {
            row.style.display = 'none';
        }
    });

    // Verificamos si debemos mostrar el mensaje de tabla vacía
    renderNoResultsRow(visibleCount === 0);
};

// Función encargada de inyectar o remover la fila de "Sin resultados"
function renderNoResultsRow(show) {
    let noResultsRow = document.getElementById('no-results-row');
    const tableBody = document.querySelector('table tbody');

    if (show) {
        // Si no hay registros visibles y la fila de aviso no existe, la creamos
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.id = 'no-results-row';
            // colspan="4" debe coincidir con el total de columnas de la tabla HTML
            noResultsRow.innerHTML = `<td colspan="4" style="text-align: center;">No hay registros para mostrar</td>`;
            tableBody.appendChild(noResultsRow);
        }
    } else if (noResultsRow) {
        // Si hay registros visibles y la fila de aviso existe, la eliminamos
        noResultsRow.remove();
    }
}


/* =====================================================
   GESTIÓN DEL MODAL DE FORMULARIO (CREAR / EDITAR)
===================================================== */

// Prepara el modal para registrar una nueva cancha
function openCreateModal() {
    // 1. Adaptar textos y UI para el modo "Crear"
    title.textContent = 'Nueva cancha';
    subtitle.textContent = 'Registra un nuevo espacio en el club';
    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Guardar cancha';

    // 2. Limpiar el formulario y resetear valores por defecto
    form.reset();
    form.action = url_store; // Apunta a la ruta POST de Laravel
    _method.value = '';      // Limpia el método PUT (si venía de editar)
    field_id.value = '';
    statusInput.value = 1;   // Por defecto activo

    nameInput.value = '';
    descInput.value = '';

    // 3. Mostrar modal
    togglePanel(true);
}

// Prepara el modal para editar una cancha existente
function openEditModal(field) {
    // 1. Adaptar textos y UI para el modo "Editar"
    title.textContent = 'Editar cancha';
    submitBtn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Actualizar datos';

    // 2. Poblar el formulario con los datos recibidos (cargados desde la iteración en Blade)
    field_id.value = field.id;
    nameInput.value = textFormat(field.name);
    descInput.value = textFormat(field.description);
    statusInput.value = field.status;

    // 3. Configurar la acción para Laravel
    form.action = url_update; 
    _method.value = 'PUT'; // Laravel requiere esto para interpretar la petición de actualización

    // 4. Mostrar modal
    togglePanel(true);
}


/* =====================================================
   FUNCIONES DE UTILIDAD Y DISEÑO
===================================================== */

// Formatea el texto: Capitaliza la primera letra y pone el resto en minúscula
function textFormat(text) {
    if (!text || typeof text !== 'string') return '';
    return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}

// Muestra/Oculta el panel modal principal con transición
function togglePanel(isOpen) {
    if (isOpen) {
        modal.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Bloquea el scroll de la página de fondo
    } else {
        modal.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';   // Restaura el scroll de la página
    }
}

// Wrapper simple para cerrar el modal
function closeModal() {
    togglePanel(false);
}

// Cierra el modal principal al presionar la tecla Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});


/* =====================================================
   GESTIÓN DEL MODAL DE ELIMINACIÓN (CONFIRMACIÓN)
===================================================== */

// Intercepta el envío del formulario de eliminación para pedir confirmación previa
function confirmarEliminacion(event, field, name) {
    event.preventDefault(); // Evita que se recargue la página o se envíe el form de inmediato

    // Guarda el formulario exacto de la fila que el usuario quiere eliminar
    formToDelete = event.currentTarget;

    // Muestra el nombre de la cancha en el modal de advertencia para dar contexto
    document.getElementById('fieldName').textContent = textFormat(name);

    // Activa visualmente el modal de confirmación
    const confirmModal = document.getElementById('customConfirm');
    confirmModal.classList.add('active');
}

// Cierra el modal de confirmación sin ejecutar la acción
function cerrarConfirmacion() {
    document.getElementById('customConfirm').classList.remove('active');
    formToDelete = null; // Limpia la referencia por seguridad
}

// Ejecuta el envío real del formulario si el usuario hace clic en "Aceptar"
btnDelete.onclick = () => {
    if (formToDelete) {
        formToDelete.submit(); 
    }
};


/* =====================================================
   EXPONER FUNCIONES AL ÁMBITO GLOBAL (window)
===================================================== */
// Es necesario para que los botones en el HTML (onclick="openCreateModal()") 
// puedan encontrar estas funciones, especialmente si este script se empaqueta con Vite.
window.openCreateModal = openCreateModal;
window.openEditModal = openEditModal;
window.confirmarEliminacion = confirmarEliminacion;
window.cerrarConfirmacion = cerrarConfirmacion;
window.closeModal = closeModal;