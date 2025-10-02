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
	public $id_escuela;
	public $id_anio_lectivo;
	public $esta_activo;
	public $correo_verificado_en;
	public $creado_en;
	public $actualizado_en;
	public $borrado_en;


	function __construct()
	{
		/* se debe invocar al constructor de la clase padre */
		parent::__construct();

		$this->id = null;
		$this->nombre_completo = "";
		$this->email = "";
		$this->id_escuela = null;
		$this->id_anio_lectivo = null;
		$this->esta_activo = null;
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
	public function getCant()
	{

		// query("CALL getCant()");

		return count($this->query("SELECT * FROM `usuarios`"));
	}

	public function getSchoolID()
	{
		$response = $this->query("SELECT `id` FROM `roles_usuario` WHERE `usuario_id` = " . $this->id);

		if (count($response) > 0) {
			return $response[0]["id"];
		}

		return ["errno" => 404, "error" => "No se encontro escuela para el usuario"];
	}

	public function getYearID($id_escuela)
	{
		$sql = "SELECT anios_lectivos.id FROM `anios_lectivos` INNER JOIN escuelas ON escuelas.id = anios_lectivos.escuela_id WHERE anios_lectivos.escuela_id = " . $id_escuela . ";";

		$response = $this->query($sql);

		if (count($response) > 0) {
			return $response[0]["id"];
		}

		return ["errno" => 404, "error" => "No se encontro escuela para el usuario"];
	}

	/* registra un nuevo usuario, valida si el email ya esta registrado*/
	public function register($form)
	{

		/* si el email esta vacio*/
		if ($form["txt_email"] == "") {
			return ["errno" => 400, "error" => "Falta email"];
		}

		/* si el password esta vacio*/
		if ($form["txt_password"] == "") {
			return ["errno" => 400, "error" => "Falta contraseña"];
		}

		if ($this->login($form)["errno"] == 404) {

			$password_encripted = password_hash($form["txt_password"], PASSWORD_DEFAULT);

			$sql = "INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo_electronico`, `contrasena_hash`, `esta_activo`, `correo_verificado_en`, `creado_en`, `actualizado_en`, `borrado_en`) VALUES (NULL, '', '" . $form["txt_email"] . "', '" . $password_encripted . "', '1', NULL, current_timestamp(), current_timestamp(), '2025-09-09 20:50:08.000000');";

			$response = $this->query($sql);

			return ["errno" => 202, "error" => "Se creo el usuario correctamente"];
		}

		return ["errno" => 409, "error" => "El email ingresado ya se encuentra registrado"];
	}

	// ===== REGISTER CON STORED PROCEDURE =====
	// /* registra un nuevo usuario usando SP; valida email y password */
	// public function register($form)
	// {
	// 	// Validaciones básicas
	// 	if (empty($form["txt_email"])) {
	// 		return ["errno" => 400, "error" => "Falta email"];
	// 	}
	// 	if (empty($form["txt_password"])) {
	// 		return ["errno" => 400, "error" => "Falta contraseña"];
	// 	}

	// 	$email = strtolower(trim($form["txt_email"]));
	// 	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// 		return ["errno" => 400, "error" => "Email inválido"];
	// 	}

	// 	// Hash de la contraseña (PHP)
	// 	$hash = password_hash($form["txt_password"], PASSWORD_DEFAULT);

	// 	// Datos opcionales (ajustá si querés asignar rol/escuela/actor)
	// 	$nombreCompleto = '';   // si no tenés el nombre en el formulario
	// 	$estaActivo     = 1;
	// 	$rolCodigo      = null; // p.ej: 'student' | 'teacher' | 'admin'
	// 	$escuelaId      = null; // alcance de rol (si aplica)
	// 	$actorId        = null; // auditoría: quién lo creó (si aplica)

	// 	$this->begin();
	// 	try {
	// 		// Preparar OUT param
	// 		$this->query("SET @nuevo_id := 0");

	// 		// Llamada al SP
	// 		$this->callSP(
	// 			"CALL sp_crear_usuario(?,?,?,?,?,?,?, @nuevo_id)",
	// 			[
	// 				$nombreCompleto, // p_nombre_completo
	// 				$email,          // p_correo_electronico
	// 				$hash,           // p_contrasena_hash (ya hasheada)
	// 				$estaActivo,     // p_esta_activo
	// 				$rolCodigo,      // p_rol_codigo (NULL si no asignás rol)
	// 				$escuelaId,      // p_escuela_id (NULL si no aplica)
	// 				$actorId         // p_actor_id (NULL si no hay auditoría)
	// 			],
	// 			["@nuevo_id"]
	// 		);

	// 		// Leer OUT
	// 		$row = $this->query("SELECT @nuevo_id AS id");
	// 		$nuevoId = isset($row[0]["id"]) ? (int)$row[0]["id"] : 0;
	// 		if ($nuevoId <= 0) {
	// 			throw new Exception("No se obtuvo el ID del usuario");
	// 		}

	// 		$this->commit();
	// 		return ["errno" => 202, "error" => "Se creó el usuario correctamente", "usuario_id" => $nuevoId];
	// 	} catch (Throwable $e) {
	// 		$this->rollback();

	// 		// Mapear mensajes comunes a tus códigos
	// 		$msg = $e->getMessage();
	// 		if (stripos($msg, 'correo ya está registrado') !== false || stripos($msg, 'duplicate') !== false) {
	// 			return ["errno" => 409, "error" => "El email ingresado ya se encuentra registrado"];
	// 		}
	// 		if (stripos($msg, 'Formato de correo inválido') !== false) {
	// 			return ["errno" => 400, "error" => "Email inválido"];
	// 		}
	// 		if (stripos($msg, 'Falta la contraseña') !== false) {
	// 			return ["errno" => 400, "error" => "Falta contraseña"];
	// 		}

	// 		return ["errno" => 500, "error" => "DB error: " . $msg];
	// 	}
	// }

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
	public function login($form)
	{

		/* si el email esta vacio*/
		if ($form["txt_email"] == "") {
			return ["errno" => 400, "error" => "Falta email"];
		}

		/* si el password esta vacio*/
		if ($form["txt_password"] == "") {
			return ["errno" => 400, "error" => "Falta contraseña"];
		}

		/* busca el correo electronico en la tabla usuarios */
		$response = $this->query("SELECT * FROM `usuarios` WHERE `correo_electronico` LIKE '" . $form["txt_email"] . "'");

		/*si la cantidad de filas es 0 no se encontro email en usuarios*/
		if (count($response) == 0) {
			return ["errno" => 404, "error" => "Correo no encontrado"];
		}

		/*correo encontrado pero contraseña incorrecta*/
		if (!password_verify($form["txt_password"], $response[0]["contrasena_hash"])) {
			return ["errno" => 403, "error" => "Contraseña incorrecta"];
		}


		/* correo electronico encontrado y password correcto*/

		$this->id = $response[0]["id"];
		$this->nombre_completo = $response[0]["nombre_completo"];
		$this->email = $form["txt_email"];
		$this->id_escuela = $this->getSchoolID();
		$this->id_anio_lectivo = $this->getYearID($this->id_escuela);
		$this->esta_activo = $response[0]["esta_activo"];
		$this->correo_verificado_en = $response[0]["correo_verificado_en"];
		$this->creado_en = $response[0]["creado_en"];
		$this->actualizado_en = $response[0]["actualizado_en"];
		$this->borrado_en = $response[0]["borrado_en"];

		// $_SESSION[APP_NAME]["user"] = $this;

		$_SESSION[APP_NAME]['user'] = [
			'id'              => $this->id,
			'nombre_completo' => $this->nombre_completo,
			'email'           => $this->email,
			'esta_activo'     => $this->esta_activo,
			'escuela_id'	  => $this->id_escuela,
			'id_anio_lectivo' => $this->id_anio_lectivo
		];

		return ["errno" => 202, "error" => "Acceso valido"];
	}
}
