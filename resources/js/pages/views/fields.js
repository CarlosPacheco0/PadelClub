  
  /* =====================================================
   CONFIGURACIÓN DESDE BLADE
===================================================== */
const { url_store, url_update } = window.FIELDS_CONFIG;

  
  const modal = document.getElementById('fieldModal');
        const overlay = document.getElementById('modalOverlay');
        const title = document.getElementById('modalTitle');
        const subtitle = document.getElementById('panelSubtitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('fieldForm');

        const _method = document.getElementById('_method');
        const field_id = document.getElementById('field_id');
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const statusInput = document.getElementById('status');


        let formToDelete = null;

        const btnDelete = document.getElementById('btnAceptarEliminar');

        // Lógica de Filtrado por Estado
        const filterStatusInput = document.getElementById('tipo');
        const tableRows = document.querySelectorAll('.table tbody tr');

        filterStatusInput.onchange = () => {
            const statusTerm = filterStatusInput.value;
            let visibleCount = 0; // 1. Inicializamos un contador

            tableRows.forEach(row => {
                const rowTable = row.querySelector('.row-status');
                if (!rowTable) return;

                const statusText = rowTable.textContent.trim().toLowerCase();
                let matchesStatus = true;

                if (statusTerm === 'activas') matchesStatus = (statusText === 'activa');
                if (statusTerm === 'inactivas') matchesStatus = (statusText === 'inactiva');

                if (matchesStatus) {
                    row.style.display = '';
                    row.style.opacity = '1';
                    visibleCount++; // 2. Incrementamos si la fila es visible
                } else {
                    row.style.display = 'none';
                }
            });

            // 3. Validar si mostramos el mensaje de "Sin resultados"
            renderNoResultsRow(visibleCount === 0);
        };

        // Función auxiliar para manejar la fila de "No hay registros"
        function renderNoResultsRow(show) {
            let noResultsRow = document.getElementById('no-results-row');
            const tableBody = document.querySelector('table tbody'); // Asegúrate de que apunte a tu tbody

            if (show) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'no-results-row';
                    // Ajusta el colspan según el número de columnas de tu tabla
                    noResultsRow.innerHTML = `<td colspan="4">No hay registros para mostrar</td>`;
                    tableBody.appendChild(noResultsRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }


        function openCreateModal() {
            title.textContent = 'Nueva cancha';
            subtitle.textContent = 'Registra un nuevo espacio en el club';
            submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Guardar cancha';

            form.reset();
            form.action = url_store;
            _method.value = '';
            field_id.value = '';
            statusInput.value = 1;

            nameInput.value = '';
            descInput.value = '';

            togglePanel(true);
        }

        function openEditModal(field) {
            title.textContent = 'Editar cancha';
            submitBtn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Actualizar datos';

            field_id.value = field.id;
            nameInput.value = textFormat(field.name);
            descInput.value = textFormat(field.description);
            statusInput.value = field.status;

            form.action = url_update;
            _method.value = 'PUT';

            togglePanel(true);
        }

        function textFormat(text) {
            // 1. Validamos que el texto exista y sea un string válido
            if (!text || typeof text !== 'string') return '';

            // 2. Capitalizamos la primera letra y aseguramos que el resto esté en minúscula
            return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
        }

        function confirmarEliminacion(event, field, name) {
            event.preventDefault(); // Detenemos el envío automático

            // Guardamos la referencia del formulario que disparó el evento
            formToDelete = event.currentTarget;

            // Seteamos el nombre en el modal
            document.getElementById('fieldName').textContent = textFormat(name);

            // Mostramos el modal
            const modal = document.getElementById('customConfirm');
            modal.classList.add('active');
        }

        function cerrarConfirmacion() {
            document.getElementById('customConfirm').classList.remove('active');
            formToDelete = null;
        }

        // Escuchamos el clic en el botón de eliminar del modal
        btnDelete.onclick = () => {
            if (formToDelete) {
                formToDelete.submit(); // Enviamos el formulario original
            }
        };

        function togglePanel(isOpen) {
            if (isOpen) {
                modal.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Evita scroll de fondo
            } else {
                modal.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        function closeModal() {
            togglePanel(false);
        }

        // Soporte para tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });



/* =====================================================
   EXPONER FUNCIONES (onclick)
===================================================== */
window.openCreateModal = openCreateModal;
window.openEditModal = openEditModal;
window.confirmarEliminacion = confirmarEliminacion;
window.cerrarConfirmacion = cerrarConfirmacion;
window.closeModal = closeModal;