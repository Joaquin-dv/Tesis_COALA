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
            <h3 class="poppins-semibold">Registrate</h3>
            <form action="?slug=register" method="POST" id="formulario">
                <div class="nombreCompleto">
                    <input type="text" id="nombre" name="txt_nombre" class="campo poppins-semibold" placeholder="Nombre">
                    <input type="text" id="apellido" name="txt_apellido" class="campo poppins-semibold" placeholder="Apellido">
                </div>
                <input type="email" id="email" name="txt_email" class="campo poppins-semibold" placeholder="Correo electrónico">
                <!-- <input type="password" id="contraseña" name="txt_password" class="campo poppins-semibold" placeholder="Contraseña">
                <input type="password" id="contraseña2" name="txt_password2" class="campo poppins-semibold" placeholder="Confirmar contraseña"> -->
                <div class="campo-password">
                    <input type="password" id="contraseña" name="txt_password" class="campo poppins-semibold" placeholder="Contraseña">
                    <span class="toggle-password" onclick="verPassword(this, 'contraseña')">
                        <i class="fa-solid fa-eye-slash"></i>
                    </span>
                </div>

                <div class="campo-password">
                    <input type="password" id="contraseña2" name="txt_password2" class="campo poppins-semibold" placeholder="Confirmar contraseña">
                    <span class="toggle-password" onclick="verPassword(this, 'contraseña2')">
                        <i class="fa-solid fa-eye-slash"></i>
                    </span>
                </div>

                <select name="select_escuela" id="select_escuela" class="campo poppins-semibold">
                    <option value="" disabled selected>Seleccionar escuela</option>
                    {{ ESCUELAS }}
                </select>

                <input type="submit" value="Entrar" name="btn_register" class="btn poppins-semibold">
                <div class="fraseLink">
                    <span class="poppins-regular">¿Ya tienes cuenta?</span><a href="?slug=login"
                        class="link poppins-bold">Iniciar Sesión</a>
                </div>
                <div class="errorMsg" id="errorGeneral">{{ MSG_ERROR }}</div>
            </form>
            <div class="btn_volver">
                <a href="?slug=landing" class="btn_volver_login">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>
    </section>
</body>
<script src="views/static/js/validacionFormulario.js"></script>
<script src="views/static/js/verContrasenia.js"></script>

</html>