<a href="?slug=detalleApunte&apunteId={{ APUNTE_ID }}" class="enlace_apunte">
    <article class="apunte">
    <figure>
        <img class="imagen_apunte" src="data/thumbnails/tesis_miniaturaa.png" alt="Imagen de apunte">
    </figure>
    <section class="informacion">
        <h2>{{ TITULO }}</h2>
        
        <section class="datos_apunte">
            <section class="datos_apunte_izquierda">
                <p class="informacion_apunte">{{ MATERIA }}</p>
                <p class="informacion_apunte">{{ ESCUELA }}</p>
                <p class="informacion_apunte">{{ NIVEL_CURSO }}° año</p>
                <p class="informacion_apunte">⭐ {{ PUNTUACION }}</p>

            </section>
            <section class="datos_apunte_derecha">
                <p class="informacion_apunte">{{ AÑO }}</p>
            </section>

        </section>
            {{ COMPONENTE_ESTADO }}
        <!-- <h2>{{ TITULO }}</h2>
        <p>{{ MATERIA }} - {{ ESCUELA }}</p>
        <p>{{ AÑO }} - {{ PUNTUACION }}</p> -->
    </section>
</article>
</a>

