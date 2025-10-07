<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/views/static/css/inicio.css">
    <link rel="stylesheet" href="/views/static/css/modal.css">
    <link rel="stylesheet" href="/views/static/css/toast.css">
    <link rel="stylesheet" href="/views/static/css/style_apunte.css">
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
            <img id="perfil-img" src="/views/static/img/inicio/foto_perfil.jpg" alt="foto de perfil">
            <ul id="menu-desplegable" class="oculto">
                <li><a href="?slug=perfil">Perfil</a></li>
                <li><a href="?slug=logout">Cerrar sesión</a></li>
            </ul>
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

        <h2 class="vistos_recientemente">Subidos recientemente</h2>
        <section class="contenedor_apuntes">
            <!-- @extends(apuntes_vistos_recientemente) -->
            {{ VISTOS_RECIENTEMENTE }}
        </section>
        <h2 class="para_ti">Para ti</h2>
        <section class="contenedor_apuntes">
            {{ PARA_TI }}
        </section>
        <!-- @extends(modalSubirApunte) -->
        {{ MODAL_SUBIR_APUNTE }}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="views/static/js/toast.js"></script>
    <script src="views/static/js/modal.js"></script>
    <script src="views/static/js/validacionFormulario.js"></script>
    <script src="views/static/js/temp_perfil.js"></script>
</body>

</html>