<?php 
	/**
	 * 
	 * Usuarios.php esta clase es para gestionar los usuarios
	 * 
	 * */
	class Usuarios extends DBAbstract
	{
		public $id;
		public $nombre_completo;
		public $email;
		public $esta_activo;
		public $correo_verificado_en;
		public $creado_en;
		public $actualizado_en;
		public $borrado_en;

		
		function __construct()
		{
			/* se debe invocar al constructor de la clase padre */
			parent::__construct();

			$this->id = 0;
			$this->nombre_completo = "";
			$this->email = "";
			$this->esta_activo = 0;
			$this->correo_verificado_en = null;
			$this->creado_en = "";
			$this->actualizado_en = "";
			$this->borrado_en = null;
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

		public function getSchoolID(){
			$response = $this->query("SELECT `id` FROM `roles_usuario` WHERE `usuario_id` = ".$this->id);

			if(count($response) > 0){
				return $response[0]["escuela_id"];
			}

			return ["errno" => 404, "error" => "No se encontro escuela para el usuario"];
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
			$this->id = $response[0]["id"];
			$this->nombre_completo = $response[0]["nombre_completo"];
			$this->esta_activo = $response[0]["esta_activo"];
			$this->correo_verificado_en = $response[0]["correo_verificado_en"];
			$this->creado_en = $response[0]["creado_en"];
			$this->actualizado_en = $response[0]["actualizado_en"];
			$this->borrado_en = $response[0]["borrado_en"];

			$_SESSION[APP_NAME]["user"] = $this;
			
			return ["errno" => 202, "error" => "Acceso valido"];
		}
	}
?>