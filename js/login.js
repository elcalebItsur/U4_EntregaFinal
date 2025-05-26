function validarLogin(e) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    let mensaje = '';
    if (!email.value.trim() || !email.value.includes('@')) mensaje += 'Correo inválido.\n';
    if (!password.value.trim()) mensaje += 'La contraseña es obligatoria.\n';
    if (mensaje) {
        alert(mensaje);
        e.preventDefault();
        return false;
    }
    return true;
}
