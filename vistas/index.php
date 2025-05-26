<?php
// ...existing code...
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
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null'; ?>;
    </script>
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
    <?php if (isset($_SESSION['usuario'])): ?>
    <div id="user-modal" class="user-modal" style="display:none;">
        <div class="user-modal-content">
            <button class="user-modal-close" onclick="document.getElementById('user-modal').style.display='none'">&times;</button>
            <h3>Mi Cuenta</h3>
            <ul class="user-menu-list">
                <li><a href="#" onclick="mostrarSeccionUsuario('favoritos');return false;"><i class="fa fa-heart"></i> Favoritos</a></li>
                <li><a href="#" onclick="mostrarSeccionUsuario('compras');return false;"><i class="fa fa-box"></i> Compras realizadas</a></li>
                <li><a href="#" onclick="mostrarSeccionUsuario('perfil');return false;"><i class="fa fa-user"></i> Mi perfil</a></li>
            </ul>
            <div id="user-favoritos" class="user-section" style="display:none;"></div>
            <div id="user-compras" class="user-section" style="display:none;"></div>
            <div id="user-perfil" class="user-section" style="display:none;"></div>
            <a href="../logout.php" class="btn-secondary" style="margin-top:1.5rem;">Cerrar sesión</a>
        </div>
    </div>
    <style>
    .user-section { margin-top: 1.5rem; }
    .user-section h4 { color: #44ff99; margin-bottom: 1rem; }
    .user-section .mini-lista { display: flex; flex-wrap: wrap; gap: 1rem; }
    .user-section .mini-card { background: #181818; border-radius: 8px; padding: 0.7rem 1rem; color: #fff; min-width: 120px; box-shadow: 0 2px 8px #0002; display: flex; flex-direction: column; align-items: center; }
    .user-section .mini-card img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem; }
    .user-section .mini-card span { font-size: 0.95rem; text-align: center; }
    </style>
    <script>
    function mostrarSeccionUsuario(seccion) {
        document.querySelectorAll('.user-section').forEach(s => s.style.display = 'none');
        if (seccion === 'favoritos') {
            renderFavoritosUsuario();
            document.getElementById('user-favoritos').style.display = 'block';
        } else if (seccion === 'compras') {
            renderComprasUsuario();
            document.getElementById('user-compras').style.display = 'block';
        } else if (seccion === 'perfil') {
            renderPerfilUsuario();
            document.getElementById('user-perfil').style.display = 'block';
        }
    }
    function renderFavoritosUsuario() {
        const cont = document.getElementById('user-favoritos');
        cont.innerHTML = '<h4>Favoritos</h4>';
        let favoritos = [];
        try {
            favoritos = JSON.parse(localStorage.getItem('favoritos_' + window.usuarioActual)) || [];
        } catch {}
        if (!favoritos.length) {
            cont.innerHTML += '<p style="color:#eab308;">No tienes productos en favoritos.</p>';
            return;
        }
        cont.innerHTML += '<div class="mini-lista">' + favoritos.map(f => `<div class='mini-card'><img src='${f.imagen || '../assets/images/hero_image.jpg'}'><span>${f.nombre}</span></div>`).join('') + '</div>';
    }
    function renderComprasUsuario() {
        const cont = document.getElementById('user-compras');
        cont.innerHTML = '<h4>Compras realizadas</h4>';
        let compras = [];
        try {
            compras = JSON.parse(localStorage.getItem('compras_' + window.usuarioActual)) || [];
        } catch {}
        if (!compras.length) {
            cont.innerHTML += '<p style="color:#eab308;">No tienes compras registradas.</p>';
            return;
        }
        cont.innerHTML += '<div class="mini-lista">' + compras.map(f => `<div class='mini-card'><img src='${f.imagen || '../assets/images/hero_image.jpg'}'><span>${f.nombre}</span></div>`).join('') + '</div>';
    }
    function renderPerfilUsuario() {
        const cont = document.getElementById('user-perfil');
        let nombre = localStorage.getItem('nombre_' + window.usuarioActual) || window.usuarioActual;
        let telefono = localStorage.getItem('telefono_' + window.usuarioActual) || '';
        cont.innerHTML = `<h4>Mi perfil</h4>
            <form id='perfil-form' style='display:flex;flex-direction:column;gap:1rem;'>
                <label>Nombre:<input type='text' id='perfil-nombre' value='${nombre}' style='width:100%;padding:0.4rem;border-radius:6px;border:none;background:#232323;color:#fff;'></label>
                <label>Teléfono:<input type='text' id='perfil-telefono' value='${telefono}' style='width:100%;padding:0.4rem;border-radius:6px;border:none;background:#232323;color:#fff;'></label>
                <button type='submit' class='btn-primary'>Guardar cambios</button>
            </form>
            <div id='perfil-msg' style='margin-top:0.7rem;color:#44ff99;display:none;'>¡Perfil actualizado!</div>`;
        document.getElementById('perfil-form').onsubmit = function(e) {
            e.preventDefault();
            localStorage.setItem('nombre_' + window.usuarioActual, document.getElementById('perfil-nombre').value);
            localStorage.setItem('telefono_' + window.usuarioActual, document.getElementById('perfil-telefono').value);
            document.getElementById('perfil-msg').style.display = 'block';
            setTimeout(()=>{document.getElementById('perfil-msg').style.display = 'none';}, 2000);
        };
    }
    </script>
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