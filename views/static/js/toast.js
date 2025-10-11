function cargando() {
    swal.fire({
        html: `
        <section class="imagen_cargando">
            <img src="views/static/img/branding/logo_koala.png" class="img_carga">
        </section>
        <div class="lds-ellipsis"><div></div><div></div><div></div></div>`,
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            popup: 'popup-cargando'
        },

    });
}

function error(){
    Swal.fire({
    icon: "error",
    text: "Hubo un problema al subir el apunte",
    showCloseButton: true,
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
});     
}

function exito(){
    Swal.fire({
    icon: "success",
    text:  "Apunte subido con exito",
    showCloseButton: true,
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar:false, 
});     
}

function simulacion() {
    cargando();
    setTimeout(() => {
        Swal.close();
        exito();
    }, 2000);
}

function aprobado() {
    Swal.fire({
        icon: "success",
        text: "El apunte ha sido aprobado",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: 'bottom-right',
        toast: true
    });
}

function rechazado() {
    Swal.fire({
        icon: "error",
        text: "El apunte ha sido rechazado",
        showCloseButton: true,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        position: 'bottom-right',
        toast: true
    });
}
