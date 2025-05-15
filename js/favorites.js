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

function renderFavoritos() {
    const contenedor = document.getElementById('favoritos-lista');
    contenedor.innerHTML = '';

    fetch('favorites.php?ajax=true')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                contenedor.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                contenedor.innerHTML = '<p>No tienes productos en favoritos.</p>';
                return;
            }

            data.forEach(producto => {
                const item = document.createElement('div');
                item.className = 'producto';
                item.innerHTML = `
                    <p>${producto.nombre}</p>
                `;
                contenedor.appendChild(item);
            });
        })
        .catch(error => {
            contenedor.innerHTML = '<p>Error al cargar los favoritos.</p>';
            console.error('Error:', error);
        });
}

renderFavoritos();