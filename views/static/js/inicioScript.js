import { initModal } from './modules/modalSubirApunteModule.js';

document.addEventListener("DOMContentLoaded", function () {
    const contenedor_vistos_recientemente = document.getElementById("contenedor_vistos_recientemente");
    const contenedor_para_ti = document.getElementById("contenedor_para_ti");

    obtenerApuntes(4).then(data => {
        // Recorremos los apuntes de último a primero
        for (let i = data.length - 1; i >= 0; i--) {
            const apunte = data[i];

            // Creamos el HTML de la tarjeta reemplazando los datos
            const tarjetaHTML = `
                <article class="apunte">
                    <figure>
                        <img class="imagen_apunte" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvhxLMRUJm6WP5tRsTPhSPKfBeKsoJebAwnQ&s" alt="Imagen de apunte">
                    </figure>
                    <section class="informacion">
                        <h2>${apunte.TITULO}</h2>
                        <section class="datos_apunte">
                            <section class="datos_apunte_izquierda">
                                <p class="informacion_apunte">${apunte.MATERIA}</p>
                                <p class="informacion_apunte">${apunte.ESCUELA}</p>
                                <p class="informacion_apunte">⭐ ${apunte.PUNTUACION ?? "Sin calificación"}</p>
                            </section>
                            <section class="datos_apunte_derecha">
                                <p class="informacion_apunte">${apunte.AÑO}</p>
                            </section>
                        </section>
                    </section>
                </article>
            `;

            // Insertamos el HTML en el contenedor
            contenedor_vistos_recientemente.innerHTML += tarjetaHTML;
        }
    });

    obtenerApuntes(15).then(data => {
        // Recorremos los apuntes de último a primero
        for (let i = data.length - 1; i >= 0; i--) {
            const apunte = data[i];

            // Creamos el HTML de la tarjeta reemplazando los datos
            const tarjetaHTML = `
                <article class="apunte">
                    <figure>
                        <img class="imagen_apunte" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvhxLMRUJm6WP5tRsTPhSPKfBeKsoJebAwnQ&s" alt="Imagen de apunte">
                    </figure>
                    <section class="informacion">
                        <h2>${apunte.TITULO}</h2>
                        <section class="datos_apunte">
                            <section class="datos_apunte_izquierda">
                                <p class="informacion_apunte">${apunte.MATERIA}</p>
                                <p class="informacion_apunte">${apunte.ESCUELA}</p>
                                <p class="informacion_apunte">⭐ ${apunte.PUNTUACION ?? "Sin calificación"}</p>
                            </section>
                            <section class="datos_apunte_derecha">
                                <p class="informacion_apunte">${apunte.AÑO}</p>
                            </section>
                        </section>
                    </section>
                </article>
            `;

            // Insertamos el HTML en el contenedor
            contenedor_para_ti.innerHTML += tarjetaHTML;
        }
    });

    initModal();
});

async function obtenerApuntes(limit) {
    const response = await fetch(`api?model=Apuntes&method=getApuntes&limit=${limit}`);
    const data = await response.json();
    return data;
}
