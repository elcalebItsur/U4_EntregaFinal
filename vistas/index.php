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
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="favorites.php">Favoritos</a></li>
                    <li><a href="cart.php">Carrito</a></li>
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
                                <button class="agregar-carrito" onclick="agregarAlCarrito(<?php echo $prod['id']; ?>)"><i class="fa fa-cart-plus"></i> Carrito</button>
                                <button class="favorito" onclick="agregarAFavoritos(<?php echo $prod['id']; ?>)"><i class="fa fa-heart"></i> Favorito</button>
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
                                <button class="agregar-carrito" onclick="agregarAlCarrito(<?php echo $prod['id']; ?>)"><i class="fa fa-cart-plus"></i> Carrito</button>
                                <button class="favorito" onclick="agregarAFavoritos(<?php echo $prod['id']; ?>)"><i class="fa fa-heart"></i> Favorito</button>
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
</body>
</html>