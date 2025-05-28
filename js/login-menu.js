// Control del menú de usuario para login.php

document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        // Cerrar al hacer clic fuera
        document.addEventListener('click', function() {
            userDropdown.classList.remove('show');
        });
    }
});
