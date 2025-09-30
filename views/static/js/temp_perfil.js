
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
