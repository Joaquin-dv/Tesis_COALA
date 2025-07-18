<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../inicio/css/inicio.css">

    <link rel="stylesheet" href="../explorar/css/style_apunte.css">
    <title>Clases</title>
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
                <h2>Busca clases</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador">
            </div>
            <div class="boton_subir_apunte">
            </div>
        </section>

        <h2 class="vistos_recientemente">Vistos recientemente</h2>
        <section class="contenedor_apuntes">
        <?php 
        include '../explorar/componentes/apunte_explorar.html';
        include '../explorar/componentes/apunte_explorar.html';
        include '../explorar/componentes/apunte_explorar.html';
        include '../explorar/componentes/apunte_explorar.html';
        include '../explorar/componentes/apunte_explorar.html';
        include '../explorar/componentes/apunte_explorar.html';
        
        ?>

            


        </section>
    </main>
</body>
</html>