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


    public function __construct()
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
        $response = $this->query("SELECT `escuela_id` FROM `roles_usuario` WHERE `usuario_id` = " . $this->id);

        if (count($response) > 0) {
            return $response[0]["escuela_id"];
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

    /**
     *
     * Funcion de registro de usuario
     *
     * 202 = Se creo el usuario
     * 400 = email vacio y/o pass vacio y/o pass < 8 caracteres
	 * 409 = email ya registrado
	 * 500 = error al crear usuario
     *
     * */
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

        if (strlen($form["txt_password"]) < 8) {
            return ["errno" => 400, "error" => "La contraseña debe tener al menos 8 caracteres"];
        }

        if ($this->login($form)["errno"] == 404) {

            $password_encripted = password_hash($form["txt_password"], PASSWORD_DEFAULT);
			
            // $sql = "INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo_electronico`, `contrasena_hash`, `esta_activo`, `correo_verificado_en`, `creado_en`, `actualizado_en`, `borrado_en`) VALUES (NULL, '', '" . $form["txt_email"] . "', '" . $password_encripted . "', '1', NULL, current_timestamp(), current_timestamp(), NULL);";
			
            // $response = $this->query($sql);
            
			// Datos opcionales (ajustá si querés asignar rol/escuela/actor)
            $nombreCompleto = $form['txt_nombre'] . " " . $form['txt_apellido'];   // si no tenés el nombre en el formulario
			$email = strtolower(trim($form["txt_email"]));
            $estaActivo     = 1;
            $rolCodigo      = 'student'; // p.ej: 'student' | 'teacher' | 'admin'
            $escuelaId      = 1; // alcance de rol (si aplica)
            $actorId        = null; // auditoría: quién lo creó (si aplica)

            $this->callSP(
                "CALL sp_crear_usuario(?,?,?,?,?,?,?, @nuevo_id)",
                [
                    $nombreCompleto, // p_nombre_completo
                    $email,          // p_correo_electronico
                    $password_encripted,           // p_contrasena_hash (ya hasheada)
                    $estaActivo,     // p_esta_activo
                    $rolCodigo,      // p_rol_codigo (NULL si no asignás rol)
                    $escuelaId,      // p_escuela_id (NULL si no aplica)
                    $actorId         // p_actor_id (NULL si no hay auditoría)
                ]
                // ["@nuevo_id"]
            );

            // Leer OUT
            // $row = $this->query("SELECT @nuevo_id AS id");

			// var_dump($row); // DEBUG

            // if (!isset($row[0]["id"])) {
            //     return ["errno" => 500, "error" => "Hubo un error al crear el usuario, intente nuevamente mas tarde"];
            // }

            return ["errno" => 202, "error" => "Se creo el usuario correctamente"];
        }

        return ["errno" => 409, "error" => "El email ingresado ya se encuentra registrado"];
    }

    /**
     *
     * Funcion de logueo de usuario
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
        // $response = $this->query("SELECT * FROM `usuarios` WHERE `correo_electronico` LIKE '" . $form["txt_email"] . "'");

        $response = $this->callSP(
            "CALL sp_obtener_usuario(?)",
            [$form["txt_email"]]
        );

        /*si la cantidad de filas es 0 no se encontro email en usuarios*/
        if (empty($response['result_sets'][0])) {
            return ["errno" => 404, "error" => "Correo no encontrado"];
        }

        /* si se encontro el correo, obtiene la primera fila */
        $usuario = $response['result_sets'][0][0];

        /* correo encontrado pero contraseña incorrecta */
        if (!password_verify($form["txt_password"], $usuario["contrasena_hash"])) {
            return ["errno" => 403, "error" => "Contraseña incorrecta"];
        }

        /* correo electrónico encontrado y password correcto */
        $this->id                 = $usuario["id"];
        $this->nombre_completo    = $usuario["nombre_completo"];
        $this->email              = $form["txt_email"]; // o $usuario["correo_electronico"]
        $this->id_escuela         = $this->getSchoolID();
        $this->id_anio_lectivo    = $this->getYearID($this->id_escuela);
        $this->esta_activo        = $usuario["esta_activo"];
        $this->correo_verificado_en = $usuario["correo_verificado_en"];
        $this->creado_en          = $usuario["creado_en"];
        $this->actualizado_en     = $usuario["actualizado_en"];
        $this->borrado_en         = $usuario["borrado_en"];

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
