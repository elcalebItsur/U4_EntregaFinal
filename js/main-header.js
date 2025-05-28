// main-header.js

document.addEventListener('DOMContentLoaded', function() {
    var avatarBtn = document.getElementById('user-avatar-btn');
    var userModal = document.getElementById('user-modal');
    // Asegúrate de que el modal NO se muestre al cargar la página
    if (userModal) userModal.style.display = 'none';
    // Permitir abrir el modal solo al hacer clic en el avatar o en el div.avatar-inicial.user-modal-avatar
    function openUserModal(e) {
        e.preventDefault();
        if (userModal) userModal.style.display = 'flex';
    }
    if (avatarBtn && userModal) {
        avatarBtn.onclick = openUserModal;
        // También para el div.avatar-inicial.user-modal-avatar
        var avatarInicial = document.querySelector('.avatar-inicial.user-modal-avatar');
        if (avatarInicial) {
            avatarInicial.onclick = openUserModal;
            avatarInicial.style.cursor = 'pointer';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') userModal.style.display = 'none';
        });
        userModal.addEventListener('click', function(e) {
            if (e.target === userModal) userModal.style.display = 'none';
        });
        var closeBtn = document.querySelector('.user-modal-close');
        if (closeBtn) closeBtn.onclick = function() { userModal.style.display = 'none'; };
    }
});
