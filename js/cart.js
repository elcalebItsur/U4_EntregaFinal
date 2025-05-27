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
            `;
        })
        .catch(error => {
            contenedor.innerHTML = '<p>Error al cargar el carrito.</p>';
        });
}

renderCarrito();