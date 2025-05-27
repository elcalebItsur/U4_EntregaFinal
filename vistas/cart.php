<?php
session_start();
require_once '../datos/CarritoDAO.php';
require_once '../datos/VentaDAO.php';
require_once '../datos/ProductoDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    if (isset($_POST['agregar']) || (isset($_GET['ajax']) && $_GET['ajax'] == 'true')) {
        echo json_encode(['error' => 'Debes iniciar sesión.']);
        exit();
    }
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';

// AJAX: agregar producto al carrito
if (isset($_POST['agregar']) && isset($_POST['productoId'])) {
    $producto_id = intval($_POST['productoId']);
    CarritoDAO::agregarProducto($usuario_id, $producto_id, 1);
    echo json_encode(['success' => true]);
    exit();
}
// AJAX: obtener carrito
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    $carrito = CarritoDAO::obtenerCarrito($usuario_id);
    echo json_encode($carrito);
    exit();
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $prod_id = intval($_GET['eliminar']);
    CarritoDAO::eliminarProducto($usuario_id, $prod_id);
    $mensaje = 'Producto eliminado del carrito.';
}
// Finalizar compra
if (isset($_POST['finalizar'])) {
    $carrito = CarritoDAO::obtenerCarrito($usuario_id);
    if ($carrito) {
        try {
            VentaDAO::registrarVenta($usuario_id, $carrito);
            CarritoDAO::vaciarCarrito($usuario_id);
            $mensaje = '¡Compra realizada con éxito!';
        } catch (Exception $e) {
            $mensaje = 'Error al procesar la compra: ' . $e->getMessage();
        }
    } else {
        $mensaje = 'El carrito está vacío.';
    }
}
$carrito = CarritoDAO::obtenerCarrito($usuario_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/cart.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="../js/cart.js" defer></script>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario_id']) ? json_encode($_SESSION['usuario_id']) : 'null'; ?>;
    </script>
    <style>
        header { background: #181818; box-shadow: none; border-bottom: 1.5px solid #232323; }
        .header-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 1.2rem 2rem; }
        .logo { font-size:2rem; color:#44ff99; font-weight:700; letter-spacing:1px; cursor:pointer; margin-right:2rem; }
        nav ul { display: flex; gap: 1.2rem; align-items: center; list-style: none; }
        nav ul li { display: flex; align-items: center; }
        nav ul li a, nav ul li span { font-size: 1rem; }
    </style>
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
                        <li class="user-menu">
                            <button class="user-avatar-btn" id="user-avatar-btn">
                                <?php
                                require_once '../datos/conexion.php';
                                $stmt = $pdo->prepare('SELECT foto_perfil FROM usuarios WHERE id = ?');
                                $stmt->execute([$_SESSION['usuario_id']]);
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
    <main style="max-width: 900px; margin: 0 auto;">
        <h2 style="color:#44ff99;text-align:center;margin-bottom:2rem;">Tu Carrito</h2>
        <?php if ($mensaje): ?>
            <div style="background:#232323;color:#44ff99;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <div id="carrito-lista" class="carrito-lista">
            <?php if (empty($carrito)): ?>
                <p>Tu carrito está vacío.</p>
            <?php else: ?>
                <?php $subtotal = 0; ?>
                <?php foreach ($carrito as $item): ?>
                    <?php $subtotal += $item['precio'] * $item['cantidad']; ?>
                    <div class="item-carrito">
                        <img src="../assets/images/<?php echo htmlspecialchars($item['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                        <div style="flex:1;margin-left:1rem;">
                            <p style="font-weight:600;"> <?php echo htmlspecialchars($item['nombre']); ?> </p>
                            <p>Cantidad: <?php echo $item['cantidad']; ?> | Stock: <?php echo $item['stock']; ?></p>
                            <p>Precio: $<?php echo number_format($item['precio'],2); ?></p>
                        </div>
                        <form method="get" action="cart.php" style="margin-left:1rem;">
                            <input type="hidden" name="eliminar" value="<?php echo $item['producto_id']; ?>">
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="resumen" id="resumen">
            <?php if (!empty($carrito)): ?>
                <?php $envio = 10.00; $total = $subtotal + $envio; ?>
                <p>Subtotal: $<?php echo number_format($subtotal,2); ?></p>
                <p>Envío: $<?php echo number_format($envio,2); ?></p>
                <p>Total: $<?php echo number_format($total,2); ?></p>
                <form method="post" action="cart.php">
                    <button type="submit" name="finalizar" class="btn-primary">Finalizar compra</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>