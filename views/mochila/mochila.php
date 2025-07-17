<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../inicio/css/inicio.css">
    <link rel="stylesheet" href="../inicio/css/modal.css">
    <link rel="stylesheet" href="css/mochila.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <title>Panel</title>
</head>

<body class="josefin-sans-normal">
    <header>
        <div class="contenedor_logo_secundario">
            <img src="../../assets/img/logo_secundario.png" alt="">
        </div>

        <nav class="barra_navegacion">
            <a href="../inicio/inicio.php">Inicio</a>
            <a href="../explorar/explorar.php">Explorar</a>
            <a href="../mochila/mochila.php">Mochila</a>
            <a href="../clases/clases.php">Clases</a>
        </nav>


        <section class="perfil">
            <img src="../inicio/img/foto_perfil.jpg" alt="foto de perfil">
        </section>
    </header>

    <main>
        <section class="buscador_subir_apunte">
            <div class="contenedor_buscador">
                <h2>¡Hola, Pepe!</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador">
            </div>
            <div class="boton_subir_apunte">
                <button id="abrir_modal">Subir apunte</button>
            </div>
        </section>

        <h2 class="vistos_recientemente">Apuntes favoritos</h2>
        <section class="contenedor_apuntes">
            <article class="apunte_mochila">
                <header class="encabezado_apunte">
                    <section class="info_encabezado_apunte">
                        <h2>Ejercicios de Trigonometría</h2>
                        <p>22/04/2025</p>
                        <p>Matemática - 2° año | Prof. Martínez</p>
                    </section>
                    <figure><img src="https://placehold.co/80?text=Math" alt=""></figure>
                </header>
                <section class="cuerpo_apunte">
                    <p>Incluye ejercicios resueltos sobre razones trigonométricas, gráficos de funciones y teoría básica
                        para repasar.</p>
                    <p>Contiene 5 archivos</p>
                </section>
                <footer>
                    <p>⭐ 4.9 | 👁️ 189</p>
                    <button>Ver más</button>
                </footer>
            </article>

            <article class="apunte_mochila">
                <header class="encabezado_apunte">
                    <section class="info_encabezado_apunte">
                        <h2>Análisis de "El Matadero"</h2>
                        <p>17/03/2025</p>
                        <p>Lengua - 4° año | Prof. Suárez</p>
                    </section>
                    <figure><img src="https://placehold.co/80?text=Libro" alt="Portada de libro"></figure>
                </header>
                <section class="cuerpo_apunte">
                    <p>Apunte con resumen, análisis literario y contexto histórico de la obra de Esteban Echeverría.</p>
                    <p>Contiene 1 archivo</p>
                </section>
                <footer>
                    <p>⭐ 4.6 | 👁️ 78</p>
                    <button>Ver más</button>
                </footer>
            </article>

            <article class="apunte_mochila">
                <header class="encabezado_apunte">
                    <section class="info_encabezado_apunte">
                        <h2>Rutina de entrenamiento semanal</h2>
                        <p>08/06/2025</p>
                        <p>Educación Física - 1° año | Prof. Ledesma</p>
                    </section>
                    <figure><img src="https://placehold.co/80?text=Sport" alt="Icono de deporte"></figure>
                </header>
                <section class="cuerpo_apunte">
                    <p>Incluye ejercicios funcionales, calentamiento, elongación y consejos para entrenar en casa sin
                        materiales.</p>
                    <p>Contiene 3 archivos</p>
                </section>
                <footer>
                    <p>⭐ 4.5 | 👁️ 54</p>
                    <button>Ver más</button>
                </footer>
            </article>


            <article class="apunte_mochila">
                <header class="encabezado_apunte">
                    <section class="info_encabezado_apunte">
                        <h2>Introducción a JavaScript</h2>
                        <p>03/05/2025</p>
                        <p>Programación - 5° año | Prof. Herrera</p>
                    </section>
                    <figure><img src="https://placehold.co/80?text=JS" alt="Logo de JavaScript"></figure>
                </header>
                <section class="cuerpo_apunte">
                    <p>Resumen teórico con ejemplos prácticos de variables, funciones, condicionales y eventos.</p>
                    <p>Contiene 4 archivos</p>
                </section>
                <footer>
                    <p>⭐ 5.0 | 👁️ 210</p>
                    <button>Ver más</button>
                </footer>
            </article>

        </section>

        <h2 class="para_ti">Tus apuntes</h2>
        <h3>Aprobados</h3>
        <section class="contenedor_apuntes">
            <?php
            // Include the component for displaying notes
            for ($i = 0; $i < 4; $i++) {
                include '../mochila/componentes/apunte_mochila.html';
            }
            ?>
        </section>

        <h3>Pendientes</h3>
        <section class="contenedor_apuntes">
            <?php
            // Include the component for displaying notes
            for ($i = 0; $i < 3; $i++) {
                include '../mochila/componentes/apunte_mochila.html';
            }
            ?>
        </section>

        <h3>Rechazados</h3>
        <section class="contenedor_apuntes">
            <?php
            // Include the component for displaying notes
            for ($i = 0; $i < 5; $i++) {
                include '../mochila/componentes/apunte_mochila.html';
            }
            ?>
        </section>

        <dialog id="modal">
                <section class="contenido_modal">
                    <i id="cerrar_modal" class="fa-solid fa-x"></i>
                    <label for="titulo">Titulo</label>
                    <input type="text" id="titulo" placeholder="Titulo" class="campo_modal">
                    <label for="descripcion">Descripcion</label>
                    <input type="text" id="descripcion" placeholder="Descripcion" class="campo_modal">
                    <button id="subir_archivo" class="btn_modal">Agregar archivo</button>
                    <section class="datos_apunte">
                    <div class="input_label">                    
                        <label for="curso">Curso</label>
                        <input type="text" id="curso" placeholder="Curso" class="campo_modal">
                        </div>
                    <div class="input_label">    
                        <label for="division">Division</label>
                        <input type="text" id="division" placeholder="Division" class="campo_modal">
                    </div>
                    <div class="input_label">
                        <label for="materia">Materia</label>
                        <input type="text" id="materia" placeholder="Materia" class="campo_modal">
                    </div>
                    </section>
                    <button id="subir_apunte" class="btn_modal">Subir Apunte</button>

                </section>
        </dialog>

    </main>
<script src="../inicio/js/modal.js"></script>
</body>

</html>