<?php

	if(isset($_SESSION[APP_NAME]["user"])){
		header("Location: ?slug=inicio");
	}

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'login');

	/**
	 *
	 * Lógica
	 *
	 * */

	/* por defecto no va a tener msj error*/
	$msg_error = "";
	$msg_success = "";
	
	/* si se presiono el boton de login*/
	if(isset($_POST["txt_password"])){
			/* instancia la clase usuario en el objeto usuario*/
			$usuario = new Usuarios();

		// Verificar si es usuario demo
		if($_POST["txt_email"] == "demo@demo" && $_POST["txt_password"] == "demo"){
			// Crear sesión demo
			$_SESSION[APP_NAME]['user'] = [
				'id' => 'demo',
				'nombre_completo' => 'Usuario Demo',
				'email' => 'demo@demo.com',
				'rol' => 'demo',
				'esta_activo' => 1,
				'escuela_id' => 1,
				'id_anio_lectivo' => 1
			];
			header("Location: ?slug=inicio");
			exit();
		}

		$result = $usuario->login($_POST);

		/* si retorna 202 el usuario y contraseña son validos*/
		if( $result["errno"] == 202){
			/* inicializar sesión dentro de PHP */
			/*¨¨¨*/
			/* lleva al panel de usuario */
			header("Location: ?slug=inicio");
			exit();
		}

		/* usuario no verificado: redirigir a confirmación */
		if ($result["errno"] == 423) {
			$_SESSION['email_verificacion'] = $result['email'] ?? ($_POST['txt_email'] ?? '');
			header("Location: ?slug=registerConfirm");
			exit();
		}

		/* capturo el mensaje de error en caso de logueo invalido*/
		$msg_error = $result["error"];

	}

	if(isset($_GET["msg"]) && $_GET["msg"] == "email_verificado"){
		$msg_success = "Email verificado correctamente. Ya puedes iniciar sesión.";
	}

	if(isset($_GET["msg"]) && $_GET["msg"] == "requiere_login"){
		$msg_error = "Es necesario iniciar sesión para acceder a esta funcionalidad.";
	}


	/***
	 * 
	 * Al final siempre se imprime la vista
	 * 
	 * */

	$tpl = new Mopla("login");


	$tpl->assignVar(["MSG_ERROR" => $msg_error, "TEST" => DB_HOST, "MSG_SUCCESS" => $msg_success]);
	/*para asignar valor a las variables dentro la plantilla*/
	/* formato {{ variable }} valor a pasar como un vector asociativo [ variable_html => valor] */
	$tpl->assignVar(["APP_SECTION" => "Login"]);

	$tpl->printToScreen();

?>