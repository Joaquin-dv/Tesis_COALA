<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../inicio/css/inicio.css">
    <link rel="stylesheet" href="./css/explorar.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <title>Explorar</title>
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
        <section class="barra_superior">
            <nav class="sidebar_lateral">
                <input type="checkbox" id="sidebar_activo">
                <label for="sidebar_activo" class="abrir_sidebar">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <div class="escuelas_materias">
                    <label for="sidebar_activo" class="cerrar_sidebar">
                        <i class="fa-solid fa-x"></i>
                    </label>

                    <span class="escuela">Roberto Arlt</span>
                    <a href="#" class="materia">Literatura</a>
                    <a href="#" class="materia">Matemática</a>
                    <a href="#" class="materia">Historia</a>

                    <span class="escuela">Fátima</span>
                    <a href="#" class="materia">Literatura</a>
                    <a href="#" class="materia">Matemática</a>
                    <a href="#" class="materia">Historia</a>
                </div>
            </nav>
            <section class="buscador_subir_apunte">
                <div class="contenedor_buscador">
                    <input type="text" placeholder="Buscar..." class="input_buscador">
                </div>
            </section>
        </section>
        <section class="contenido">
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
    </main>

</body>

</html>