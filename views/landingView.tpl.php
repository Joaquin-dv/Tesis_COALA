<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/landing.css">
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <title>COALA</title>
</head>

<body class="josefin-sans-normal">
    <header>
        <div class="logo_secundario">
            <img src="views/static/img/branding/logo_secundario.png" alt="">
        </div>

        <section class="eleccion_landing">
            <button class="btn active" data-section="about">¿Qué es COALA?</button>
            <button class="btn" data-section="team">¿Quiénes somos?</button>
        </section>

        <section class="inicios_sesion">
            <a href="?slug=login" class="registrarse">Ingresar</a>
        </section>
    </header>

    <main>

        <!-- ===================== SECCIÓN ABOUT ===================== -->
        <section id="about" class="informacion_de_coala">

            <section class="eslogan">
                <div class="contenedor_logo">
                    <img src="views/static/img/branding/logo_koala.png" alt="">
                </div>
                <div class="eslogan_texto">
                    <span>Lo que sabés, lo compartís.</span>
                    <span>Lo que no, lo descubrís.</span>
                </div>
                <a href="#carta_informacion">
                    <button class="boton_explorar_coala">Explorar COALA</button>
                </a>
            </section>

            <article class="carta_informacion" id="carta_informacion">
                <div class="contenedor_imagen_informacion">
                    <img src="views/static/img/landing/imagen_comunidad.png" alt="">
                </div>
                <section class="informacion">
                    <h2>Comunidad</h2>
                    <p>Una comunidad que aprende unida. Cada apunte compartido es una herramienta más para crecer. Aprendé con otros desde distintos puntos de vista y sin perder ni una hoja más.</p>
                </section>
            </article>

            <article class="carta_informacion">
                <div class="contenedor_imagen_informacion">
                    <img src="views/static/img/landing/imagen_alumnos.png" alt="">
                </div>
                <section class="informacion">
                    <h2>Alumnos</h2>
                    <p>COALA te permite acceder a apuntes de todas las materias y años. Buscá, compartí y calificá apuntes de manera rápida y sencilla. Aprendé de tus compañeros y ayudalos a ellos también.</p>
                </section>
            </article>

            <article class="carta_informacion">
                <div class="contenedor_imagen_informacion">
                    <img src="views/static/img/landing/imagen_profesor.png" alt="">
                </div>
                <section class="informacion">
                    <h2>Profesores</h2>
                    <p>Podés subir tus apuntes y guías de estudio para ayudar a tus estudiantes y recibir retroalimentación de ellos. COALA facilita la interacción educativa y el seguimiento del aprendizaje.</p>
                </section>
            </article>

        </section> <!-- ✅ cierre correcto de ABOUT -->

        <!-- ===================== SECCIÓN TEAM ===================== -->
        <section id="team" class="contenido_team">
            <section class="presentacion_team">
                <h2 class="subtitulo_presentacion_team">¿Quiénes estamos detrás de COALA?</h2>
                <p class="parrafo_presentacion_team">Un grupo de estudiantes apasionados por la educación y la tecnología</p>
            </section>

            <div class="team_cards">
                <div class="team_card">
                    <div class="team_img">
                        <img src="https://via.placeholder.com/150" alt="Creador 1">
                    </div>
                    <h3>Nombre Creador 1</h3>
                    <p>Diseñador UX/UI</p>
                </div>

                <div class="team_card">
                    <div class="team_img">
                        <img src="https://via.placeholder.com/150" alt="Creador 2">
                    </div>
                    <h3>Nombre Creador 2</h3>
                    <p>Programador Frontend</p>
                </div>

                <div class="team_card">
                    <div class="team_img">
                        <img src="https://via.placeholder.com/150" alt="Creador 3">
                    </div>
                    <h3>Nombre Creador 3</h3>
                    <p>Backend & DB</p>
                </div>
            </div>

            <section class="presentacion_team">
                <h2 class="subtitulo_presentacion_team">Somos estudiantes de la Técnica N°3 de Malvinas Argentinas</h2>
                <p class="parrafo_presentacion_team">COALA nació como nuestra tesis, pero lo proyectamos como una herramienta real para mejorar la forma de estudiar y compartir conocimientos</p>
            </section>

            <section class="cta_registrarse">
                <h2>¿Querés sumarte a la comunidad? Tu próximo apunte te espera.</h2>
                <a href="?slug=login" class="registrarse">Registrarme</a>
            </section>
        </section>

    </main>

    <footer>
        <span>&copy; COALA</span>
    </footer>

    <script src="views/static/js/botonesLanding.js"></script>
</body>
</html>
