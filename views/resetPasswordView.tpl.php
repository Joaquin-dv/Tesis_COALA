<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/static/css/general/formulario.css">
    <link rel="stylesheet" href="views/static/css/register.css">
    <link rel="stylesheet" href="views/static/css/resetPasswordStyle.css">
    <link rel="icon" type="image/png" sizes="32x32" href="views/static/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="views/static/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="views/static/img/favicon/apple-touch-icon.png">
    <link rel="manifest" href="views/static/img/favicon/site.webmanifest">
    <script src="https://kit.fontawesome.com/f63493d67a.js" crossorigin="anonymous"></script>
    <title>COALA</title>
</head>

<body class="josefin-sans-normal">
    <section class="main">
        <div class="logo">
        </div>
        <section id="step1" class="contenedorFormulario {{ SHOW_EMAIL_FORM }}">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Recupera tu cotraseña</h3>
            <form method="post" id="formulario">
                <input type="email" id="email" name="email" class="campo poppins-semibold" placeholder="Correo electrónico">
                <input id="sendCodeBtn" name="sendCodeBtn" type="submit" value="Enviar código" class="btn poppins-semibold">
                <div class="errorMsg" id="errorGeneral">{{ MSG_ERROR }}</div>
                <div class="successMsg" id="successGeneral">{{ MSG_SUCCESS }}</div>
            </form>
            <div class="btn_volver">
                <a href="?slug=landing" class="btn_volver_login">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>

        <section id="mensajeEmailEnviado" class="contenedorFormulario {{ SHOW_MENSAJE_EMAIL }}">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Recupera tu cotraseña</h3>
            <div class="texto-verificacion">
                <p>Se ha enviado un email a tu correo electrónico. Por favor, revisa tu bandeja.</p>
            </div>
            <div class="btn_volver">
                <a href="?slug=landing" class="btn_volver_login">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>


        <section id="step2" class="contenedorFormulario {{ SHOW_CONTRASENA_FORM }}">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Nueva contraseña</h3>
            <form method="post" id="resetForm">
                <input type="hidden" name="email" value="{{ EMAIL }}">
                <input type="hidden" name="token" value="{{ TOKEN }}">
                <div class="campo-password">
                    <input type="password" name="password" class="campo poppins-semibold" placeholder="Nueva contraseña" required minlength="8">
                    <span class="toggle-password" onclick="verPassword(this)">
                        <i class="fa-solid fa-eye-slash"></i>
                    </span>
                </div>
                <div class="campo-password">
                    <input type="password" name="confirmPassword" class="campo poppins-semibold" placeholder="Confirmar contraseña" required minlength="8">
                    <span class="toggle-password" onclick="verPassword(this)">
                        <i class="fa-solid fa-eye-slash"></i>   
                    </span>
                </div>
                <input name="resetPasswordBtn" type="submit" value="Cambiar contraseña" class="btn poppins-semibold">
                <div class="errorMsg" id="errorGeneral">{{ MSG_ERROR }}</div>
                <div class="successMsg" id="successGeneral">{{ MSG_SUCCESS }}</div>
            </form>
            <div class="btn_volver">
                <a href="?slug=landing" class="btn_volver_login">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>
    </section>
</body>
<script src="views/static/js/verContrasenia.js"></script>

<!-- <script src="views/static/js/saltoCampo.js"></script>
<script src="views/static/js/resetPasswordScript.js"></script> -->

</html>