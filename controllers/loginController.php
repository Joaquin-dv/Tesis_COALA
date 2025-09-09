<?php 

	/**
	 * 
	 * Se incluyen las librerias
	 * Los modelos
	 * 
	 * */

	require_once 'models/Usuarios.php';

	/**
	 * 
	 * Lógica
	 * 
	 * */

	/* por defecto no va a tener msj error*/
	$msg_error = "";

	/* instancia la clase usuario en el objeto usuario*/
	$usuario = new Usuarios();

	/* si se presiono el boton de login*/
	if(isset($_POST["btn_login"])){

		$result = $usuario->login($_POST);

		/* si retorna 202 el usuario y contraseña son validos*/
		if( $result["errno"] == 202){
			/* inicializar sesión dentro de PHP */
			/*¨¨¨*/
			/* lleva al panel de usuario */
			header("Location: ?slug=panel");
		}

		/* capturo el mensaje de error en caso de logueo invalido*/
		$msg_error = $result["error"];

	}


	/***
	 * 
	 * Al final siempre se imprime la vista
	 * 
	 * */

	$tpl = new Mopla("login");


	$tpl->assignVar(["MSG_ERROR" => $msg_error, "TEST" => DB_HOST]);
	/*para asignar valor a las variables dentro la plantilla*/
	/* formato {{ variable }} valor a pasar como un vector asociativo [ variable_html => valor] */
	$tpl->assignVar(["APP_SECTION" => "Login"]);

	$tpl->printToScreen();

?>