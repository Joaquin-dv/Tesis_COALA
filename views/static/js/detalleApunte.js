const selector = document.getElementById("selector");
    const visor = document.getElementById("visor");
    const descargar = document.getElementById("descargar");

    selector.addEventListener("change", () => {
      const archivo = selector.value;
      if (!archivo) {
        visor.innerHTML = "<p>Seleccioná un archivo para verlo</p>";
        return;
      }

      const ext = archivo.split('.').pop().toLowerCase();
      const esLocal = window.location.protocol === "file:"; // detecta si estás en local

      descargar.href = archivo;

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
    });