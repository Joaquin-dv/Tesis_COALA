let ultimaPosicionScroll = window.scrollY;
const cartas = document.querySelectorAll(".carta_informacion");

const observer = new IntersectionObserver((entradas) => {
  const direccion = window.scrollY > ultimaPosicionScroll ? "abajo" : "arriba";
  ultimaPosicionScroll = window.scrollY;

  entradas.forEach((entrada) => {
    if (entrada.isIntersecting) {
      if (direccion === "abajo") {
        entrada.target.classList.add("visible");
        entrada.target.classList.remove("hidden");
      }
    } else {
      if (direccion === "arriba") {
        entrada.target.classList.remove("visible");
        entrada.target.classList.add("hidden");
      }
    }
  });
}, {
  threshold: 0.3 
});

cartas.forEach((carta, i) => {
  carta.dataset.side = i % 2 === 0 ? "right" : "left";
  carta.classList.add("hidden");
  observer.observe(carta);
});
