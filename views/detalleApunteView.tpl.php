<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/detalleApunte.css">
    <link rel="stylesheet" href="views/static/css/general/generales.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <title>COALA</title>
</head>
<body class="josefin-sans-normal">
    @extends(appHeader)
    <script>
        // Pasar el rol del usuario a JavaScript
        window.userRole = '{{ USER_ROLE }}';
        window.puntuacionUsuario = {{ PUNTUACION_USUARIO }};
    </script>
    <main>
        <div class="contenedor_volver">
            <a href="javascript:history.back()" class="btn_volver"><i class="fa-solid fa-xmark"></i></a>
        </div>

        <section class="contenedor_detalle_apunte">
            <section class="informacion_apunte">
                <div class="primer_linea">
                    <div>
                        <h1>{{ TITULO }}</h1>
                    </div>
                    <div>
                        <p class="puntuacion_apunte">⭐{{ PROMEDIO_CALIFICACIONES }}/5 ({{ CANTIDAD_PUNTUACIONES }} valoraciones)</p>
                    </div>
                    <i class="fa-solid fa-heart corazon {{ ES_FAVORITO }}" onclick="return checkDemoUser()"></i>





                </div>
                <div class="segunda_linea">
                    <div>
                        <p>
                            <strong>Autor:</strong> {{ NOMBRE_AUTOR }}
                        </p>
                    </div>
                    <div>
                        <p>
                            <strong>Materia:</strong> {{ MATERIA }}
                        </p>
                    </div>
                    <!-- <div>
                        <p>
                            <strong>Profesor:</strong> Chamorro
                        </p>
                    </div> -->
                </div>
                <div class="tercer_linea">
                    <div>
                        <p>
                            <strong>Subido el:</strong> {{ FECHA_CREACION }}
                        </p>
                    </div>
                </div>

                <div class="tercer_linea">
                    <div>
                        <p>
                            <strong>Descripcion:</strong> {{ DESCRIPCION }}
                        </p>
                    </div>
                </div>
            </section>


            <section class="puntuacion_apunte">
                <span class="texto_puntuacion">¿Este apunte te fue útil?</span>
               <div class="rating" onclick="return checkDemoUser()">
                   <i class="fa-solid fa-star" data-value="1"></i>
                   <i class="fa-solid fa-star" data-value="2"></i>
                   <i class="fa-solid fa-star" data-value="3"></i>
                   <i class="fa-solid fa-star" data-value="4"></i>
                   <i class="fa-solid fa-star" data-value="5"></i>
               </div>
               <button type="button" id="reset-rating" class="reset-rating-btn" style="display: none;" onclick="return checkDemoUser()">Limpiar puntuación</button>

                <input type="hidden" id="rating-value" name="rating" value="{{ PUNTUACION_USUARIO }}">
            </section>

        </section>
        <section class="botones_admin">
            <!-- <button class="boton_eliminar_admin">Eliminar</button>
            <button class="boton_aprobar_admin">Aprobar</button> -->
        </section>

        <section class="contenedor_archivos_apunte">
            <div id="visor" class="visor-archivo" data-ruta="{{ RUTA_ARCHIVO }}" data-error="{{ ERROR_ARCHIVO }}"></div>
            <section class="contenedor_botones_bajo_visor">
                {{ BOTON_REVISION }}
                <a id="descargar" href="#" download onclick="return checkDemoUser()">Descargar archivo</a>
            </section>
        </section>
        <!-- <section class="contenedor_botones_detalle_apunte">

            <button class="btn_reportar" id="btnAbrirPopup" onclick="return checkDemoUser()">Reportar</button>

        </section> -->
        <section class="contenedor_comentarios_apunte">
            <p class="titulo_comentarios_apunte">
                <i class="fa-solid fa-user-group"></i>
                <span>Comentarios del apunte</span>
            </p>



            <form id="form-comentario" method="POST" onsubmit="return checkDemoUser()">
                <div class="input-group">
                    <input required="" type="text" name="comentario" id="txt-comentario" autocomplete="off" class="input" onclick="checkDemoUser()">
                    <label class="user-label">Deje un comentario</label>
                    <button type="submit" class="send-btn" onclick="return checkDemoUser()"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </form>
            <section class="contenedor_comentarios">
                {{ COMENTARIOS_APUNTE }}
            </section>
        </section>





<div class="popup_reporte" id="popupReporte">
  <div class="popup_contenido">
    <div class="popup_icono">
      <i class="fa-solid fa-flag"></i>
    </div>

    <h2 class="popup_titulo">Elige la opción que mejor describa el problema</h2>
    <p class="popup_subtitulo">
      Denuncia contenido que infrinja las normas de COALA.
    </p>
    <p class="popup_texto">
      Revisaremos cada reporte para mantener la comunidad segura y de calidad.
    </p>

    <form id="formReporte">
      <label><input type="radio" name="motivo" value="spam" required> Spam</label>
      <label><input type="radio" name="motivo" value="sensible"> Contenido sensible</label>
      <label><input type="radio" name="motivo" value="danino"> Información falsa o dañina</label>
      <label><input type="radio" name="motivo" value="mal_ubicado"> Contenido mal categorizado</label>

      <div class="acciones_popup">
        <button type="button" id="btnCancelar" class="btn_cancelar">Cancelar</button>
        <button type="submit" class="btn_siguiente">Siguiente</button>
      </div>
    </form>
  </div>
</div>



    @extends(mobile_nav)

    </main>

    <script>
        // Pasar el rol del usuario a JavaScript
        window.userRole = '{{ USER_ROLE }}';
    </script>
    <script src="views/static/js/puntuarApunte.js"></script>
    <script type="module" src="views/static/js/detalleApunte.js"></script>
    <script src="views/static/js/generalesScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="views/static/js/modules/toastModule.js"></script>
</body>

</html>