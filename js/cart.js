const productos = [
    {id: 1, nombre: 'Camisa Polo', precio: 129.99},
    {id: 2, nombre: 'Jeans Corte Recto', precio: 149.99},
    {id: 3, nombre: 'Chaqueta de Cuero', precio: 199.99},
    {id: 4, nombre: 'Zapatos de Cuero', precio: 249.99},
    {id: 5, nombre: 'Pants Deportivo', precio: 99.99},
    {id: 6, nombre: 'Camisa Casual', precio: 89.99}
];

function getUserKey(tipo) {
    if (!window.usuarioActual) return null;
    return tipo + '_' + window.usuarioActual;
}

function getUserArray(tipo) {
    const key = getUserKey(tipo);
    if (!key) return [];
    try {
        return JSON.parse(localStorage.getItem(key)) || [];
    } catch { return []; }
}

function setUserArray(tipo, array) {
    const key = getUserKey(tipo);
    if (key) {
        localStorage.setItem(key, JSON.stringify(array));
    }
}

function renderCarrito() {
    const contenedor = document.getElementById('carrito-lista');
    const resumen = document.getElementById('resumen');
    contenedor.innerHTML = '';
    resumen.innerHTML = '';

    fetch('cart.php?ajax=true')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                contenedor.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                contenedor.innerHTML = '<p>Tu carrito está vacío.</p>';
                return;
            }

            let subtotal = 0;
            data.forEach(producto => {
                subtotal += producto.precio;
                const item = document.createElement('div');
                item.className = 'item-carrito';
                item.innerHTML = `
                    <p>${producto.nombre}</p>
                    <p>$${producto.precio.toFixed(2)}</p>
                `;
                contenedor.appendChild(item);
            });

            const envio = 10.00;
            const total = subtotal + envio;
            resumen.innerHTML = `
                <p>Subtotal: $${subtotal.toFixed(2)}</p>
                <p>Envío: $${envio.toFixed(2)}</p>
                <p>Total: $${total.toFixed(2)}</p>
                <button class="btn-primary">Proceder a pagar</button>
            `;
        })
        .catch(error => {
            contenedor.innerHTML = '<p>Error al cargar el carrito.</p>';
            console.error('Error:', error);
        });
}

renderCarrito();