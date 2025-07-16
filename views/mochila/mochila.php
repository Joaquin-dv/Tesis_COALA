<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/views/inicio/css/inicio.css">
    <link rel="stylesheet" href="/css/mochila.css">
    <title>Panel</title>
</head>

<body class="josefin-sans-normal">
    <header>
        <div class="contenedor_logo_secundario">
            <img src="/views/inicio/img/logo_secundario.png" alt="">
        </div>

        <nav class="barra_navegacion">
            <a href="#">Inicio</a>
            <a href="#">Explorar</a>
            <a href="#">Mochila</a>
            <a href="#">Clases</a>
        </nav>


        <section class="perfil">
            <img src="/views/inicio/img/foto_perfil.jpg" alt="foto de perfil">
        </section>
    </header>

    <main>
        <section class="buscador_subir_apunte">
            <div class="contenedor_buscador">
                <h2>¡Hola, Pepe!</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador">
            </div>
            <div class="boton_subir_apunte">
                <button>Subir apunte</button>
            </div>
        </section>

        <h2 class="vistos_recientemente">Vistos recientemente</h2>
        <section class="contenedor_apuntes">
            <?php
            include '../mochila/componentes/apunte_mochila.html';
            ?>
        </section>
        <h2 class="para_ti">Para ti</h2>
        <section class="contenedor_apuntes">

        </section>
    </main>

</body>

</html>