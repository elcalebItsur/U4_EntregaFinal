const productos = [
    {id: 1, nombre: 'Camisa Polo', precio: 129.99, descripcion: 'Camisa casual para hombre.', imagen: 'assets/images/camisa1.webp'},
    {id: 2, nombre: 'Jeans Corte Recto', precio: 149.99, descripcion: 'Jeans Levis corte recto.', imagen: 'assets/images/jeans1.webp'},
    {id: 3, nombre: 'Chaqueta de Cuero', precio: 199.99, descripcion: 'Chaqueta elegante de cuero.', imagen: 'assets/images/chaqueta1.jpg'},
    {id: 4, nombre: 'Zapatos de Cuero', precio: 249.99, descripcion: 'Zapatos formales de cuero.', imagen: 'assets/images/zapatos1.webp'},
    {id: 5, nombre: 'Pants Deportivo', precio: 99.99, descripcion: 'Pants cómodo para deporte.', imagen: 'assets/images/pants1.jpg'},
    {id: 6, nombre: 'Camisa Casual', precio: 89.99, descripcion: 'Camisa casual para mujer.', imagen: 'assets/images/camisa2.jpg'},
    {id: 7, nombre: 'Sudadera Oversize', precio: 159.99, descripcion: 'Sudadera moderna y cómoda.', imagen: 'assets/images/sudadera%20oversized.jpg'},
    {id: 8, nombre: 'Falda Plisada', precio: 119.99, descripcion: 'Falda elegante para mujer.', imagen: 'assets/images/Falda%20Plisada.webp'},
    {id: 9, nombre: 'Gorra Urbana', precio: 49.99, descripcion: 'Gorra para cualquier ocasión.', imagen: 'assets/images/Gorra%20Urbana.webp'},
    {id: 10, nombre: 'Bolso de Mano', precio: 179.99, descripcion: 'Bolso elegante y práctico.', imagen: 'assets/images/Bolso%20de%20Mano.webp'},
    {id: 11, nombre: 'Playera Deportiva', precio: 79.99, descripcion: 'Playera ideal para entrenar.', imagen: 'assets/images/Playera%20Deportiva.webp'},
    {id: 12, nombre: 'Vestido de Noche', precio: 299.99, descripcion: 'Vestido elegante para eventos.', imagen: 'assets/images/Vestido%20de%20Noche.jpeg'},
    {id: 13, nombre: 'Sombrero de Paja', precio: 39.99, descripcion: 'Sombrero ligero y cómodo.', imagen: 'assets/images/Sombrero%20de%20Paja.webp'},
    {id: 14, nombre: 'Bufanda de Lana', precio: 59.99, descripcion: 'Bufanda cálida para invierno.', imagen: 'assets/images/Bufanda%20de%20Lana.webp'},
    {id: 15, nombre: 'Guantes de Cuero', precio: 89.99, descripcion: 'Guantes elegantes y cómodos.', imagen: 'assets/images/Guantes%20de%20Cuero.webp'}
];

function renderProductos() {
    const cont = document.getElementById('productos-lista');
    cont.innerHTML = '';
    productos.forEach(prod => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
            <img src="${prod.imagen}" alt="${prod.nombre}">
            <h3>${prod.nombre}</h3>
            <p>${prod.descripcion}</p>
            <div class="precio">$${prod.precio.toFixed(2)}</div>
            <div class="acciones">
                <button class="agregar-carrito" title="Agregar al carrito"><i class="fa fa-cart-plus"></i> Carrito</button>
                <button class="favorito" title="Agregar a favoritos"><i class="fa fa-heart"></i> Favorito</button>
            </div>
        `;
        // Eventos
        card.querySelector('.agregar-carrito').onclick = () => agregarAlCarrito(prod.id);
        card.querySelector('.favorito').onclick = () => agregarAFavoritos(prod.id);
        cont.appendChild(card);
    });
}

function verificarSesion() {
    if (!window.usuarioActual) {
        alert('Debes iniciar sesión para usar esta funcionalidad.');
    }
}

function agregarAlCarrito(id) {
    if (!window.usuarioActual) {
        verificarSesion();
        return;
    }
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `productoId=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto agregado al carrito.');
        } else {
            alert(data.error || 'Error al agregar al carrito.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al agregar al carrito.');
    });
}

function agregarAFavoritos(id) {
    if (!window.usuarioActual) {
        verificarSesion();
        return;
    }
    fetch('favorites.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `productoId=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto agregado a favoritos.');
        } else {
            alert(data.error || 'Error al agregar a favoritos.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al agregar a favoritos.');
    });
}

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

function setUserArray(tipo, arr) {
    const key = getUserKey(tipo);
    if (key) localStorage.setItem(key, JSON.stringify(arr));
}

renderProductos();