<!-- header.php: Encabezado reutilizable para todas las páginas -->
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
