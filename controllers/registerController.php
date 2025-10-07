<?php

	$msg_error = "";
	
	// var_dump($_POST);
	/* si existe el boton de registro*/
	if(isset($_POST["txt_password"])){
		/* se instancia la clase usuario*/
		$usuario = new Usuarios();	

		/* realiza el registro */
		$response = $usuario->registerConVerificacion($_POST);
		
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

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>