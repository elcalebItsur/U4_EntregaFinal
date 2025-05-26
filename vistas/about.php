<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vende con Nosotros - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <style>
        header { background: #181818; box-shadow: none; border-bottom: 1.5px solid #232323; }
        .header-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 1.2rem 2rem; }
        .logo { font-size:2rem; color:#44ff99; font-weight:700; letter-spacing:1px; cursor:pointer; margin-right:2rem; }
        nav ul { display: flex; gap: 1.2rem; align-items: center; list-style: none; }
        nav ul li { display: flex; align-items: center; }
        nav ul li a, nav ul li span { font-size: 1rem; }
        .vender-main { max-width: 700px; margin: 2.5rem auto; background: #1e1e1e; border-radius: 14px; padding: 2.5rem 2rem; box-shadow: 0 4px 16px #0002; }
        .vender-main h2 { color: #44ff99; text-align: center; margin-bottom: 1.5rem; }
        .vender-main ol { margin: 1.5rem 0; }
        .vender-main a.btn-primary { display: block; margin: 2rem auto 0 auto; max-width: 300px; }
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
                        <li><span style="color:#44ff99;">Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span></li>
                        <li><a href="../logout.php" class="btn-secondary">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                        <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
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