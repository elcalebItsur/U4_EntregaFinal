function renderCarrito() {
    const contenedor = document.getElementById('carrito-lista');
    const resumen = document.getElementById('resumen');
    if (!contenedor || !resumen) return;
    contenedor.innerHTML = '';
    resumen.innerHTML = '';

    fetch('cart.php?ajax=true', {cache: 'no-store'})
        .then(response => {
            if (!response.ok) throw new Error('Respuesta no OK');
            return response.json();
        })
        .then(data => {
            if (data.error) {
                contenedor.innerHTML = `<p>${data.error}</p>`;
                return;
            }
            if (!Array.isArray(data) || data.length === 0) {
                contenedor.innerHTML = '<p>Tu carrito está vacío.</p>';
                return;
            }
            let subtotal = 0;
            data.forEach(producto => {
                subtotal += producto.precio * producto.cantidad;
                const item = document.createElement('div');
                item.className = 'item-carrito';
                item.innerHTML = `
                    <img src="../assets/images/${producto.imagen || 'hero_image.jpg'}" alt="${producto.nombre}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                    <div style="flex:1;margin-left:1rem;">
                        <p style="font-weight:600;">${producto.nombre}</p>
                        <p>Cantidad: ${producto.cantidad} | Stock: ${producto.stock}</p>
                        <p>Precio: $${producto.precio.toFixed(2)}</p>
                    </div>
                `;
                contenedor.appendChild(item);
            });
            const envio = 10.00;
            resumen.innerHTML = `
                <p>Subtotal: $${subtotal.toFixed(2)}</p>
                <p>Envío: $${envio.toFixed(2)}</p>
                <p>Total: $${(subtotal+envio).toFixed(2)}</p>
                <form method="post" action="cart.php">
                    <button type="submit" name="finalizar" class="btn-primary">Finalizar compra</button>
                </form>
            `;
        })
        .catch(error => {
            contenedor.innerHTML = '<p>Error al cargar el carrito. Intenta recargar la página.</p>';
        });
}

window.actualizarCarritoVista = renderCarrito;

document.addEventListener('DOMContentLoaded', renderCarrito);

// Permite actualizar el carrito automáticamente al agregar productos desde otras páginas
window.addEventListener('storage', function(e) {
    if (e.key === 'carrito_actualizado') {
        renderCarrito();
    }
});