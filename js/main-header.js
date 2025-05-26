// main-header.js

document.addEventListener('DOMContentLoaded', function() {
    var avatarBtn = document.getElementById('user-avatar-btn');
    var userModal = document.getElementById('user-modal');
    if (avatarBtn && userModal) {
        avatarBtn.onclick = function(e) {
            e.preventDefault();
            userModal.style.display = 'flex';
        };
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
