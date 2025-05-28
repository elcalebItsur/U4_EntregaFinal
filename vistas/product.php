<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Camisa Moderna - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/product.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <!-- Reemplazar solo la parte del header (desde <header> hasta </header>) con este código: -->
<header>
    <div class="header-content">
        <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="about.php">Vende</a></li>
                <li><a href="acerca.php" class="btn-accent">Acerca de</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="user-menu">
                        <button class="user-btn" id="user-menu-btn">
                            <i class="fas fa-user-circle"></i>
                            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            <i class="fas fa-chevron-down chevron-icon"></i>
                        </button>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="user-info">
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                <small class="user-email"> <?php echo htmlspecialchars($_SESSION['email']); ?> </small>
                            </div>
                            <a href="perfil.php"><i class="fas fa-user"></i> Mi perfil</a>
                            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                                <a href="mis_ventas.php"><i class="fas fa-box"></i> Mis ventas</a>
                            <?php else: ?>
                                <a href="mis_compras.php"><i class="fas fa-box"></i> Mis compras</a>
                            <?php endif; ?>
                            <div class="divider"></div>
                            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                    <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
    <main class="vista-producto">
        <img src="ver_imagen.php?id=1" alt="Camisa Moderna" class="producto-imagen" />
        <div class="info">
            <h2>Camisa Polo</h2>
            <p>Descripción del producto. Tallas disponibles: S, M, L, XL.</p>
            <p><strong>$129.99</strong></p>
            <button class="btn-accent">Agregar al carrito</button>
        </div>
        <div class="reseñas">
            <h3>Reseñas</h3>
            <div class="reseña">    
                <p><strong>Usuario1:</strong> Me encanta esta camisa, muy cómoda.</p>
            </div>
            <div class="reseña">    
                <p><strong>Usuario2:</strong> La calidad es excelente, la recomiendo.</p>
            </div>
            <div class="reseña">    
                <p><strong>Usuario3:</strong> El color es diferente al de la foto.</p>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>