<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="../explorar/css/style_apunte.css">
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
            <img src="img/foto_perfil.jpg" alt="foto de perfil">
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

        <h2 class="vistos_recientemente">Vistos recientemente</h2>
        <section class="contenedor_apuntes">
            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Funciones Cuadráticas</h2>
                    <p>Matemática - Orona Nicolas</p>
                    <p>2024-09-02 - 4.3</p>
                </section>
            </article>
            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Leyes de Newton</h2>
                    <p>Física - Isaac Gómez</p>
                    <p>2025 - 4.8</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Realismo mágico</h2>
                    <p>Literatura - María Torres</p>
                    <p>2024 - 4.2</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Constitución Argentina</h2>
                    <p>Ciudadanía - Prof. Salazar</p>
                    <p>2025 - 4.6</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Bases de HTML y CSS</h2>
                    <p>Informática - Lic. Ramírez</p>
                    <p>2025 - 5.0</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Funciones Cuadráticas</h2>
                    <p>Matemática - Orona Nicolas</p>
                    <p>2024-09-02 - 4.3</p>
                </section>
            </article>
            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Leyes de Newton</h2>
                    <p>Física - Isaac Gómez</p>
                    <p>2025 - 4.8</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Realismo mágico</h2>
                    <p>Literatura - María Torres</p>
                    <p>2024 - 4.2</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Constitución Argentina</h2>
                    <p>Ciudadanía - Prof. Salazar</p>
                    <p>2025 - 4.6</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Bases de HTML y CSS</h2>
                    <p>Informática - Lic. Ramírez</p>
                    <p>2025 - 5.0</p>
                </section>
            </article>


        </section>
        <h2 class="para_ti">Para ti</h2>
        <section class="contenedor_apuntes">
            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Funciones Cuadráticas</h2>
                    <p>Matemática - Orona Nicolas</p>
                    <p>2024-09-02 - 4.3</p>
                </section>
            </article>
            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Leyes de Newton</h2>
                    <p>Física - Isaac Gómez</p>
                    <p>2025 - 4.8</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Realismo mágico</h2>
                    <p>Literatura - María Torres</p>
                    <p>2024 - 4.2</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Constitución Argentina</h2>
                    <p>Ciudadanía - Prof. Salazar</p>
                    <p>2025 - 4.6</p>
                </section>
            </article>

            <article class="apunte">
                <figure>

                </figure>
                <section class="informacion">
                    <h2>Bases de HTML y CSS</h2>
                    <p>Informática - Lic. Ramírez</p>
                    <p>2025 - 5.0</p>
                </section>
            </article>

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

        </section>
    </main>
    <script src="js/modal.js"></script>
</body>

</html>