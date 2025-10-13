document.addEventListener("DOMContentLoaded", () => {
  const visor = document.getElementById("visor");
  const descargar = document.getElementById("descargar");

  // 1️⃣ Leemos los parámetros GET
  const params = new URLSearchParams(window.location.search);
  const apunteId = params.get("apunteId");

  // 2️⃣ Obtener la ruta del archivo y error desde el atributo data del div visor
  const rutaArchivo = visor.getAttribute('data-ruta');
  const errorArchivo = visor.getAttribute('data-error');

  // 3️⃣ Si tenemos apunteId, verificamos si hay error o ruta
  if (apunteId) {
    if (errorArchivo) {
      // Mostrar mensaje de error
      visor.innerHTML = `<p>⚠️ ${errorArchivo}</p>`;
      descargar.style.display = 'none';
    } else if (rutaArchivo) {
      // Mostrar el archivo
      console.log("Ruta del apunte desde data attribute:", rutaArchivo);
      mostrarArchivo(rutaArchivo);
      // mostrarArchivo("/data/uploads/9400f1b21cb527d7fa3d3eabba93557a18ebe7a2ca4e471cfe5e4c5b4ca7f767/68eb2703a22977.54056782_CamScanner_10-08-2025_18.36.pdf");
    } else {
      // Fallback: intentar obtener vía API si no hay ruta en data attribute
      obtenerRutaApunte(apunteId).then(data => {
        if (data["errno"] == 404) {
          visor.innerHTML = "<p>⚠️ No se encontró el apunte solicitado.</p>";
        } else {
          const ruta = data;
          console.log(ruta);
          const archivo = `${ruta}`;
          mostrarArchivo(archivo);
        }
      }).catch(err => {
        console.error("Error al obtener la ruta del apunte:", err);
        visor.innerHTML = "<p>Error al cargar el apunte.</p>";
      });
    }
  } else {
    visor.innerHTML = "<p>⚠️ No se especificó ningún apunte para mostrar.</p>";
  }

  // 3️⃣ Función que muestra el archivo según el tipo
  async function mostrarArchivo(archivo) {
    const ext = archivo.split('.').pop().toLowerCase();
    const esLocal = window.location.protocol === "file:";

    // Verificar si el archivo existe antes de intentar mostrarlo
    try {
      const response = await fetch(archivo, { method: 'HEAD' });
      if (!response.ok) {
        throw new Error('Archivo no encontrado');
      }
    } catch (error) {
      visor.innerHTML = "<p>⚠️ No se encontró el apunte solicitado.</p>";
      descargar.style.display = 'none';
      return;
    }

    descargar.href = archivo;
    descargar.style.display = 'inline-block';

    let contenido = '';

    if (ext === 'pdf') {
      contenido = `<embed src="${archivo}" type="application/pdf" width="100%" height="100%">`;
    }
    else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(ext)) {
      if (esLocal) {
        contenido = `<p style="padding:1rem;">⚠️ No se puede previsualizar este tipo de archivo en local.<br>Probalo cuando subas el proyecto al servidor.</p>`;
      } else {
        const url = `${window.location.origin}/${archivo}`;
        contenido = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}" width="100%" height="100%" frameborder="0"></iframe>`;
      }
    }
    else if (['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext)) {
      contenido = `<img src="${archivo}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
    }
    else if (['txt', 'csv'].includes(ext)) {
      fetch(archivo)
        .then(res => res.text())
        .then(texto => visor.innerHTML = `<pre style="padding:1rem; text-align:left;">${texto}</pre>`)
        .catch(() => visor.innerHTML = `<p>Error al cargar el archivo.</p>`);
      return;
    }
    else {
      contenido = `<p>⚠️ Este tipo de archivo no se puede previsualizar.</p>`;
    }

    visor.innerHTML = contenido;
  }
});

async function obtenerRutaApunte(apunteId) {
    const response = await fetch(`api/index.php?model=Apuntes&method=getRutaApunteById&apunte_id=${apunteId}`);
    const data = await response.json();
    return data;
}

// Función para manejar el envío de comentarios
document.addEventListener("DOMContentLoaded", () => {
    const formComentario = document.getElementById("form-comentario");
    const inputComentario = document.getElementById("txt-comentario");

    if (formComentario) {
        formComentario.addEventListener("submit", async (e) => {
            e.preventDefault();

            const comentario = inputComentario.value.trim();
            if (!comentario) {
                alert("Por favor, escribe un comentario.");
                return;
            }

            try {
                const formData = new FormData();
                formData.append('comentario', comentario);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    // Limpiar el input
                    inputComentario.value = '';

                    // Recargar la página para mostrar el nuevo comentario
                    window.location.reload();
                } else {
                    alert("Error al enviar el comentario. Inténtalo de nuevo.");
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Error al enviar el comentario. Inténtalo de nuevo.");
            }
        });
    }
});

// Función para manejar el corazón de favoritos
document.addEventListener("DOMContentLoaded", () => {
    const corazon = document.querySelector(".corazon");

    if (corazon) {
        // Obtener el apunteId de la URL
        const params = new URLSearchParams(window.location.search);
        const apunteId = params.get("apunteId");

        if (apunteId) {
            corazon.addEventListener("click", async () => {
                try {
                    const formData = new FormData();
                    formData.append('model', 'Apuntes');
                    formData.append('method', 'toggleFavorito');
                    formData.append('apunte_id', apunteId);

                    const response = await fetch('api/index.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.errno === 200) {
                        // Importar y mostrar el toast correspondiente
                        import('./modules/toastModule.js').then(module => {
                            if (data.activo) {
                                module.favoritoAgregado();
                                corazon.classList.add("favorito-activo");
                            } else {
                                module.favoritoRemovido();
                                corazon.classList.remove("favorito-activo");
                            }
                        });
                    } else if (data.errno === 400) {
                        // Mostrar mensaje específico para apuntes no aprobados
                        import('./modules/toastModule.js').then(module => {
                            // Crear un toast personalizado con el mensaje del servidor
                            Swal.fire({
                                icon: "warning",
                                text: data.error,
                                showCloseButton: true,
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: false,
                                position: 'bottom-right',
                                toast: true
                            });
                        });
                    } else {
                        alert("Error al cambiar favorito: " + data.error);
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert("Error al cambiar favorito. Inténtalo de nuevo.");
                }
            });
        }
    }
});