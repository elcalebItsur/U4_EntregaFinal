<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Acerca de Textisur</title>
	<link rel="stylesheet" href="../css/main.css" />
	<style>
		.acerca-main {
			max-width: 800px;
			margin: 3rem auto;
			background: #1e1e1e;
			border-radius: 14px;
			padding: 2.5rem 2rem;
			box-shadow: 0 4px 16px #0002;
			animation: fadeInUp 0.7s cubic-bezier(.4,2,.3,1);
		}
		.acerca-main h2 { color: #44ff99; text-align: center; margin-bottom: 1.5rem; }
		.acerca-main p { margin-bottom: 1.2rem; color: #e0e0e0; font-size: 1.1rem; }
		.acerca-main ul { margin: 1.5rem 0 0 1.5rem; color: #eab308; }
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
                <li><a href="favorites.php">Favoritos</a></li>
                <li><a href="cart.php">Carrito</a></li>
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
                            <a href="direcciones.php"><i class="fas fa-map-marker-alt"></i> Mis direcciones</a>
                            <a href="pedidos.php"><i class="fas fa-box"></i> Mis pedidos</a>
                            <a href="favorites.php"><i class="fas fa-heart"></i> Favoritos</a>
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
	<main class="acerca-main">
		<h2>Acerca de Textisur</h2>
		<p><b>Textisur</b> es una plataforma creada para conectar tiendas de ropa locales y compradores en la región donde la industria textil es la principal actividad económica.</p>
		<p>Nuestro propósito es impulsar la economía local, facilitar la venta de productos textiles y acercar a los consumidores a la moda regional, promoviendo el talento y la calidad de los productores locales.</p>
		<ul>
			<li>Conecta tiendas y compradores de manera sencilla y segura.</li>
			<li>Ofrece un catálogo variado y actualizado de productos textiles.</li>
			<li>Permite a los vendedores gestionar su tienda y llegar a más clientes.</li>
			<li>Fomenta el consumo local y el desarrollo económico de la región.</li>
		</ul>
		<p>¡Únete a Textisur y sé parte del crecimiento de nuestra industria textil!</p>
	</main>
	<footer>
		<p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
	</footer>
</body>
</html>
