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
        return false;
    }
    return true;
}
function mostrarCamposVendedor() {
    var tipo = document.getElementById('tipo').value;
    document.getElementById('campos-vendedor').style.display = tipo === 'Vendedor' ? 'block' : 'none';
    document.getElementById('campos-comprador').style.display = tipo === 'Comprador' ? 'block' : 'none';
}
document.getElementById('tipo').addEventListener('change', mostrarCamposVendedor);
window.onload = mostrarCamposVendedor;
