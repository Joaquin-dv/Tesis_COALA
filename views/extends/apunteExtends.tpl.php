<article class="apunte">
    <figure>
        <img class="imagen_apunte" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvhxLMRUJm6WP5tRsTPhSPKfBeKsoJebAwnQ&s" alt="Imagen de apunte">
        {{ IMAGEN }}
    </figure>
    <section class="informacion">
        <h2>{{ TITULO }}</h2>
        
        <section class="datos_apunte">
            <section class="datos_apunte_izquierda">
                <p class="informacion_apunte">{{ MATERIA }}</p>
                <p class="informacion_apunte">{{ ESCUELA }}</p>
                <p class="informacion_apunte">⭐ {{ PUNTUACION }}</p>

            </section>
            <section class="datos_apunte_derecha">
                <p class="informacion_apunte">{{ AÑO }}</p>
            </section>

        </section>
        <!-- <h2>{{ TITULO }}</h2>
        <p>{{ MATERIA }} - {{ ESCUELA }}</p>
        <p>{{ AÑO }} - {{ PUNTUACION }}</p> -->
    </section>
</article>