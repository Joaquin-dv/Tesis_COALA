<article class="comentario_apunte">
    <div class="contenedor_foto_comentario">
        <img src="https://api.dicebear.com/9.x/initials/svg?seed={{ NOMBRE_USUARIO }}" alt="Foto de perfil de {{ NOMBRE_USUARIO }}" />
    </div>

    <div class="contenedor_comentario_derecha">
            <div class="puntuacion-comentario">
                {{ ESTRELLAS }}
            </div>
            <div class="header_comentario">
            <h4 class="nombre_usuario">{{ NOMBRE_USUARIO }}</h4>
            <time class="fecha_creacion_comentario" datetime="2024-06-03">{{ FECHA_CREACION }}</time>
            </div>

        <p class="texto_comentario">
        {{ TEXTO_COMENTARIO }}
        </p>
    </div>
</article>