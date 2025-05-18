<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tienda de Ropa</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="../js/index.js" defer></script>
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
        <section class="hero">
            <h2>Descubre tu estilo único</h2>
            <p>Explora las últimas tendencias en moda y encuentra tu look perfecto.</p>
            <a href="#populares" class="btn-primary">Ver Populares</a>
        </section>
        <section class="categorias">
            <h2>Categorías</h2>
            <div class="grid">
                <div class="card">Hombre</div>
                <div class="card">Mujer</div>
                <div class="card">Niños</div>
                <div class="card">Accesorios</div>
            </div>
        </section>
        <section class="populares" id="populares">
            <h2>Más Populares</h2>
            <div class="productos-grid" id="productos-lista"></div>
        </section>
        <section class="temporada">
            <h2>Ropa de Temporada</h2>
            <p>Prepárate para esta temporada con nuestra colección exclusiva.</p>
            <a href="#populares" class="btn-primary">Ver Colección</a>
        </section>
        <section class="vender">
            <h2>¿Eres vendedor?</h2>
            <p>Empieza a vender tus productos en minutos. Es rápido y fácil.</p>
            <a href="about.php" class="btn-primary">Comienza Ahora</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>