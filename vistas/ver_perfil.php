<?php
session_start();
require_once '../datos/UsuarioDAO.php';
require_once '../datos/ProductoDAO.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
$usuario = UsuarioDAO::obtenerPorId($usuario_id);
$productos = [];
if ($usuario['tipo'] === 'Vendedor') {
    $productos = ProductoDAO::obtenerPorVendedor($usuario_id);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/ver_perfil.css" />
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
                    <?php if ($usuario['tipo'] === 'Vendedor'): ?>
                        <li><a href="admin_tienda.php" class="btn-primary">Administrar Tienda</a></li>
                        <li><a href="mis_ventas.php" class="btn-primary">Mis Ventas</a></li>
                    <?php elseif ($usuario['tipo'] === 'Comprador'): ?>
                        <li><a href="mis_compras.php" class="btn-primary">Mis Compras</a></li>
                    <?php endif; ?>
                    <li class="user-menu">
                        <button class="user-avatar-btn" id="user-avatar-btn">
                            <?php
                            $foto = $usuario['foto_perfil'] ?? null;
                            if ($foto): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" />
                            <?php else: ?>
                                <span class="avatar-inicial"><?php echo strtoupper(substr($usuario['nombre'],0,1)); ?></span>
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
<main class="perfil-main">
    <div class="perfil-header">
        <div class="perfil-avatar">
            <?php if (!empty($usuario['foto_perfil'])): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de perfil" />
            <?php else: ?>
                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div class="perfil-info">
            <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($usuario['tipo']); ?></p>
            <?php if ($usuario['tipo'] === 'Vendedor'): ?>
                <p><strong>Tienda:</strong> <?php echo htmlspecialchars($usuario['nombre_tienda']); ?></p>
                <p><strong>RFC:</strong> <?php echo htmlspecialchars($usuario['rfc']); ?></p>
            <?php else: ?>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($usuario['tipo'] === 'Vendedor'): ?>
        <h3 class="user-modal-warning">Mis productos</h3>
        <div class="productos-grid">
            <?php foreach ($productos as $prod): ?>
                <div class="producto-card">
                    <?php
                    $img = '../assets/images/hero_image.jpg';
                    if (!empty($prod['imagen']) && file_exists(__DIR__ . '/../assets/images/' . $prod['imagen'])) {
                        $img = '../assets/images/' . $prod['imagen'];
                    }
                    ?>
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" />
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                    <div class="precio">$<?php echo number_format($prod['precio'],2); ?></div>
                    <div class="stock-label">Stock: <?php echo htmlspecialchars($prod['stock']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="user-modal-warning">No hay productos para mostrar.</div>
    <?php endif; ?>
</main>
<footer>
    <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
</footer>
</body>
</html>
