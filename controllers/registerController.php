<?php

	$msg_error = "";
	
	/* si existe el boton de registro*/
	if(isset($_POST["btn_register"])){

		/* se instancia la clase usuario*/
		$usuario = new Usuarios();	

		/* realiza el registro */
		$response =$usuario->register($_POST);


		/* si se creo el usuario correctamente entonces va al login*/
		if($response["errno"] == 202){
			header("Location: ?slug=login");
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