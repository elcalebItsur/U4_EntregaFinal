// admin_tienda.js

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-eliminar-producto');
    const modalNombre = document.getElementById('modal-eliminar-nombre');
    const modalForm = document.getElementById('modal-eliminar-form');
    const closeBtn = document.getElementById('cerrar-modal-eliminar');
    let productoId = null;

    document.querySelectorAll('.btn-eliminar-modal').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            productoId = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            modalNombre.textContent = nombre;
            modal.style.display = 'flex';
        });
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Permitir cerrar el modal con el botón cancelar
    const closeBtn2 = document.getElementById('cerrar-modal-eliminar-2');
    closeBtn2.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    modalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (productoId) {
            window.location.href = 'admin_tienda.php?eliminar=' + productoId;
        }
    });
});
