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
    timer: 4000,
    timerProgressBar: true,
});     
}

function exito(){
    Swal.fire({
    icon: "success",
    text:  "Apunte subido con exito",
    showCloseButton: true,
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar:true, 
});     
}