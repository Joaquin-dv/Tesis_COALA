<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/explorar.css">
    <link rel="stylesheet" href="views/static/css/style_apunte.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <title>COALA</title>
</head>
<body class="josefin-sans-normal">
    <header>
        <div class="contenedor_logo_secundario">
            <img src="/views/static/img/branding/logo_secundario.png" alt="">
        </div>

        <nav class="barra_navegacion">
            <a href="?slug=inicio">Inicio</a>
            <a href="?slug=explorar">Explorar</a>
            <a href="?slug=mochila">Mochila</a>
            <a href="?slug=clases">Clases</a>
        </nav>


        <section class="perfil">
            <img src="/views/static/img/inicio/foto_perfil.jpg" alt="foto de perfil">
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
            <section class="contenedor_apuntes">
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
                @extends(apunte)
            </section>
    </main>
    
</body>

</html>