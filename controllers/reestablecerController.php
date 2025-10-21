<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'reestablecer');

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("resetPassword");

	$mostrarFormularioEmail = true;
	$mostrarMensajeEmail = false;
	$mostrarFormularioContrasena = false;
	$msg_error = "";
	$msg_success = "";

	/* Si se ha enviado el formulario */
	if(isset($_POST['sendCodeBtn'])) {
		/* Se instancia la clase de usuarios */
		$usuario = new Usuarios();

		/* Se obtienen los datos del formulario */
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';

		/* Se llama al método del controlador para reestablecer la contraseña */
		$result = $usuario->enviarEmailReestablecer($email);

		/* Se asignan los mensajes a la plantilla */
		if($result['errno'] != 200) {
			$msg_error = $result['error'];
			$msg_success = "";
		} else {
			$mostrarFormularioEmail = false;
			$mostrarMensajeEmail = true;

			$msg_error = "";
			$msg_success = "";
		}
	} else if (isset($_GET['email']) && isset($_GET['token'])) {
		/* Se instancia la clase de usuarios */
		$usuario = new Usuarios();

		/* Se obtienen los datos del formulario */
		$email = isset($_GET['email']) ? trim($_GET['email']) : '';
		$token = isset($_GET['token']) ? trim($_GET['token']) : '';

		/* Se llama al método del controlador para reestablecer la contraseña */
		$result = $usuario->verificarCodigoReestablecer($email, $token);
		/* Se asignan los mensajes a la plantilla */
		if($result['errno'] != 200) {
			$msg_error = $result['error'];
			$msg_success = "";
		} else {
			$mostrarFormularioContrasena = true;
			$mostrarFormularioEmail = false;
			$msg_error = "";
			$msg_success = "";
		}
	} else {
		/* Si no se ha enviado el formulario, se inicializan los mensajes vacíos */
		$tpl->assignVar(["MSG_ERROR" => ""]);
		$tpl->assignVar(["MSG_SUCCESS" => ""]);
	}

	if(isset($_POST['resetPasswordBtn'])) {
		/* Se instancia la clase de usuarios */
		$usuario = new Usuarios();

		/* Se obtienen los datos del formulario */
		$email = isset($_GET['email']) ? trim($_GET['email']) : '';
		$token = isset($_GET['token']) ? trim($_GET['token']) : '';
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		$confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';

		/* Se llama al método del controlador para reestablecer la contraseña */
		$result = $usuario->reestablecerContrasena($email, $token, $password, $confirmPassword);

		/* Se asignan los mensajes a la plantilla */
		if($result['errno'] != 200) {
			$msg_error = $result['error'];
			$msg_success = "";
			$mostrarFormularioContrasena = true;
			$mostrarFormularioEmail = false;
		} else {
			header("Location: ?slug=login&msg=reset_success");
			exit();;
		}
	}


	$tpl->assignVar(["MSG_ERROR" => $msg_error ?? ""]);
	$tpl->assignVar(["MSG_SUCCESS" => $msg_success ?? ""]);
	
	$tpl->assignVar(["SHOW_EMAIL_FORM" => $mostrarFormularioEmail ? '' : 'hidden']);
	$tpl->assignVar(["SHOW_MENSAJE_EMAIL" => $mostrarMensajeEmail ? '' : 'hidden']);
	$tpl->assignVar(["SHOW_CONTRASENA_FORM" => $mostrarFormularioContrasena ? '' : 'hidden']);

	$tpl->assignVar(["APP_SECTION" => "Reestablecer Contraseña"]);
	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
	
?>