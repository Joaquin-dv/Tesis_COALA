const stars = document.querySelectorAll(".rating i");
const ratingValue = document.getElementById("rating-value");

stars.forEach(star => {
  star.addEventListener("mouseover", () => {
    const value = parseInt(star.dataset.value);
    highlightStars(value);
  });

  star.addEventListener("mouseout", () => {
    resetStars();
  });

  star.addEventListener("click", () => {
    const value = parseInt(star.dataset.value);
    ratingValue.value = value;
    selectStars(value);
  });
});

function highlightStars(value) {
  stars.forEach(star => {
    star.classList.toggle("hovered", star.dataset.value <= value);
  });
}

function resetStars() {
  stars.forEach(star => {
    star.classList.remove("hovered");
  });
}

function selectStars(value) {
  stars.forEach(star => {
    star.classList.toggle("selected", star.dataset.value <= value);
  });
}

// Inicializar con el valor actual si es distinto de 0
if (ratingValue.value != 0) {
  selectStars(parseInt(ratingValue.value));
}










document.addEventListener("DOMContentLoaded", () => {
  const btnAbrir = document.getElementById("btnAbrirPopup");
  const popup = document.getElementById("popupReporte");
  const btnCancelar = document.getElementById("btnCancelar");
  const form = document.getElementById("formReporte");

  btnAbrir.addEventListener("click", () => {
    popup.style.display = "flex";
  });

  btnCancelar.addEventListener("click", () => {
    popup.style.display = "none";
  });

  popup.addEventListener("click", (e) => {
    if (e.target === popup) popup.style.display = "none";
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    popup.style.display = "none";
  });
});
