const btn_abrir = document.querySelector ('#abrir_modal');
const btn_cerrar = document.querySelector ('#cerrar_modal');
const modal = document.querySelector ('#modal');

btn_abrir.addEventListener("click",()=>{
    modal.showModal();
})
btn_cerrar.addEventListener("click",()=>{
    modal.close();
})