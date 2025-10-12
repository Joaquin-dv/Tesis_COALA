<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/modal.css">
    <link rel="stylesheet" href="views/static/css/mochila.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <title>COALA</title>
</head>

<body class="josefin-sans-normal">
    @extends(appHeader)

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

        <section class="tabs">
            <button class="btn_tab poppins-semibold" onclick="mostrarSeccion('todos')">Todos</button>
            <button class="btn_tab poppins-semibold" onclick="mostrarSeccion('favoritos')">Favoritos</button>
            <button class="btn_tab poppins-semibold" onclick="mostrarSeccion('pendientes')">En revision</button>
            <button class="btn_tab poppins-semibold" onclick="mostrarSeccion('aprobados')">Aprobados</button>
            <button class="btn_tab poppins-semibold" onclick="mostrarSeccion('rechazados')">Rechazados</button>
        </section>

        <div id="favoritos" class="bloque_apuntes">
            <h2 class="vistos_recientemente">Apuntes favoritos</h2>
            <section class="contenedor_apuntes">
                {{ APUNTES_FAVORITOS }}
            </section>
        </div>

        <div id="aprobados"class="bloque_apuntes">
        <h3>Aprobados</h3>
            <section class="contenedor_apuntes">
                {{ APUNTES_APROBADOS }}
            </section>
        </div>

        <div id="pendientes" class="bloque_apuntes">
            <h3>En revision</h3>
            <section class="contenedor_apuntes">
                {{ APUNTES_EN_REVISION }}
            </section>
        </div>

        <div id="rechazados" class="bloque_apuntes">
            <h3>Rechazados</h3>
            <section class="contenedor_apuntes">
                {{ APUNTES_RECHAZADOS }}
            </section>
        </div>
        @extends(modalSubirApunte)
    </main>
    <!-- <script src="views/static/js/modal.js"></script> -->
    <script src="views/static/js/mochila.js"></script>
</body>

</html>