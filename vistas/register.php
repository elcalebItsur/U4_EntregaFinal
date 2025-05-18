<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/auth.css" />
    <script>
    function validarRegistro(e) {
        const nombre = document.getElementById('nombre');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const telefono = document.getElementById('telefono');
        const fecha = document.getElementById('fecha_nacimiento');
        let mensaje = '';
        if (!nombre.value.trim()) mensaje += 'El nombre es obligatorio.\n';
        if (!email.value.trim() || !email.value.includes('@')) mensaje += 'Correo inválido.\n';
        if (!password.value.trim() || password.value.length < 6) mensaje += 'La contraseña debe tener al menos 6 caracteres.\n';
        if (!telefono.value.trim() || telefono.value.length < 10) mensaje += 'Teléfono inválido.\n';
        if (!fecha.value) mensaje += 'La fecha de nacimiento es obligatoria.\n';
        if (mensaje) {
            alert(mensaje);
            e.preventDefault();
        }
    }
    </script>
</head>
<body>
    <header>
        <h1 class="logo" style="cursor: pointer;" onclick="location.href='../index.php'">Textisur</h1>
    </header>
    <main class="auth-container">
        <h2>Crea tu cuenta</h2>
        <form action="/SistemaComleto/register.php" method="POST" onsubmit="validarRegistro(event)">
            <label>Nombre completo:</label>
            <input type="text" name="nombre" id="nombre" required />
            <label>Correo electrónico:</label>
            <input type="email" name="email" id="email" required />
            <label>Contraseña:</label>
            <input type="password" name="password" id="password" required minlength="6" />
            <label>Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" required pattern="[0-9]{10,15}" />
            <label>Género:</label>
            <select name="genero" id="genero">
                <option value="">Prefiero no decirlo</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required />
            <label>Tipo de usuario:</label>
            <select name="tipo" id="tipo" onchange="mostrarCamposVendedor()">
                <option value="Comprador">Comprador</option>
                <option value="Vendedor">Vendedor</option>
            </select>
            <div id="campos-vendedor" style="display:none">
                <label>Nombre de tienda:</label>
                <input type="text" name="nombre_tienda" id="nombre_tienda" />
                <label>RFC:</label>
                <input type="text" name="rfc" id="rfc" />
            </div>
            <div id="campos-comprador">
                <label>Dirección:</label>
                <input type="text" name="direccion" id="direccion" />
            </div>
            <button class="btn-primary" type="submit">Registrarse</button>
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </form>
        <script>
        function mostrarCamposVendedor() {
            var tipo = document.getElementById('tipo').value;
            document.getElementById('campos-vendedor').style.display = tipo === 'Vendedor' ? 'block' : 'none';
            document.getElementById('campos-comprador').style.display = tipo === 'Comprador' ? 'block' : 'none';
        }
        document.getElementById('tipo').addEventListener('change', mostrarCamposVendedor);
        window.onload = mostrarCamposVendedor;
        </script>
    </main>
</body>
</html>