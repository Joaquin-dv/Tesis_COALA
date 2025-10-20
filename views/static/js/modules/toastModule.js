    // Detectar si es vista móvil
    const isMobile = window.innerWidth <= 768;

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

export function exito(onClose) {
    Swal.fire({
        icon: "success",
        text: "Apunte subido con éxito",
        showCloseButton: true,
        showConfirmButton: false,
        topLayer: true,
        timer: 8000,
        timerProgressBar: true,
        didClose: () => {
            if (onClose && typeof onClose === 'function') {
                onClose();
            }
        }
    });
}

export function aprobado() {
    Swal.fire({
        icon: "success",
        text: "El apunte ha sido aprobado",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: isMobile ? 'top-right' : 'bottom-right',
        toast: true
    });
}

export function rechazado() {
    Swal.fire({
        icon: "error",
        text: "El apunte ha sido rechazado",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: isMobile ? 'top-right' : 'bottom-right',
        toast: true
    });
}

export function procesando() {
    return Swal.fire({
        icon: "info",
        text: "Procesando apunte con IA...",
        showCloseButton: false,
        showConfirmButton: false,
        position: isMobile ? 'top-right' : 'bottom-right',
        toast: true,
        timer: false, // No se cierra automáticamente
        customClass: {
            popup: 'processing-toast'
        }
    });
}

export function cerrarProcesando() {
    // Cerrar el toast de procesamiento
    Swal.close();
}

export function favoritoAgregado() {
    Swal.fire({
        icon: "success",
        text: "Apunte agregado a favoritos",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: isMobile ? 'top-right' : 'bottom-right',
        toast: true
    });
}

export function favoritoRemovido() {
    Swal.fire({
        icon: "info",
        text: "Apunte removido de favoritos",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: isMobile ? 'top-right' : 'bottom-right',
        toast: true
    });
}
