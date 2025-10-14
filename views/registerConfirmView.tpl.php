<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/general/formulario.css">
    <link rel="stylesheet" href="views/static/css/register.css">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <title>COALA</title>
</head>

<body>
    <section class="main">
        <div class="logo">
        </div>
        <section class="contenedorFormulario">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Verificar Email</h3>
            <p class="poppins-regular texto-verificacion">Hemos enviado un código de verificación de 6 dígitos a tu correo electrónico. Por favor, ingrésalo a continuación:</p>
            <form action="?slug=registerConfirm" method="POST" id="formulario">
                <input type="text" id="codigo" name="txt_codigo" class="campo poppins-semibold" placeholder="Código de verificación" maxlength="6" pattern="[0-9]{6}" required>
                <input type="submit" value="Verificar" name="btn_verificar" class="btn poppins-semibold">
                <div class="fraseLink">
                    <span class="poppins-regular">¿No recibiste el código?</span><a href="?slug=register"
                        class="link poppins-bold">Registrarse nuevamente</a>
                </div>
                <div class="errorMsg" id="errorGeneral">{{ MSG_ERROR }}</div>
                <div class="successMsg" id="successGeneral">{{ MSG_SUCCESS }}</div>
            </form>
            <div class="btn_volver">
                <a href="?slug=register" class="btn_volver_login">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>
    </section>
</body>
<script src="views/static/js/validacionFormulario.js"></script>

</html>