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
    <title>COALA</title>
</head>

<body>
    <section class="main">
        <div class="logo">
        </div>
        <section id="step1" class="contenedorFormulario">
            <h1 class="poppins-bold">COALA</h1>
            <h3 class="poppins-semibold">Recupera tu cotraseña</h3>
            <form method="post" id="formulario">
                <input type="email" id="email" class="campo poppins-semibold" placeholder="Correo electrónico">
                <input id="sendCodeBtn" type="submit" value="Enviar código" class="btn poppins-semibold">
                <div class="errorMsg" id="errorGeneral"></div>
            </form>
        </section>


        <section id="step2" class="contenedorFormulario hidden">
            <h1 class="poppins-bold">COALA</h1>
            <h4 class="poppins-semibold">Ingresa el codigo enviado a tu email</h4>
            <form method="post" id="formulario">
                <section class="codigo">
                    <input type="text" class="campoCode" maxlength="1">
                    <input type="text" class="campoCode" maxlength="1">
                    <input type="text" class="campoCode" maxlength="1">
                    <span class="poppins-semibold">-</span>
                    <input type="text" class="campoCode" maxlength="1">
                    <input type="text" class="campoCode" maxlength="1">
                    <input type="text" class="campoCode" maxlength="1">
                </section>
                <input id="recoverBtn" type="submit" value="Recuperar" class="btn poppins-semibold">
                <div class="errorMsg" id="errorGeneral"></div>
            </form>
        </section>
    </section>
</body>
<script src="views/static/js/saltoCampo.js"></script>
<script src="views/static/js/resetPasswordScript.js"></script>

</html>