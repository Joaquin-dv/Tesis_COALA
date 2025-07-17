<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="../explorar/css/style_apunte.css">
    <title>Clases</title>
</head>
<body class="josefin-sans-normal">
    <header>
        <div class="contenedor_logo_secundario">
            <img src="img/logo_secundario.png" alt="">
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
        <?php
        // Include the component for displaying notes
        for ($i = 0; $i < 6; $i++) {
            include '../explorar/componentes/apunte_explorar.html';
        }  
        ?>
    </main>
</body>
</html>