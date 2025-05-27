<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para ver tus favoritos.']);
        exit();
    }

    $favoritos = $_SESSION['favoritos'] ?? [];
    echo json_encode($favoritos);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para agregar a favoritos.']);
        exit();
    }

    $productoId = $_POST['productoId'] ?? null;
    $eliminar = isset($_POST['eliminar']);
    if ($productoId) {
        if (!isset($_SESSION['favoritos'])) {
            $_SESSION['favoritos'] = [];
        }
        if ($eliminar) {
            // Eliminar producto de favoritos
            $_SESSION['favoritos'] = array_values(array_filter($_SESSION['favoritos'], function($id) use ($productoId) {
                return $id != $productoId;
            }));
            echo json_encode(['success' => true, 'eliminado' => true]);
        } else {
            if (!in_array($productoId, $_SESSION['favoritos'])) {
                $_SESSION['favoritos'][] = $productoId;
            }
            echo json_encode(['success' => true]);
        }
    } else {
        echo json_encode(['error' => 'ID de producto no válido.']);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favoritos - TextiSur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        header { background: #181818; box-shadow: none; border-bottom: 1.5px solid #232323; }
        .header-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 1.2rem 2rem; }
        .logo { font-size:2rem; color:#44ff99; font-weight:700; letter-spacing:1px; cursor:pointer; margin-right:2rem; }
        nav ul { display: flex; gap: 1.2rem; align-items: center; list-style: none; }
        nav ul li { display: flex; align-items: center; }
        nav ul li a, nav ul li span { font-size: 1rem; }
        
        .favoritos-container {
            max-width: 1200px;
            margin: 2.5rem auto;
            background: #1e1e1e;
            border-radius: 14px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 16px #0002;
            animation: fadeInUp 0.7s cubic-bezier(.4,2,.3,1);
        }
        
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .producto-card {
            background: #232323;
            border-radius: 12px;
            padding: 1.2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #2e2e2e;
            position: relative;
        }
        
        .producto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-color: #44ff99;
        }
        
        .producto-card img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 1rem;
            aspect-ratio: 1/1;
            object-fit: cover;
        }
        
        .producto-card h3 {
            color: #fff;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .producto-card .precio {
            color: #44ff99;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0.5rem 0;
        }
        
        .producto-card .acciones {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }
        
        .producto-card button {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        
        .agregar-carrito {
            background: #1a7f37;
            color: white;
        }
        
        .agregar-carrito:hover {
            background: #14532d;
        }
        
        .eliminar-favorito {
            background: #ff6b6b;
            color: white;
        }
        
        .eliminar-favorito:hover {
            background: #e63946;
        }
        
        .empty-favs {
            text-align: center;
            padding: 2rem;
            color: #eab308;
            font-size: 1.1rem;
        }
        
        .favs-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            color: #44ff99;
            margin-bottom: 1.5rem;
        }
    </style>
    <script src="../js/index.js" defer></script>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario_id']) ? json_encode($_SESSION['usuario_id']) : 'null'; ?>;
    
    function cargarFavoritos() {
        if (!window.usuarioActual) {
            document.getElementById('favoritos-lista').innerHTML = '<div class="empty-favs">Debes iniciar sesión para ver tus favoritos.</div>';
            return;
        }
        
        fetch('favorites.php?ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('favoritos-lista').innerHTML = `<div class="empty-favs">${data.error}</div>`;
                    return;
                }
                
                if (!data.length) {
                    document.getElementById('empty-favs').style.display = 'block';
                    document.getElementById('favoritos-lista').style.display = 'none';
                    return;
                }
                
                // Obtener los productos completos de index.js
                const productosFavoritos = productos.filter(p => data.includes(p.id));
                
                const cont = document.getElementById('favoritos-lista');
                cont.innerHTML = '';
                
                productosFavoritos.forEach(prod => {
                    const card = document.createElement('div');
                    card.className = 'producto-card';
                    card.innerHTML = `
                        <img src="${prod.imagen}" alt="${prod.nombre}" onerror="this.src='../assets/images/hero_image.jpg'">
                        <h3>${prod.nombre}</h3>
                        <p>${prod.descripcion}</p>
                        <div class="precio">$${prod.precio.toFixed(2)}</div>
                        <div class="acciones">
                            <button class="agregar-carrito" onclick="agregarAlCarrito(${prod.id})">
                                <i class="fa fa-cart-plus"></i> Carrito
                            </button>
                            <button class="eliminar-favorito" onclick="eliminarDeFavoritos(${prod.id})">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                        </div>
                    `;
                    cont.appendChild(card);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('favoritos-lista').innerHTML = '<div class="empty-favs">Error al cargar favoritos.</div>';
            });
    }
    
    function eliminarDeFavoritos(id) {
        if (!window.usuarioActual) {
            alert('Debes iniciar sesión para esta acción.');
            return;
        }
        
        fetch('favorites.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `productoId=${id}&eliminar=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la lista de favoritos
                cargarFavoritos();
                // Actualizar localStorage
                let favoritos = [];
                try { favoritos = JSON.parse(localStorage.getItem('favoritos_' + window.usuarioActual)) || []; } catch {}
                localStorage.setItem('favoritos_' + window.usuarioActual, JSON.stringify(favoritos.filter(p => p.id !== id)));
            } else {
                alert(data.error || 'Error al eliminar de favoritos.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar de favoritos.');
        });
    }
    
    document.addEventListener('DOMContentLoaded', cargarFavoritos);
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">TextiSur</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="favorites.php" style="color:#eab308;font-weight:700;"><i class="fa fa-heart"></i> Favoritos</a></li>
                    <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> Carrito</a></li>
                    <li><a href="about.php"><i class="fa fa-store"></i> Vende</a></li>
                    <li><a href="acerca.php" class="btn-accent"><i class="fa fa-info-circle"></i> Acerca de</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li><span style="color:#44ff99;"><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span></li>
                        <li><a href="../logout.php" class="btn-secondary">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                        <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="favoritos-container">
        <div class="favs-title">
            <i class="fas fa-heart" style="font-size: 1.5rem;"></i>
            <h2>Tus Productos Favoritos</h2>
        </div>
        
        <div id="favoritos-lista" class="productos-grid"></div>
        <div id="empty-favs" class="empty-favs" style="display:none;">
            <i class="fas fa-heart-broken" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No tienes productos en favoritos.</p>
            <p>¡Explora la tienda y agrega tus prendas preferidas!</p>
            <a href="index.php" class="btn-primary" style="margin-top: 1rem;">Ver productos</a>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>