// JS para modales de compra y carrito y variable global usuarioActual
window.usuarioActual = window.usuarioActual ?? null;

function abrirModalCompra(id, nombre, stock) {
  if (!window.usuarioActual) {
    alert('Necesitas iniciar sesión para hacer una compra.');
    return;
  }
  document.getElementById('modal-compra').style.display = 'flex';
  document.getElementById('modal-nombre-producto').textContent = nombre;
  document.getElementById('modal-cantidad-compra').value = 1;
  document.getElementById('modal-cantidad-compra').max = stock;
  document.getElementById('modal-stock-info').textContent = 'Stock disponible: ' + stock;
  document.getElementById('modal-compra-mensaje').textContent = '';
  document.getElementById('btn-confirmar-compra').onclick = function() {
    confirmarCompra(id, stock);
  };
}
document.addEventListener('DOMContentLoaded', function() {
  var cerrarBtn = document.getElementById('cerrar-modal-compra');
  if (cerrarBtn) {
    cerrarBtn.onclick = function() {
      document.getElementById('modal-compra').style.display = 'none';
    };
  }
  window.onclick = function(event) {
    var modal = document.getElementById('modal-compra');
    if (event.target === modal) modal.style.display = 'none';
  };
});
function confirmarCompra(id, stock) {
  var cantidad = parseInt(document.getElementById('modal-cantidad-compra').value);
  if (isNaN(cantidad) || cantidad < 1) {
    document.getElementById('modal-compra-mensaje').textContent = 'Cantidad inválida.';
    return;
  }
  if (cantidad > stock) {
    document.getElementById('modal-compra-mensaje').textContent = 'No hay suficiente stock disponible.';
    return;
  }
  document.getElementById('btn-confirmar-compra').disabled = true;
  fetch('comprar.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'producto_id=' + encodeURIComponent(id) + '&cantidad=' + encodeURIComponent(cantidad)
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('modal-compra-mensaje').textContent = 'COMPRA REALIZADA CORRECTAMENTE';
      setTimeout(() => {
        document.getElementById('modal-compra').style.display = 'none';
        window.location.href = 'mis_compras.php';
      }, 1200);
    } else {
      document.getElementById('modal-compra-mensaje').textContent = data.error || 'Error al comprar.';
    }
  })
  .catch(() => {
    document.getElementById('modal-compra-mensaje').textContent = 'Error de conexión.';
  })
  .finally(() => {
    document.getElementById('btn-confirmar-compra').disabled = false;
  });
}
function abrirModalCarrito(id, nombre, stock) {
  document.getElementById('modal-carrito').style.display = 'flex';
  document.getElementById('modal-carrito-nombre').textContent = nombre;
  document.getElementById('modal-carrito-mensaje').textContent = '';

  const modal = document.getElementById('modal-carrito');
  const formId = 'form-agregar-carrito-modal';
  let form = document.getElementById(formId);
  if (form) form.remove();
  const formHtml = `
    <form id="${formId}" method="post" action="cart.php" style="display:flex;flex-direction:column;align-items:center;gap:1rem;width:100%;margin-top:1rem;">
      <input type="hidden" name="agregar" value="1">
      <input type="hidden" name="productoId" value="${id}">
      <label for="modal-carrito-cantidad-form">Cantidad:</label>
      <input type="number" name="cantidad" id="modal-carrito-cantidad-form" value="1" min="1" max="${stock}" style="width:80px;text-align:center;">
      <div style="display:flex;gap:1rem;justify-content:center;">
        <button type="submit" class="btn-primary">Agregar</button>
        <button type="button" onclick="document.getElementById('modal-carrito').style.display='none'" class="btn-secondary">Cancelar</button>
      </div>
    </form>`;

    const oldBtns = document.getElementById('btn-confirmar-carrito')?.parentNode;
  if (oldBtns) oldBtns.innerHTML = '';
  document.getElementById('modal-carrito-mensaje').insertAdjacentHTML('afterend', formHtml);
}
