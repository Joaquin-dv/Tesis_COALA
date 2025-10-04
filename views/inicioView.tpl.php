<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/views/static/css/inicio.css">
    <link rel="stylesheet" href="/views/static/css/modal.css">
    <link rel="stylesheet" href="/views/static/css/style_apunte.css">
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

        <h2 class="vistos_recientemente">Subidos recientemente</h2>
        <section class="contenedor_apuntes">
            {{ VISTOS_RECIENTEMENTE }}
        </section>
        <h2 class="para_ti">Para ti</h2>
        <section class="contenedor_apuntes">
            {{ PARA_TI }}
        </section>
        {{ MODAL_SUBIR_APUNTE }}
    </main>
    <script src="views/static/js/modal.js"></script>
    <script src="views/static/js/validacionFormulario.js"></script>
    <script src="views/static/js/generalesScript.js"></script>
</body>

</html>