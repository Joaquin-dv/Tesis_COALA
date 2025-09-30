document.querySelectorAll('#formulario').forEach(form =>{
    form.addEventListener('submit',function (e){
        e.preventDefault();
        let vacio=true;

        const errorGeneral = form.querySelector('#errorGeneral');
            errorGeneral.textContent = "";

        form.querySelectorAll("input[type='text'], input[type='file'], textarea, select").forEach(input =>{
            input.classList.remove('remarcadoError');
            if(input.value === ""){
                vacio=false;
                input.classList.add("remarcadoError");
            }
        });
         if(!vacio){
            errorGeneral.textContent = "Por favor completa todos los campos";
        } else {
            form.submit();
        }
    });
});