<?php

/* Log de acceso a la página */
$logger = new Logger();
$logger->pageLoad(null, 'registerConfirm');

$msg_error = "";
$msg_success = "";

/* si existe el boton de verificación*/
if(isset($_POST["txt_codigo"])){
    /* se instancia la clase usuario*/
    $usuario = new Usuarios();	

    /* obtiene el email de la sesión */
    $email = isset($_SESSION['email_verificacion']) ? $_SESSION['email_verificacion'] : '';
    
    if(empty($email)){       
        $msg_error = "Sesión expirada. Por favor, regístrate nuevamente.";
    } else {
        /* verifica el código */
        $response = $usuario->verificarCodigo($email, $_POST["txt_codigo"]);

        /* si se verificó correctamente entonces va al login*/
        if($response["errno"] == 202){
            // Limpiar sesión
            unset($_SESSION['email_verificacion']);
            header("Location: ?slug=login&msg=email_verificado");
            exit();
        }
        $msg_error = $response["error"];
    }
}

/* si existe el boton de reenviar código*/
if(isset($_GET["btn_reenviar"])){
    /* se instancia la clase usuario*/
    $usuario = new Usuarios();	

    /* obtiene el email de la sesión */
    $email = isset($_SESSION['email_verificacion']) ? $_SESSION['email_verificacion'] : '';
    
    if(empty($email)){
        $msg_error = "Sesión expirada. Por favor, regístrate nuevamente.";
    } else {
        /* reenvía el código */
        $response = $usuario->reenviarCodigo($email);

        /* si se reenvió correctamente entonces muestra mensaje de éxito*/
        if($response["errno"] == 200){
            $msg_success = "Código reenviado correctamente. Por favor, revisa tu correo electrónico: ".$response["email"]."";
        } else {
            $msg_error = $response["error"];
        }
    }
}

/* Se instancia a la clase del motor de plantillas */
$tpl = new Mopla("registerConfirm");

$tpl->assignVar(["MSG_ERROR" => $msg_error]);
$tpl->assignVar(["MSG_SUCCESS" => $msg_success]);

/*para asignar valor a las variables dentro la plantilla*/
$tpl->assignVar(["APP_SECTION" => "Verificar Email"]);

/* Imprime la plantilla en la página */
$tpl->printToScreen();
?>