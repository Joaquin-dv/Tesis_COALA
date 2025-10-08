document.addEventListener("DOMContentLoaded", () => {
  const visor = document.getElementById("visor");
  const descargar = document.getElementById("descargar");

  // 1️⃣ Leemos los parámetros GET
  const params = new URLSearchParams(window.location.search);
  const alumno = params.get("alumno"); // ejemplo: "nahuel"
  const apunte = params.get("apunte"); // ejemplo: "tesis.pdf"

  // 2️⃣ Si tenemos alumno y apunte, construimos la ruta
  if (alumno && apunte) {
    const archivo = `data/${alumno}/${apunte}`; // Ruta: data/Nahuel/tesis.pdf
    mostrarArchivo(archivo);
  } else {
    visor.innerHTML = "<p>⚠️ No se especificó ningún apunte para mostrar.</p>";
  }

  // 3️⃣ Función que muestra el archivo según el tipo
  function mostrarArchivo(archivo) {
    const ext = archivo.split('.').pop().toLowerCase();
    const esLocal = window.location.protocol === "file:";

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
  }
});
