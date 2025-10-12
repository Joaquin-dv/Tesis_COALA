const contenedor = document.getElementsByClassName("contenedor_apuntes");
function mostrarSeccion(seccionID) {
    const secciones = document.querySelectorAll('.bloque_apuntes');
    const botones = document.querySelectorAll('.btn_tab');

    secciones.forEach(seccion => {
        seccion.style.display = 'none';
    });

    botones.forEach(boton => {
        boton.classList.remove('active');
    });

    if (seccionID === 'todos') {
        secciones.forEach(seccion => {
            seccion.style.display = 'block';
        });
    } else {
        const seccionMostrar = document.getElementById(seccionID);
        if (seccionMostrar) {
            seccionMostrar.style.display = 'block';
        }
    }

    const botonActivo = document.querySelector(`button[onclick="mostrarSeccion('${seccionID}')"]`);
    if (botonActivo) {
        botonActivo.classList.add('active');
    }
}

window.onload = function() {
    mostrarSeccion('todos');
}

