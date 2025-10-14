// Menu desplegable del perfil
document.addEventListener("DOMContentLoaded", () => {
    const perfilImg = document.getElementById("perfil-img");
    const menu = document.getElementById("menu-desplegable");

    perfilImg.addEventListener("click", () => {
        menu.classList.toggle("oculto");
    });

    // Cierra el menú si se hace clic fuera
    document.addEventListener("click", (e) => {
        if (!perfilImg.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add("oculto");
        }
    });
});

// Función para verificar si es usuario demo
function checkDemoUser() {
    // Verificar si existe la sesión y si el rol es demo
    // Nota: En PHP, la sesión se pasa a JS a través de una variable global
    // Asumiendo que se define window.userRole en algún lugar del PHP
    if (typeof window.userRole !== 'undefined' && window.userRole === 'demo') {
        // Mostrar mensaje de que debe crear cuenta
        Swal.fire({
            title: 'Funcionalidad no disponible',
            text: 'Para acceder a esta funcionalidad, debes crear una cuenta.',
            icon: 'info',
            confirmButtonText: 'Crear cuenta',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?slug=registerButton';
            }
        });
        return false; // Previene la acción
    }
    return true; // Permite la acción
}
