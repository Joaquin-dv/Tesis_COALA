export function cargando() {
    Swal.fire({
        text: "Subiendo apunte...",
        html: `
        <section class="imagen_cargando">
            <img src="views/static/img/branding/logo_koala.png" class="img_carga">
        </section>
        <div class="lds-ellipsis"><div></div><div></div><div></div></div>`,
        allowOutsideClick: false,
        showConfirmButton: false,
        topLayer: true,
        customClass: {
            popup: 'popup-cargando'
        },
    });
}

export function error(mensajeError) {
    Swal.fire({
        icon: "error",
        text: `Hubo un problema al subir el apunte. ${mensajeError}`,
        showCloseButton: true,
        showConfirmButton: false,
        topLayer: true,
        timer: 8000,
        timerProgressBar: true,
    });
}

export function exito() {
    Swal.fire({
        icon: "success",
        text: "Apunte subido con éxito",
        showCloseButton: true,
        showConfirmButton: false,
        topLayer: true,
        timer: 8000,
        timerProgressBar: true,
    });
}
