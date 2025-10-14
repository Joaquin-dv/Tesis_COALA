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
    public $rol;
    public $id_escuela;
    public $id_anio_lectivo;
    public $esta_activo;
    public $correo_verificado_en;
    public $email_token;
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
        $this->rol = null;
        $this->id_escuela = null;
        $this->id_anio_lectivo = null;
        $this->esta_activo = null;
        $this->correo_verificado_en = null;
        $this->email_token = null;
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

    public function getUserRole($user_id)
    {
        $response = $this->query("SELECT roles.codigo FROM `roles_usuario` INNER JOIN roles ON roles.id = roles_usuario.rol_id WHERE roles_usuario.usuario_id = " . $user_id);

        if (count($response) > 0) {
            return $response[0]["codigo"];
        }

        return ["errno" => 404, "error" => "No se encontro rol para el usuario"];
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
        if ((int)$usuario["esta_activo"] === 0) {
            return ["errno" => 423, "error" => "Tu email aún no fue verificado. Verifícalo para continuar.", "email" => $usuario["correo_electronico"] ?? $form["txt_email"]];
        }

        $this->id                 = $usuario["id"];
        $this->nombre_completo    = $usuario["nombre_completo"];
        $this->email              = $form["txt_email"]; // o $usuario["correo_electronico"]
        $this->rol                = $this->getUserRole($this->id);
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
            'rol'             => $this->rol,
            'esta_activo'     => $this->esta_activo,
            'escuela_id'	  => $this->id_escuela,
            'id_anio_lectivo' => $this->id_anio_lectivo
        ];

        return ["errno" => 202, "error" => "Acceso valido"];
    }

    /**
     * Genera un código de verificación aleatorio de 6 dígitos
     */
    public function generarCodigoVerificacion()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Envía email con código de verificación
     */
    public function enviarCodigoVerificacion($email, $codigo, $nombre)
    {
        $asunto = "Código de verificación - COALA";
        $mensaje = "
        <html>
        <head>
            <title>Código de verificación</title>
        </head>
        <body>
            <h2>¡Hola " . $nombre . "!</h2>
            <p>Gracias por registrarte en COALA. Para completar tu registro, por favor ingresa el siguiente código de verificación:</p>
            <h1 style='color: #007bff; font-size: 32px; text-align: center; letter-spacing: 5px;'>" . $codigo . "</h1>
            <p>Este código expirará en 15 minutos.</p>
            <p>Si no solicitaste este código, puedes ignorar este email.</p>
            <br>
            <p>Saludos,<br>El equipo de COALA</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@coala.com" . "\r\n";


        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0 ;
        $mail->Host = HOST;
        $mail->Port = PORT;
        $mail->SMTPAuth = SMTP_AUTH; //
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Username = REMITENTE;
        $mail->Password = PASSWORD;

        $mail->setFrom(REMITENTE, NOMBRE);
        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        if(!$mail->send()){
            error_log("Mailer no se pudo enviar el correo!" );
			return array("errno" => 1, "error" => "No se pudo enviar.");
        }else{
			return array("errno" => 0, "error" => "Enviado con exito.");
		}   
         mail($email, $asunto, $mensaje, $headers);
    }

    /**
     * Registra usuario con código de verificación guardado en email_token
     */
    public function registerConVerificacion($form)
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

        // Verificar si el email ya existe
        $response = $this->callSP(
            "CALL sp_obtener_usuario(?)",
            [$form["txt_email"]]
        );

        if (!empty($response["result_sets"][0])) {
            return ["errno" => 409, "error" => "El email ingresado ya se encuentra registrado"];
        }

        $password_encripted = password_hash($form["txt_password"], PASSWORD_DEFAULT);
        $codigo_verificacion = $this->generarCodigoVerificacion();
        $nombreCompleto = $form['txt_nombre'] . " " . $form['txt_apellido'];
        $email = strtolower(trim($form["txt_email"]));
        $estaActivo = 0; // Usuario inactivo hasta verificar email
        $rolCodigo = 'student';
        $escuelaId = $form['select_escuela'] ?? 1; // Asignar escuela desde el formulario o 1 por defecto
        $actorId = null;

        // Crear usuario con código de verificación en email_token
        $this->callSP(
            "CALL sp_crear_usuario_con_token(?,?,?,?,?,?,?,?, @nuevo_id)",
            [
                $nombreCompleto,
                $email,
                $password_encripted,
                $estaActivo,
                $rolCodigo,
                $escuelaId,
                $actorId,
                $codigo_verificacion
            ]
        );

        // Enviar email con código
        if ($this->enviarCodigoVerificacion($email, $codigo_verificacion, $form['txt_nombre'])) {
            return ["errno" => 201, "error" => "Usuario creado. Se ha enviado un código de verificación a tu email.", "email" => $email];
        } else {
            return ["errno" => 500, "error" => "Error al enviar el código de verificación"];
        }
    }

    /**
     * Verifica el código de verificación y activa el usuario
     */
    public function verificarCodigo($email, $codigo)
    {
        if (empty($email) || empty($codigo)) {
            return ["errno" => 400, "error" => "Email y código son requeridos"];
        }

        // Buscar usuario con el código en email_token
        $response = $this->callSP(
            "CALL sp_obtener_usuario_con_token(?,?)",
            [$email, $codigo]
        );


        if (empty($response["result_sets"][0])) {
            return ["errno" => 404, "error" => "Código de verificación inválido o expirado"];
        }

        $usuario = $response["result_sets"][0][0];

        // Activar usuario y limpiar token
        $this->callSP(
            "CALL sp_activar_usuario(?)",
            [$usuario['id']]
        );

        return ["errno" => 202, "error" => "Email verificado correctamente. Ya puedes iniciar sesión."];
    }

}
