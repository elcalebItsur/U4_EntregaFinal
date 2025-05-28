<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Acerca de Textisur</title>
	<link rel="stylesheet" href="../css/main.css" />
	<link rel="stylesheet" href="../css/acerca.css" />
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
