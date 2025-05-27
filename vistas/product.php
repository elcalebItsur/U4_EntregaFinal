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
                            <i class="fas fa-chevron-down" style="font-size:0.8rem;"></i>
                        </button>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="user-info">
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                <small style="display:block;color:#aaa;"><?php echo htmlspecialchars($_SESSION['email']); ?></small>
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
        <img src="ver_imagen.php?id=1" alt="Camisa Moderna" style="max-width:350px;border-radius:14px;box-shadow:0 4px 16px #0003;" />
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