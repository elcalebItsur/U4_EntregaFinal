document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-eliminar-cart');
    const modalNombre = document.getElementById('modal-eliminar-cart-nombre');
    const modalForm = document.getElementById('modal-eliminar-cart-form');
    const closeBtn = document.getElementById('cerrar-modal-eliminar-cart');
    let productoId = null;

    document.querySelectorAll('.btn-eliminar-cart-modal').forEach(btn => {
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

    document.getElementById('cerrar-modal-eliminar-cart-2').addEventListener('click', function() {
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
            window.location.href = 'cart.php?eliminar=' + productoId;
        }
    });
});