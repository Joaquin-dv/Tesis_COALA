<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/general/formulario.css">
    <link rel="stylesheet" href="views/static/css/login.css">
    <title>Inicio de sesión</title>
</head>

<body>
    <section class="main">
        <div class="logo">
        </div>
        <section class="contenedorFormulario">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Iniciar Sesión</h3>
            <form action="?slug=login" method="POST" id="formulario">
                <input type="email" id="email" class="campo poppins-semibold" placeholder="Correo electrónico">
                <input type="password" id="contraseña" class="campo poppins-semibold" placeholder="Contraseña">
                <a href="resetPassewordView.html" class="poppins-regular">¿Olvidaste tu contraseña?</a>
                <input type="submit" value="Entrar" class="btn poppins-semibold">
                <div class="fraseLink">
                    <span class="poppins-regular">¿No tienes cuenta?</span><a href="?slug=register"
                        class="link poppins-bold">Registrate</a>
                </div>
                <div class="errorMsg" id="errorGeneral"></div>
            </form>
        </section>
    </section>
</body>
<script src="views/static/js/validacionFormulario.js"></script>

</html>