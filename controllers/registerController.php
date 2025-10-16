<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'register');

	$msg_error = "";
	
	// var_dump($_POST);
	/* si existe el boton de registro*/
	if(isset($_POST["txt_password"])){
		/* se instancia la clase usuario*/
		$usuario = new Usuarios();	

		/* realiza el registro */
		$response = $usuario->registerConVerificacion($_POST);

		if ($response["errno"] == 410) {
			// Ya existe un registro pendiente de verificación reenviar al registerConfirm
			$_SESSION['email_verificacion'] = $_POST['txt_email'];
			header("Location: ?slug=registerConfirm");
			exit();
		}
		
		/* si se creo el usuario correctamente entonces va al registerConfirm para verificar el email*/
		if($response["errno"] == 201){
			/* se guarda el email en la sesión */
			$_SESSION['email_verificacion'] = $response["email"];
			/* se redirige al registerConfirm */
			header("Location: ?slug=registerConfirm");
		}

		$msg_error = $response["error"];
	}	


	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("register");


	$tpl->assignVar(["MSG_ERROR" => $msg_error]);

	/*para asignar valor a las variables dentro la plantilla*/
	/* formato {{ variable }} valor a pasar como un vector asociativo [ variable_html => valor] */
	$tpl->assignVar(["APP_SECTION" => "Registro"]);

	/* Obtener la lista de escuelas desde la base de datos */
	$escuelas = new Escuelas();
	$listaEscuelas = $escuelas->getEscuelas();
	$opcionesEscuelas = "";
	foreach ($listaEscuelas as $escuela) {
		$opcionesEscuelas .= '<option value="' . htmlspecialchars($escuela['ESCUELA_ID']) . '">' . htmlspecialchars($escuela['ESCUELA_NOMBRE']) . '</option>';
	}
	$tpl->assignVar(["ESCUELAS" => $opcionesEscuelas]);

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>