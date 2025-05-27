<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../datos/conexion.php';
require_once '../datos/ProductoDAO.php';
$foto = null;
if (isset($_SESSION['usuario_id'])) {
    $stmt = $pdo->prepare('SELECT foto_perfil FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['usuario_id']]);
    $foto = $stmt->fetchColumn();
}

// Filtrado por categoría
$categoriaSeleccionada = $_GET['categoria'] ?? null;
if ($categoriaSeleccionada) {
    $productos = ProductoDAO::obtenerPorCategoria($categoriaSeleccionada);
} else {
    $productos = ProductoDAO::obtenerPopulares(6); // Populares aleatorios
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TextiSur - Tu Tienda de Ropa</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="../css/main-header.css" />
    <script src="../js/index.js" defer></script>
    <script src="../js/main-header.js" defer></script>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario_id']) ? json_encode($_SESSION['usuario_id']) : 'null'; ?>;
    </script>
    <script>
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
          actualizarIconoCarrito();
          // Notifica a la pestaña del carrito que debe actualizarse
          if (window.localStorage) {
            localStorage.setItem('carrito_actualizado', Date.now());
          }
          // Si estamos en la página del carrito, actualiza la vista directamente
          if (window.actualizarCarritoVista) {
            window.actualizarCarritoVista();
          }
        } else {
          alert(data.error || 'No se pudo agregar al carrito.');
        }
      })
      .catch(() => alert('Error de conexión.'));
    }

    function mostrarNotificacion(msg) {
      let notif = document.getElementById('notif-carrito');
      if (!notif) {
        notif = document.createElement('div');
        notif.id = 'notif-carrito';
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

    function actualizarIconoCarrito() {
      fetch('cart.php?ajax=true')
        .then(r => r.json())
        .then(data => {
          let total = 0;
          if (Array.isArray(data)) {
            data.forEach(item => total += parseInt(item.cantidad));
          }
          let icon = document.getElementById('cart-count');
          if (!icon) {
            const li = document.querySelector('a[href="cart.php"]');
            if (li) {
              icon = document.createElement('span');
              icon.id = 'cart-count';
              icon.style.background = '#44ff99';
              icon.style.color = '#181818';
              icon.style.fontWeight = 'bold';
              icon.style.fontSize = '0.9rem';
              icon.style.borderRadius = '50%';
              icon.style.padding = '2px 8px';
              icon.style.marginLeft = '6px';
              icon.style.verticalAlign = 'middle';
              li.appendChild(icon);
            }
          }
          if (icon) icon.textContent = total > 0 ? total : '';
        });
    }
    document.addEventListener('DOMContentLoaded', actualizarIconoCarrito);
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="about.php">Vende</a></li>
                    <li><a href="acerca.php" class="btn-accent">Acerca de</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                            <li><a href="admin_tienda.php" class="btn-primary">Administrar Tienda</a></li>
                        <?php endif; ?>
                        <li class="user-menu">
                            <button class="user-avatar-btn" id="user-avatar-btn">
                                <?php
                                $foto = $stmt->fetchColumn();
                                if ($foto): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" />
                                <?php else: ?>
                                    <span class="avatar-inicial"><?php echo strtoupper(substr($_SESSION['usuario'],0,1)); ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                        <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <?php if (isset($_SESSION['usuario'])): ?>
    <div id="user-modal" class="user-modal" style="display:none;">
        <div class="user-modal-content">
            <button class="user-modal-close">&times;</button>
            <div style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
                <?php if ($foto): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" style="width:80px;height:80px;border-radius:50%;object-fit:cover;box-shadow:0 2px 8px #0003;" />
                <?php else: ?>
                    <div style="width:80px;height:80px;border-radius:50%;background:#44ff99;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#181818;font-size:2.2rem;">
                        <?php echo strtoupper(substr($_SESSION['usuario'],0,1)); ?>
                    </div>
                <?php endif; ?>
                <div style="text-align:center;">
                    <div style="font-size:1.2rem;font-weight:600;"> <?php echo htmlspecialchars($_SESSION['usuario']); ?> </div>
                    <div style="color:#aaa;font-size:0.98rem;"> <?php echo htmlspecialchars($_SESSION['email']); ?> </div>
                </div>
            </div>
            <ul class="user-menu-list">
                <li><a href="perfil.php"><i class="fa fa-edit"></i> Editar perfil</a></li>
                <li><a href="ver_perfil.php"><i class="fa fa-user"></i> Ver perfil</a></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out-alt"></i> Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <main style="max-width: 1200px; margin: 0 auto;">
        <section class="hero">
            <div class="hero-text">
                <h2>Descubre tu estilo único</h2>
                <p>Explora las últimas tendencias en moda y encuentra tu look perfecto.</p>
                <a href="#populares" class="btn-primary">Ver Populares</a>
            </div>
            <div class="hero-img">
                <img src="../assets/images/hero_image.jpg" alt="Moda Textisur" loading="lazy">
            </div>
        </section>
        <section class="categorias">
            <h2 style="color:#44ff99;">Categorías</h2>
            <div class="grid">
                <div class="card" onclick="window.location.href='index.php?categoria=Hombre'"><i class="fa fa-mars"></i><span>Hombre</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Mujer'"><i class="fa fa-venus"></i><span>Mujer</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Niños'"><i class="fa fa-child"></i><span>Niños</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Accesorios'"><i class="fa fa-hat-cowboy"></i><span>Accesorios</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Deporte'"><i class="fa fa-futbol"></i><span>Deporte</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Unisex'"><i class="fa fa-user"></i><span>Unisex</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Invierno'"><i class="fa fa-snowflake"></i><span>Invierno</span></div>
                <div class="card" onclick="window.location.href='index.php?categoria=Oficina'"><i class="fa fa-briefcase"></i><span>Oficina</span></div>
            </div>
        </section>
        <section class="populares" id="populares">
            <h2 style="color:#44ff99;"><?php echo $categoriaSeleccionada ? 'Productos de ' . htmlspecialchars($categoriaSeleccionada) : 'Más Populares'; ?></h2>
            <div class="productos-grid">
                <?php if (empty($productos)): ?>
                    <div style="color:#eab308;font-size:1.2rem;">No hay productos para mostrar.</div>
                <?php else: ?>
                    <?php foreach ($productos as $prod): ?>
                        <div class="producto-card">
                            <img src="../assets/images/<?php echo htmlspecialchars($prod['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" onerror="this.src='../assets/images/hero_image.jpg'">
                            <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                            <?php if (!empty($prod['nombre_tienda'])): ?>
                                <div style="color:#eab308;font-size:0.98rem;margin-bottom:0.2rem;text-align:center;">Tienda: <?php echo htmlspecialchars($prod['nombre_tienda']); ?></div>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($prod['descripcion'] ?? ''); ?></p>
                            <div class="precio">$<?php echo number_format($prod['precio'],2); ?></div>
                            <div class="acciones">
                                <button class="comprar-ahora" onclick="abrirModalCompra(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars(addslashes($prod['nombre'])); ?>', <?php echo (int)($prod['stock'] ?? 0); ?>)"><i class="fa fa-bolt"></i> Comprar ahora</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <section class="vender">
            <h2>¿Eres vendedor?</h2>
            <p>Empieza a vender tus productos en minutos. Es rápido y fácil.</p>
            <a href="about.php" class="btn-primary">Comienza Ahora</a>
        </section>
        <section class="todos-productos" id="todos-productos">
            <h2 style="color:#44ff99;">Todos los productos disponibles</h2>
            <div class="productos-grid">
                <?php 
                $todos = ProductoDAO::obtenerTodos();
                if (empty($todos)): ?>
                    <div style="color:#eab308;font-size:1.2rem;">No hay productos para mostrar.</div>
                <?php else: ?>
                    <?php foreach ($todos as $prod): ?>
                        <div class="producto-card">
                            <img src="../assets/images/<?php echo htmlspecialchars($prod['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" onerror="this.src='../assets/images/hero_image.jpg'">
                            <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                            <?php if (!empty($prod['nombre_tienda'])): ?>
                                <div style="color:#eab308;font-size:0.98rem;margin-bottom:0.2rem;text-align:center;">Tienda: <?php echo htmlspecialchars($prod['nombre_tienda']); ?></div>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($prod['descripcion'] ?? ''); ?></p>
                            <div class="precio">$<?php echo number_format($prod['precio'],2); ?></div>
                            <div class="acciones">
                                <button class="comprar-ahora" onclick="abrirModalCompra(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars(addslashes($prod['nombre'])); ?>', <?php echo (int)($prod['stock'] ?? 0); ?>)"><i class="fa fa-bolt"></i> Comprar ahora</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
    <!-- Modal de compra -->
    <div id="modal-compra" class="modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
      <div class="modal-content" style="background:#232323;padding:2rem 2.5rem;border-radius:14px;max-width:350px;width:100%;text-align:center;position:relative;">
        <button id="cerrar-modal-compra" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.3rem;color:#aaa;cursor:pointer;">&times;</button>
        <h3 style="color:#44ff99;">Comprar <span id="modal-nombre-producto"></span></h3>
        <div style="margin:1rem 0;">
          <label for="modal-cantidad-compra">Cantidad:</label>
          <input type="number" id="modal-cantidad-compra" min="1" value="1" style="width:60px;text-align:center;">
          <div id="modal-stock-info" style="color:#eab308;font-size:0.95rem;margin-top:0.3rem;"></div>
        </div>
        <button id="btn-confirmar-compra" class="btn-primary" style="width:100%;margin-top:1rem;">Confirmar compra</button>
        <div id="modal-compra-mensaje" style="margin-top:1rem;font-size:1rem;"></div>
      </div>
    </div>
    <script src="../js/index.js"></script>
    <script>
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
    document.getElementById('cerrar-modal-compra').onclick = function() {
      document.getElementById('modal-compra').style.display = 'none';
    };
    window.onclick = function(event) {
      var modal = document.getElementById('modal-compra');
      if (event.target === modal) modal.style.display = 'none';
    };
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
    </script>
</body>
</html>