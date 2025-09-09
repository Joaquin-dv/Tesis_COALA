<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/modal.css">
    <link rel="stylesheet" href="views/static/css/mochila.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <title>Panel</title>
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
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
        </section>
        
        <h2 class="para_ti">Tus apuntes</h2>
        <h3>Aprobados</h3>
        <section class="contenedor_apuntes">
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
        </section>

        <h3>Pendientes</h3>
        <section class="contenedor_apuntes">
            @extends(apunte_mochila)
            @extends(apunte_mochila)
        </section>

        <h3>Rechazados</h3>
        <section class="contenedor_apuntes">
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
            @extends(apunte_mochila)
        </section>
        @extends(modalSubirApunte)
    </main>
    <script src="views/static/js/modal.js"></script>
</body>

</html>