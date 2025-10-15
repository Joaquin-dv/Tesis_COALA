document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('.toggle-password');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const input = toggle.previousElementSibling;
            const esPassword = input.type === 'password';
            input.type = esPassword ? 'text' : 'password';

            // Cambia el ícono
            toggle.innerHTML = esPassword
                ? '<i class="fa-solid fa-eye"></i>'
                : '<i class="fa-solid fa-eye-slash"></i>';
        });
    });
});
