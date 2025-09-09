<?php 

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

			//$sql = "INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo_electronico`, `contrasena_hash`, `esta_activo`, `correo_verificado_en`, `creado_en`, `actualizado_en`) VALUES (NULL, \'\', \'test@test.com\', \'1234\', \'1\', NULL, current_timestamp(), current_timestamp());";

			/* busca el correo electronico en la tabla usuarios */
			$response = $this->query("SELECT * FROM `usuarios` WHERE `email` LIKE '".$form["txt_email"]."'");

			/*si la cantidad de filas es 0 no se encontro email en usuarios*/
			if(count($response) == 0){
				return ["errno" => 404, "error" => "Correo no encontrado"];
			}

			/*correo encontrado pero contraseña incorrecta*/
			if($response[0]["pass"]!=$form["txt_password"]){
				return ["errno" => 403, "error" => "Contraseña incorrecta"];
			}
			
			/* correo electronico encontrado y password correcto*/

			$this->email = $form["txt_email"];

			return ["errno" => 202, "error" => "Acceso valido"];

		}
	}
?>