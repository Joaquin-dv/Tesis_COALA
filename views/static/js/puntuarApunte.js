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
    // Verificar si es usuario demo antes de puntuar
    if (window.userRole === 'demo') {
      return; // La función checkDemoUser ya muestra el mensaje
    }
    const value = parseInt(star.dataset.value);
    ratingValue.value = value;
    selectStars(value);
    
    // Solo mostrar botón de reset si no tiene puntuación guardada
    const resetBtn = document.getElementById('reset-rating');
    const comentarioInput = document.getElementById('txt-comentario');
    
    if (resetBtn && (!window.puntuacionUsuario || window.puntuacionUsuario === 0)) {
      resetBtn.style.display = 'inline-block';
    }
    
    if (comentarioInput) {
      comentarioInput.placeholder = 'Escribe un comentario para enviar tu puntuación de ' + value + ' estrella' + (value > 1 ? 's' : '');
      comentarioInput.focus();
    }
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

// Función para resetear la puntuación
function resetRating() {
  if (ratingValue) {
    ratingValue.value = 0;
  }
  stars.forEach(star => {
    star.classList.remove("selected");
  });
  const comentarioInput = document.getElementById('txt-comentario');
  const resetBtn = document.getElementById('reset-rating');
  
  if (comentarioInput) {
    comentarioInput.placeholder = 'Deje un comentario';
  }
  if (resetBtn) {
    resetBtn.style.display = 'none';
  }
}





// Inicializar con la puntuación del usuario
document.addEventListener('DOMContentLoaded', function() {
  if (window.puntuacionUsuario && window.puntuacionUsuario > 0) {
    const ratingInput = document.getElementById('rating-value');
    if (ratingInput) {
      ratingInput.value = window.puntuacionUsuario;
      selectStars(window.puntuacionUsuario);
      // No mostrar botón de reset si ya tiene puntuación guardada
    }
  }
});

// Mostrar toast si ya existe comentario
document.addEventListener('DOMContentLoaded', function() {
  if (window.mostrarToastComentario === true) {
    Swal.fire({
      icon: 'warning',
      title: 'Ya comentaste este apunte',
      text: 'Solo puedes comentar una vez por apunte',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  }
});

// Manejar envío del formulario de comentario con puntuación
document.addEventListener('DOMContentLoaded', function() {
  const formComentario = document.getElementById('form-comentario');
  const ratingInput = document.getElementById('rating-value');
  const resetBtn = document.getElementById('reset-rating');
  
  if (formComentario) {
    formComentario.addEventListener('submit', function(e) {
      // Agregar la puntuación al formulario si existe
      if (ratingInput && ratingInput.value > 0) {
        // Remover input previo si existe
        const existingInput = formComentario.querySelector('input[name="puntuacion"]');
        if (existingInput) {
          existingInput.remove();
        }
        
        const puntuacionInput = document.createElement('input');
        puntuacionInput.type = 'hidden';
        puntuacionInput.name = 'puntuacion';
        puntuacionInput.value = ratingInput.value;
        formComentario.appendChild(puntuacionInput);
      }
    });
  }
  
  // Event listener para el botón de reset
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      if (window.userRole !== 'demo') {
        resetRating();
      }
    });
  }
});










document.addEventListener("DOMContentLoaded", () => {
  const btnAbrir = document.getElementById("btnAbrirPopup");
  const popup = document.getElementById("popupReporte");
  const btnCancelar = document.getElementById("btnCancelar");
  const form = document.getElementById("formReporte");

  btnAbrir.addEventListener("click", () => {
    // Verificar si es usuario demo antes de abrir el popup
    if (window.userRole === 'demo') {
      return; // La función checkDemoUser ya muestra el mensaje desde el HTML
    }
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



document.addEventListener("DOMContentLoaded", () => {
    const corazon = document.querySelector('.corazon');

    corazon.addEventListener('click', () => {
        // Verificar si es usuario demo antes de marcar favorito
        if (window.userRole === 'demo') {
          return; // La función checkDemoUser ya muestra el mensaje
        }
        corazon.classList.toggle('favorito');
    });
});
