import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Fungsi untuk toggle visibilitas password
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = () => {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    };

    const eyeIcon = document.getElementById('eyeIcon');
    if (eyeIcon) {
        eyeIcon.addEventListener('click', togglePassword);
    }
});