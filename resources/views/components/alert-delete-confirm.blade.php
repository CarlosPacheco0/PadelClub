    <div id="customConfirm" class="confirm-overlay">
        <div class="confirm-card">
            <div class="confirm-icon">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 class="confirm-title">¿Eliminar cancha?</h3>
            <p class="confirm-text">
                Estás a punto de eliminar <span id="paramText"></span> <strong id="fieldName" style="color: #fff;"></strong>.
                Esta acción no se puede deshacer.
            </p>
            <div class="confirm-actions">
                <button type="button" class="btn-confirm-cancel" onclick="cerrarConfirmacion()">Cancelar</button>
                <button type="button" class="btn-confirm-delete" id="btn-confirm-delete">Eliminar ahora</button>
            </div>
        </div>
    </div>