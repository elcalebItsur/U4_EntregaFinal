<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../datos/conexion.php';
$stmt = $pdo->prepare('SELECT foto_perfil FROM usuarios WHERE id = ?');
$stmt->execute([$_SESSION['usuario_id']]);
$foto = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TextiSur - Tu Tienda de Ropa</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="../css/main-header.css" />
    <style>
        body { background: #181818; color: #e0e0e0; }
        header { background: #181818; box-shadow: none; border-bottom: 1.5px solid #232323; }
        .header-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 1.2rem 2rem; }
        .logo { font-size:2rem; color:#44ff99; font-weight:700; letter-spacing:1px; cursor:pointer; margin-right:2rem; }
        nav ul { display: flex; gap: 1.2rem; align-items: center; list-style: none; }
        nav ul li { display: flex; align-items: center; }
        nav ul li a, nav ul li span { font-size: 1rem; }
        .hero { background: linear-gradient(135deg, #181818 60%, #232323 100%); border-radius: 18px; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; padding: 2.5rem 2rem; gap:2rem; }
        .hero-text { flex:1; }
        .hero h2 { font-size: 2.5rem; color: #44ff99; margin-bottom:1rem; }
        .hero p { color: #b0b0b0; margin-bottom:1.5rem; }
        .hero-img { flex:1; text-align:right; }
        .hero-img img { max-width: 350px; border-radius: 16px; box-shadow: 0 8px 32px #0004; }
        .categorias .grid { margin-top:1.5rem; }
        .categorias .grid .card { cursor: pointer; transition: box-shadow 0.2s, border 0.2s; border: 1.5px solid #232323; background:#232323; display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:120px; font-size:1.2rem; font-weight:500; color:#44ff99; }
        .categorias .grid .card:hover { box-shadow: 0 4px 16px #44ff9922; border: 1.5px solid #44ff99; background: #181818; }
        .productos-grid { margin-top: 1.5rem; }
        .vender { background: #1e1e1e; border-radius: 12px; margin-top: 2.5rem; padding: 2rem; text-align: center; }
        .vender h2 { color: #44ff99; }
        .vender a { margin-top: 1rem; }
        footer { margin-top: 3rem; background: #232323; border-radius: 10px 10px 0 0; }
        @media (max-width: 900px) {
            .hero { flex-direction: column; text-align:center; }
            .hero-img { text-align:center; margin-top:1.5rem; }
        }
        @media (max-width: 700px) {
            .header-content { flex-direction: column; gap:1rem; padding:1rem; }
            .hero h2 { font-size: 1.5rem; }
        }
        .user-modal {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.45); display: flex; align-items: center; justify-content: center; z-index: 2000;
            animation: fadeIn 0.2s;
        }
        .user-modal-content {
            background: #232323; color: #fff; border-radius: 14px; padding: 2rem 2.5rem; min-width: 320px; box-shadow: 0 8px 32px #0005; position: relative;
            display: flex; flex-direction: column; align-items: center;
        }
        .user-modal-close {
            position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #ff6b6b; font-size: 2rem; cursor: pointer;
        }
        .user-menu-list { list-style: none; padding: 0; margin: 1.5rem 0 0 0; width: 100%; }
        .user-menu-list li { margin-bottom: 1rem; }
        .user-menu-list a { color: #44ff99; text-decoration: none; font-size: 1.1rem; display: flex; align-items: center; gap: 0.7rem; transition: color 0.2s; }
        .user-menu-list a:hover { color: #eab308; }
        .user-section { margin-top: 1.5rem; }
        .user-section h4 { color: #44ff99; margin-bottom: 1rem; }
        .user-section .mini-lista { display: flex; flex-wrap: wrap; gap: 1rem; }
        .user-section .mini-card { background: #181818; border-radius: 8px; padding: 0.7rem 1rem; color: #fff; min-width: 120px; box-shadow: 0 2px 8px #0002; display: flex; flex-direction: column; align-items: center; }
        .user-section .mini-card img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem; }
        .user-section .mini-card span { font-size: 0.95rem; text-align: center; }
    </style>
    <script src="../js/index.js" defer></script>
    <script src="../js/main-header.js" defer></script>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null'; ?>;
    </script>
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
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                            <li><a href="admin_tienda.php" class="btn-primary">Administrar Tienda</a></li>
                        <?php endif; ?>
                        <li class="user-menu">
                            <button class="user-avatar-btn" id="user-avatar-btn">
                                <?php
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
    <div id="user-modal" class="user-modal" style="display:none;">
        <div class="user-modal-content">
            <button class="user-modal-close">&times;</button>
            <div style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
                <?php if ($foto): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" style="width:80px;height:80px;border-radius:50%;object-fit:cover;box-shadow:0 2px 8px #0003;" />
                <?php else: ?>
                    <div style="width:80px;height:80px;border-radius:50%;background:#44ff99;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#181818;font-size:2.2rem;">
                        <?php echo strtoupper(substr($_SESSION['usuario'],0,1)); ?>
                    </div>
                <?php endif; ?>
                <div style="text-align:center;">
                    <div style="font-size:1.2rem;font-weight:600;"> <?php echo htmlspecialchars($_SESSION['usuario']); ?> </div>
                    <div style="color:#aaa;font-size:0.98rem;"> <?php echo htmlspecialchars($_SESSION['email']); ?> </div>
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
    <main style="max-width: 1200px; margin: 0 auto;">
        <section class="hero">
            <div class="hero-text">
                <h2>Descubre tu estilo único</h2>
                <p>Explora las últimas tendencias en moda y encuentra tu look perfecto.</p>
                <a href="#populares" class="btn-primary">Ver Populares</a>
            </div>
            <div class="hero-img">
                <img src="../assets/images/hero_image.jpg" alt="Moda Textisur" loading="lazy">
            </div>
        </section>
        <section class="ofertas" id="ofertas">
            <h2 style="color:#eab308;">Ofertas Especiales</h2>
            <div class="productos-grid" id="ofertas-lista"></div>
        </section>
        <section class="categorias">
            <h2 style="color:#44ff99;">Categorías</h2>
            <div class="grid">
                <div class="card" onclick="alert('Próximamente: Ropa de Hombre')"><i class="fa fa-mars"></i><span>Hombre</span></div>
                <div class="card" onclick="alert('Próximamente: Ropa de Mujer')"><i class="fa fa-venus"></i><span>Mujer</span></div>
                <div class="card" onclick="alert('Próximamente: Ropa para Niños')"><i class="fa fa-child"></i><span>Niños</span></div>
                <div class="card" onclick="alert('Próximamente: Accesorios')"><i class="fa fa-hat-cowboy"></i><span>Accesorios</span></div>
                <div class="card" onclick="alert('Próximamente: Deporte')"><i class="fa fa-futbol"></i><span>Deporte</span></div>
                <div class="card" onclick="alert('Próximamente: Unisex')"><i class="fa fa-user"></i><span>Unisex</span></div>
                <div class="card" onclick="alert('Próximamente: Invierno')"><i class="fa fa-snowflake"></i><span>Invierno</span></div>
                <div class="card" onclick="alert('Próximamente: Oficina')"><i class="fa fa-briefcase"></i><span>Oficina</span></div>
            </div>
        </section>
        <section class="populares" id="populares">
            <h2 style="color:#44ff99;">Más Populares</h2>
            <div class="productos-grid" id="productos-lista"></div>
        </section>
        <section class="temporada">
            <h2 style="color:#44ff99;">Ropa de Temporada</h2>
            <div class="productos-grid" id="temporada-lista"></div>
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