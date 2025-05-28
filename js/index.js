
if (window.productos) {
} else {
    window.productos = [
        {id: 1, nombre: 'Camisa Polo', precio: 129.99, descripcion: 'Camisa casual para hombre.', imagen: '../assets/images/camisa1.webp', categoria: 'Hombre', temporada: false, oferta: true, precioOriginal: 179.99},
        {id: 2, nombre: 'Jeans Corte Recto', precio: 149.99, descripcion: 'Jeans Levis corte recto.', imagen: '../assets/images/jeans1.webp', categoria: 'Hombre', temporada: true, oferta: false},
        {id: 3, nombre: 'Chaqueta de Cuero', precio: 199.99, descripcion: 'Chaqueta elegante de cuero.', imagen: '../assets/images/chaqueta1.jpg', categoria: 'Hombre', temporada: false, oferta: true, precioOriginal: 249.99},
        {id: 4, nombre: 'Zapatos de Cuero', precio: 249.99, descripcion: 'Zapatos formales de cuero.', imagen: '../assets/images/zapatos1.webp', categoria: 'Hombre', temporada: false, oferta: false},
        {id: 5, nombre: 'Pants Deportivo', precio: 99.99, descripcion: 'Pants cómodo para deporte.', imagen: '../assets/images/pants1.jpg', categoria: 'Hombre', temporada: true, oferta: false},
        {id: 6, nombre: 'Camisa Casual', precio: 89.99, descripcion: 'Camisa casual para mujer.', imagen: '../assets/images/camisa2.jpg', categoria: 'Mujer', temporada: false, oferta: false},
        {id: 7, nombre: 'Sudadera Oversize', precio: 159.99, descripcion: 'Sudadera moderna y cómoda.', imagen: '../assets/images/sudadera_oversized.jpg', categoria: 'Unisex', temporada: true, oferta: true, precioOriginal: 199.99},
        {id: 8, nombre: 'Falda Plisada', precio: 119.99, descripcion: 'Falda elegante para mujer.', imagen: '../assets/images/Falda_Plisada.webp', categoria: 'Mujer', temporada: true, oferta: false},
        {id: 9, nombre: 'Gorra Urbana', precio: 49.99, descripcion: 'Gorra para cualquier ocasión.', imagen: '../assets/images/Gorra_Urbana.webp', categoria: 'Accesorios', temporada: false, oferta: false},
        {id: 10, nombre: 'Bolso de Mano', precio: 179.99, descripcion: 'Bolso elegante y práctico.', imagen: '../assets/images/Bolso_de_Mano.webp', categoria: 'Accesorios', temporada: false, oferta: true, precioOriginal: 229.99},
        {id: 11, nombre: 'Playera Deportiva', precio: 79.99, descripcion: 'Playera ideal para entrenar.', imagen: '../assets/images/Playera_Deportiva.webp', categoria: 'Deporte', temporada: true, oferta: false},
        {id: 12, nombre: 'Vestido de Noche', precio: 299.99, descripcion: 'Vestido elegante para eventos.', imagen: '../assets/images/Vestido_de_Noche.jpeg', categoria: 'Mujer', temporada: true, oferta: true, precioOriginal: 399.99},
        {id: 13, nombre: 'Sombrero de Paja', precio: 39.99, descripcion: 'Sombrero ligero y cómodo.', imagen: '../assets/images/Sombrero_de_Paja.webp', categoria: 'Accesorios', temporada: false, oferta: false},
        {id: 14, nombre: 'Bufanda de Lana', precio: 59.99, descripcion: 'Bufanda cálida para invierno.', imagen: '../assets/images/Bufanda_de_Lana.webp', categoria: 'Accesorios', temporada: true, oferta: false},
        {id: 15, nombre: 'Guantes de Cuero', precio: 89.99, descripcion: 'Guantes elegantes y cómodos.', imagen: '../assets/images/Guantes_de_Cuero.webp', categoria: 'Accesorios', temporada: true, oferta: false},
        {id: 16, nombre: 'Chamarra Impermeable', precio: 219.99, descripcion: 'Chamarra para lluvia y frío.', imagen: '../assets/images/chaqueta1.jpg', categoria: 'Unisex', temporada: true, oferta: true, precioOriginal: 299.99},
        {id: 17, nombre: 'Short Deportivo', precio: 59.99, descripcion: 'Short fresco para entrenar.', imagen: '../assets/images/Playera_Deportiva.webp', categoria: 'Deporte', temporada: true, oferta: false},
        {id: 18, nombre: 'Blusa Floral', precio: 109.99, descripcion: 'Blusa fresca y colorida.', imagen: '../assets/images/camisa2.jpg', categoria: 'Mujer', temporada: true, oferta: false},
        {id: 19, nombre: 'Pantalón Cargo', precio: 139.99, descripcion: 'Pantalón cómodo y resistente.', imagen: '../assets/images/jeans1.webp', categoria: 'Hombre', temporada: false, oferta: false},
        {id: 20, nombre: 'Mochila Urbana', precio: 129.99, descripcion: 'Mochila práctica y moderna.', imagen: '../assets/images/Bolso_de_Mano.webp', categoria: 'Accesorios', temporada: false, oferta: true, precioOriginal: 179.99},
        {id: 21, nombre: 'Vestido Casual', precio: 139.99, descripcion: 'Vestido cómodo para el día a día.', imagen: '../assets/images/Vestido_de_Noche.jpeg', categoria: 'Mujer', temporada: true, oferta: false},
        {id: 22, nombre: 'Sudadera Deportiva', precio: 119.99, descripcion: 'Sudadera para entrenar.', imagen: '../assets/images/sudadera_oversized.jpg', categoria: 'Deporte', temporada: true, oferta: false},
        {id: 23, nombre: 'Calcetas Divertidas', precio: 19.99, descripcion: 'Calcetas con diseños únicos.', imagen: '../assets/images/Bufanda_de_Lana.webp', categoria: 'Accesorios', temporada: false, oferta: false},
        {id: 24, nombre: 'Camisa Formal', precio: 159.99, descripcion: 'Camisa elegante para oficina.', imagen: '../assets/images/camisa1.webp', categoria: 'Hombre', temporada: false, oferta: false}
    ];
}
const productos = window.productos;

function renderOfertas() {
    const cont = document.getElementById('ofertas-lista');
    if (!cont) return;
    cont.innerHTML = '';
    productos.filter(p => p.oferta).forEach(prod => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
            <img src="${prod.imagen}" alt="${prod.nombre}" onerror="this.src='../assets/images/hero_image.jpg'">
            <h3>${prod.nombre}</h3>
            <p>${prod.descripcion}</p>
            <div class="precio">
                <span style='text-decoration:line-through;color:#ff6b6b;font-size:0.95rem;'>$${prod.precioOriginal?.toFixed(2) || ''}</span>
                <span style='margin-left:0.5rem;'>$${prod.precio.toFixed(2)}</span>
            </div>
            <div class="acciones">
                <button class="comprar-ahora" title="Comprar ahora"><i class="fa fa-bolt"></i> Comprar ahora</button>
            </div>
        `;
        card.querySelector('.comprar-ahora').onclick = () => abrirModalCompra(prod.id, prod.nombre, prod.stock ?? 99);
        cont.appendChild(card);
    });
}

function renderProductos() {
    const cont = document.getElementById('productos-lista');
    if (!cont) return;
    cont.innerHTML = '';
    productos.forEach(prod => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
            <img src="${prod.imagen}" alt="${prod.nombre}" onerror="this.src='../assets/images/hero_image.jpg'">
            <h3>${prod.nombre}</h3>
            <p>${prod.descripcion}</p>
            <div class="precio">$${prod.precio.toFixed(2)}</div>
            <div class="acciones">
                <form method="post" action="vistas/cart.php" style="display:inline;">
                    <input type="hidden" name="agregar" value="1">
                    <input type="hidden" name="productoId" value="${prod.id}">
                    <input type="number" name="cantidad" value="1" min="1" max="99" style="width:50px;">
                    <button type="submit" class="btn-primary">Agregar al carrito</button>
                </form>
            </div>
        `;
        cont.appendChild(card);
    });
}

function renderTemporada() {
    const cont = document.getElementById('temporada-lista');
    cont.innerHTML = '';
    productos.filter(p => p.temporada).forEach(prod => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
            <img src="${prod.imagen}" alt="${prod.nombre}" onerror="this.src='../assets/images/hero_image.jpg'">
            <h3>${prod.nombre}</h3>
            <p>${prod.descripcion}</p>
            <div class="precio">$${prod.precio.toFixed(2)}</div>
            <div class="acciones">
                <button class="comprar-ahora" title="Comprar ahora"><i class="fa fa-bolt"></i> Comprar ahora</button>
            </div>
        `;
        card.querySelector('.comprar-ahora').onclick = () => abrirModalCompra(prod.id, prod.nombre, prod.stock ?? 99);
        cont.appendChild(card);
    });
}

function verificarSesion() {
    if (!window.usuarioActual) {
        alert('Debes iniciar sesión para usar esta funcionalidad.');
    }
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
            // Guardar producto completo en favoritos
            let favoritos = [];
            try { favoritos = JSON.parse(localStorage.getItem('favoritos_' + window.usuarioActual)) || []; } catch {}
            const prod = productos.find(p => p.id === id);
            if (prod && !favoritos.some(f => f.id === id)) {
                favoritos.push(prod);
                localStorage.setItem('favoritos_' + window.usuarioActual, JSON.stringify(favoritos));
            }
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

window.addEventListener('DOMContentLoaded', () => {
    renderProductos();
    renderOfertas();
    renderTemporada();
    // Cerrar modal usuario al hacer clic fuera
    const userModal = document.getElementById('user-modal');
    if (userModal) {
        userModal.addEventListener('click', function(e) {
            if (e.target === userModal) userModal.style.display = 'none';
        });
    }
});