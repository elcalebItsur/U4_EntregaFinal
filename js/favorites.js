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

function agregarAFavoritos(id) {
  if (!window.usuarioActual) {
    alert('Debes iniciar sesión para agregar a favoritos.');
    window.location.href = 'login.php';
    return;
  }
  fetch('favorites.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'productoId=' + encodeURIComponent(id)
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      mostrarNotificacion('Producto agregado a favoritos');
      if (window.actualizarFavoritosVista) window.actualizarFavoritosVista();
    } else {
      alert(data.error || 'No se pudo agregar a favoritos.');
    }
  })
  .catch(() => alert('Error de conexión.'));
}

function eliminarDeFavoritos(id) {
  fetch('favorites.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'productoId=' + encodeURIComponent(id) + '&eliminar=1'
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      mostrarNotificacion('Producto eliminado de favoritos');
      if (window.actualizarFavoritosVista) window.actualizarFavoritosVista();
    } else {
      alert(data.error || 'No se pudo eliminar de favoritos.');
    }
  })
  .catch(() => alert('Error de conexión.'));
}

function mostrarNotificacion(msg) {
  let notif = document.getElementById('notif-fav');
  if (!notif) {
    notif = document.createElement('div');
    notif.id = 'notif-fav';
    notif.style.position = 'fixed';
    notif.style.top = '30px';
    notif.style.right = '30px';
    notif.style.background = '#44ff99';
    notif.style.color = '#181818';
    notif.style.padding = '1rem 2rem';
    notif.style.borderRadius = '10px';
    notif.style.fontWeight = 'bold';
    notif.style.boxShadow = '0 4px 16px #0005';
    notif.style.zIndex = 9999;
    notif.style.opacity = 0;
    notif.style.transition = 'opacity 0.3s, top 0.3s';
    document.body.appendChild(notif);
  }
  notif.textContent = msg;
  notif.style.opacity = 1;
  notif.style.top = '30px';
  setTimeout(() => {
    notif.style.opacity = 0;
    notif.style.top = '10px';
  }, 1200);
}

function actualizarFavoritosVista() {
  fetch('favorites.php?ajax=true', {cache: 'no-store'})
    .then(r => r.json())
    .then(data => {
      const cont = document.getElementById('favoritos-lista');
      if (!cont) return;
      cont.innerHTML = '';
      if (!Array.isArray(data) || data.length === 0) {
        cont.innerHTML = '<p>No tienes productos favoritos.</p>';
        return;
      }
      data.forEach(prod => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
          <img src="../assets/images/${prod.imagen || 'hero_image.jpg'}" alt="${prod.nombre}">
          <h3>${prod.nombre}</h3>
          <div class="precio">$${parseFloat(prod.precio).toFixed(2)}</div>
          <div class="acciones">
            <button class="agregar-carrito" onclick="agregarAlCarrito(${prod.id})"><i class="fa fa-cart-plus"></i> Carrito</button>
            <button class="eliminar-favorito" onclick="eliminarDeFavoritos(${prod.id})"><i class="fa fa-heart-broken"></i> Quitar</button>
          </div>
        `;
        cont.appendChild(card);
      });
    });
}
window.actualizarFavoritosVista = actualizarFavoritosVista;
document.addEventListener('DOMContentLoaded', actualizarFavoritosVista);

renderFavoritos();

function agregarAlCarrito(id) {
  if (!window.usuarioActual) {
    alert('Debes iniciar sesión para agregar productos al carrito.');
    window.location.href = 'login.php';
    return;
  }
  fetch('cart.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'agregar=1&productoId=' + encodeURIComponent(id)
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      mostrarNotificacion('Producto agregado al carrito');
      if (window.actualizarCarritoVista) window.actualizarCarritoVista();
    } else {
      alert(data.error || 'No se pudo agregar al carrito.');
    }
  })
  .catch(() => alert('Error de conexión.'));
}