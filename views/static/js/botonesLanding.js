const botones = document.querySelectorAll(".btn");

// Seleccionamos por ID porque los botones usan data-section="about"/"team"
const about = document.getElementById("about");
const team = document.getElementById("team");

// Al cargar: mostramos solo "about"
about.classList.add("visible");
team.classList.remove("visible");

botones.forEach(boton => {
  boton.addEventListener("click", () => {
    // Cambiar estado del botón activo
    botones.forEach(b => b.classList.remove("active"));
    boton.classList.add("active");

    // Mostrar la sección correspondiente
    const target = boton.getAttribute("data-section");

    if (target === "about") {
      about.classList.add("visible");
      team.classList.remove("visible");
    } else if (target === "team") {
      team.classList.add("visible");
      about.classList.remove("visible");
    }
  });
});



