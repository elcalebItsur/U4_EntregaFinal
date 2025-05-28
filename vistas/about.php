<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vende con Nosotros - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/about.css" />
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
                                require_once __DIR__ . '/../datos/conexion.php';
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
    <div id="user-modal" class="user-modal">
        <div class="user-modal-content">
            <button class="user-modal-close">&times;</button>
            <div class="user-modal-avatar-group">
                <?php if ($foto): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" class="user-modal-avatar" />
                <?php else: ?>
                    <div class="avatar-inicial user-modal-avatar">
                        <?php echo strtoupper(substr($_SESSION['usuario'],0,1)); ?>
                    </div>
                <?php endif; ?>
                <div class="user-modal-info">
                    <div class="user-modal-title"> <?php echo htmlspecialchars($_SESSION['usuario']); ?> </div>
                    <div class="user-modal-email"> <?php echo htmlspecialchars($_SESSION['email']); ?> </div>
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
    <main class="vender-main">
        <h2>¿Quieres vender tu ropa?</h2>
        <p>Registrarte como vendedor te da acceso a una audiencia amplia que busca moda única.</p>
        <ol>
            <li>Regístrate como vendedor</li>
            <li>Sube fotos y detalles de tus productos</li>
            <li>Empieza a recibir pedidos</li>
        </ol>
        <p>¡Únete a nuestra comunidad de vendedores y empieza a ganar dinero con tu ropa!</p>
        <p>Si ya tienes una cuenta, puedes iniciar sesión como vendedor.</p>
        <a href="register.php" class="btn-primary">Registrarme como Vendedor</a>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>