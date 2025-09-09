<?php 
	/**
	 * 
	 * Usuarios.php esta clase es para gestionar los usuarios
	 * 
	 * */
	class Usuarios extends DBAbstract
	{

		public $email;
		
		function __construct()
		{
			/* se debe invocar al constructor de la clase padre */
			parent::__construct();

			$this->email = "";
		}

		/**
		 * 
		 * Retorna la cantidad de usuarios
		 * 
		 * */
		public function getCant(){
			
			// query("CALL getCant()");

			return count($this->query("SELECT * FROM `usuarios`"));
		}

		/* registra un nuevo usuario, valida si el email ya esta registrado*/
		public function register($form){

			/* si el email esta vacio*/
			if($form["txt_email"]==""){
				return ["errno" => 400, "error" => "Falta email"];
			}

			/* si el password esta vacio*/
			if($form["txt_password"]==""){
				return ["errno" => 400, "error" => "Falta contraseña"];
			}

			if($this->login($form)["errno"] == 404){

				$password_encripted = password_hash($form["txt_password"], PASSWORD_DEFAULT);

				$sql = "INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo_electronico`, `contrasena_hash`, `esta_activo`, `correo_verificado_en`, `creado_en`, `actualizado_en`, `borrado_en`) VALUES (NULL, '', '".$form["txt_email"]."', '".$password_encripted."', '1', NULL, current_timestamp(), current_timestamp(), '2025-09-09 20:50:08.000000');";

				$response = $this->query($sql);

				return ["errno" => 202, "error" => "Se creo el usuario correctamente"];
			}

			return ["errno" => 409, "error" => "El email ingresado ya se encuentra registrado"];
		}


		/**
		 * 
		 * intenta loguear
		 * 
		 * 202 = usuario valido
		 * 400 = email vacio y/o pass vacio
		 * 404 = usuario invalido
		 * 402 = usuario valido contraseña incorrecto
		 * 
		 * */
		public function login($form){

			/* si el email esta vacio*/
			if($form["txt_email"]==""){
				return ["errno" => 400, "error" => "Falta email"];
			}

			/* si el password esta vacio*/
			if($form["txt_password"]==""){
				return ["errno" => 400, "error" => "Falta contraseña"];
			}

			/* busca el correo electronico en la tabla usuarios */
			$response = $this->query("SELECT * FROM `usuarios` WHERE `correo_electronico` LIKE '".$form["txt_email"]."'");

			/*si la cantidad de filas es 0 no se encontro email en usuarios*/
			if(count($response) == 0){
				return ["errno" => 404, "error" => "Correo no encontrado"];
			}

			/*correo encontrado pero contraseña incorrecta*/
			if(!password_verify($form["txt_password"], $response[0]["contrasena_hash"])){
				return ["errno" => 403, "error" => "Contraseña incorrecta"];
			}
			

			/* correo electronico encontrado y password correcto*/

			$this->email = $form["txt_email"];

			$_SESSION[APP_NAME]["user"] = $this;
			
			return ["errno" => 202, "error" => "Acceso valido"];
		}
	}
?>