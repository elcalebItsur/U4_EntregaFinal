<?php
// ...existing code...
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
    window.usuarioActual = <?php echo isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null'; ?>;
    </script>
</head>
<body>
    <header>
        <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="favorites.php">Favoritos</a></li>
                <li><a href="cart.php">Carrito</a></li>
                <li><a href="about.php">Vende con Nosotros</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span></li>
                    <li><a href="../logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Tu Carrito</h2>
        <div id="carrito-lista" class="carrito-lista"></div>
        <div id="resumen" class="resumen-carrito"></div>
        <button class="back-button" onclick="location.href='index.php'">Regresar</button>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>