const productos = [
    {id: 1, nombre: 'Camisa Polo', precio: 129.99, descripcion: 'Camisa casual para hombre.', imagen: '../assets/images/camisa1.webp', stock: 12},
    {id: 2, nombre: 'Jeans Corte Recto', precio: 149.99, descripcion: 'Jeans Levis corte recto.', imagen: '../assets/images/jeans1.webp', stock: 8},
    {id: 3, nombre: 'Chaqueta de Cuero', precio: 199.99, descripcion: 'Chaqueta elegante de cuero.', imagen: '../assets/images/chaqueta1.jpg', stock: 5},
    {id: 4, nombre: 'Zapatos de Cuero', precio: 249.99, descripcion: 'Zapatos formales de cuero.', imagen: '../assets/images/zapatos1.webp', stock: 10},
    {id: 5, nombre: 'Pants Deportivo', precio: 99.99, descripcion: 'Pants cómodo para deporte.', imagen: '../assets/images/pants1.jpg', stock: 15},
    {id: 6, nombre: 'Camisa Casual', precio: 89.99, descripcion: 'Camisa casual para mujer.', imagen: '../assets/images/camisa2.jpg', stock: 7}
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

function renderFavoritos() {
    const contenedor = document.getElementById('favoritos-lista');
    const emptyMsg = document.getElementById('empty-favs');
    contenedor.innerHTML = '';

    fetch('favorites.php?ajax=true')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                contenedor.innerHTML = `<p>${data.error}</p>`;
                if (emptyMsg) emptyMsg.style.display = 'none';
                return;
            }

            let favoritos = [];
            try { favoritos = JSON.parse(localStorage.getItem('favoritos_' + window.usuarioActual)) || []; } catch {}
            if (!favoritos.length) {
                favoritos = data.map(id => productos.find(p => p.id === id)).filter(Boolean);
            }

            if (!favoritos.length) {
                if (emptyMsg) emptyMsg.style.display = 'block';
                return;
            } else {
                if (emptyMsg) emptyMsg.style.display = 'none';
            }

            favoritos.forEach(producto => {
                const card = document.createElement('div');
                card.className = 'producto-card';
                card.innerHTML = `
                    <img src="${producto.imagen || '../assets/images/hero_image.jpg'}" alt="${producto.nombre}" onerror="this.src='../assets/images/hero_image.jpg'">
                    <h3>${producto.nombre}</h3>
                    <p>${producto.descripcion || ''}</p>
                    <div class="precio">$${producto.precio?.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) || ''} <span style='font-size:0.95rem;color:#eab308;font-weight:400;'>(Stock: ${producto.stock ?? 0})</span></div>
                    <div class="acciones">
                        <button class="eliminar-favorito" title="Quitar de favoritos"><i class="fa fa-trash"></i> Quitar</button>
                    </div>
                `;
                card.querySelector('.eliminar-favorito').onclick = () => eliminarFavorito(producto.id, card);
                contenedor.appendChild(card);
            });
        })
        .catch(error => {
            contenedor.innerHTML = '<p>Error al cargar los favoritos.</p>';
            if (emptyMsg) emptyMsg.style.display = 'none';
            console.error('Error:', error);
        });
}

function eliminarFavorito(id, card) {
    // Animación de salida
    if (card) {
        card.style.transition = 'opacity 0.4s, transform 0.4s';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => {
            card.remove();
        }, 400);
    }
    // Eliminar del localStorage
    let favoritos = [];
    try { favoritos = JSON.parse(localStorage.getItem('favoritos_' + window.usuarioActual)) || []; } catch {}
    favoritos = favoritos.filter(f => f.id !== id);
    localStorage.setItem('favoritos_' + window.usuarioActual, JSON.stringify(favoritos));
    // Eliminar del backend (PHP)
    fetch('favorites.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `productoId=${id}&eliminar=1`
    })
    .then(() => {
        setTimeout(() => {
            renderFavoritos();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 400);
    });
}

renderFavoritos();