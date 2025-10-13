<?php

/**
 *
 * Apuntes.php esta clase es para gestionar los apuntes
 *
 * */
class Apuntes extends DBAbstract
{
    // === Atributos principales (coinciden con columnas) ===
    private $id;
    private $titulo;
    private $descripcion;
    private $usuario_cargador_id;
    private $escuela_id;
    private $materia;
    private $anio_lectivo_id;
    private $curso_id;                  // NULL permitido
    private $nivel;                     // NULL permitido (tinyint unsigned)
    private $division;                  // NULL permitido (varchar 16)
    private $visibilidad;               // 'publico' | 'curso'
    private $estado;                    // 'pendiente' | 'en_revision' | 'aprobado' | 'rechazado'
    private $verificado_por_docente;    // tinyint(1) 0/1
    private $verificado_por_usuario_id; // NULL permitido
    private $verificado_en;             // datetime NULL
    private $estado_ia;                 // 'no_escaneado' | 'aprobado' | 'marcado' | 'bloqueado'
    private $motivo_rechazo;            // varchar(255) NULL
    private $logger;
    /** NUEVO: user id cacheado de la sesión para logging */
    private $user_id;

    function __construct()
    {
        /* se debe invocar al constructor de la clase padre */
        parent::__construct();

        $this->id = 0;
        $this->titulo = "";
        $this->descripcion = "";
        $this->usuario_cargador_id = 0;
        $this->escuela_id = 0;
        $this->materia = 0;
        $this->anio_lectivo_id = 0;
        $this->curso_id = null;
        $this->nivel = null;
        $this->division = null;
        $this->visibilidad = "publico";
        $this->estado = "en_revision";
        $this->verificado_por_docente = 0;
        $this->verificado_por_usuario_id = null;
        $this->verificado_en = null;
        $this->estado_ia = "no_escaneado";
        $this->motivo_rechazo = null;
        $this->logger = new Logger();

        // NUEVO: guardar el id de usuario (o 0 si no hay sesión)
        $this->user_id = (isset($_SESSION[APP_NAME]["user"]["id"]) && is_numeric($_SESSION[APP_NAME]["user"]["id"]))
            ? (int) $_SESSION[APP_NAME]["user"]["id"]
            : 0;
    }

    /**
     * 
     * Retorna la cantidad de apuntes
     * 
     * */
    public function getCantAprobados()
    {
        $row = $this->callSP("CALL sp_obtener_cantidad_apuntes_aprobados()");
        return (int) $row['result_sets'][0][0]['c'];
    }

    /**
     * Obtiene todos los apuntes, con un límite opcional
     * Si $formated es true, devuelve un array simplificado para la vista
     */
    public function getApuntes($limit = 100, bool $formated = false)
    {
        // Evitá inyección por si llega algo raro en $limit
        $limit = (int) $limit;
        
        $result = $this->callSP(
                "CALL sp_obtener_apuntes(?)",
                [$limit]
            );

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "APUNTE_ID" => $row["APUNTE_ID"],
                    "TITULO" => $row["TITULO"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : "Sin calificar",
                    "IMAGEN" => "",
                    "USUARIO_ID" => $row["USUARIO_ID"],
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
                ];
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }
    /**
     * Obtiene todos los apuntes de un alumno por su ID
     */
    public function getApuntesByAlumno($alumno_id, bool $formated = false)
    {
        if (!is_numeric($alumno_id) || $alumno_id <= 0) {
            $this->logger->error($this->user_id, '500', 'No se obtuvo el ID del alumno correctamente');
            return ["errno" => 500, "error" => "No se obtuvo el ID del alumno correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apuntes_por_alumno(?)", [$alumno_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : null,
                    "IMAGEN" => "",
                    "ESTADO" => $row["ESTADO"],
                ];
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    public function getApuntesFavoritosByAlumno($alumno_id, bool $formated = false)
    {
        if (!is_numeric($alumno_id) || $alumno_id <= 0) {
            $this->logger->error($this->user_id, '500', 'No se obtuvo el ID del alumno correctamente');
            return ["errno" => 500, "error" => "No se obtuvo el ID del alumno correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apuntes_favoritos_por_alumno(?)", [$alumno_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : null,
                    "IMAGEN" => "",
                ];
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    public function getPromedioByIDApunte($apunte_id)
    {
        //
        $result = $this->callSP("CALL sp_obtener_promedio_por_apunte_id()", [$apunte_id]);

        if (count($result['result_sets'][0]) > 0) {
            return (float) $result['result_sets'][0]["promedio_calificacion"];
        } else {
            return 0;
        }
    }

    public function getApuntesPorId($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $this->logger->error($this->user_id, '400', 'ID de apunte inválido');
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        $result = $this->callSP("CALL sp_obtener_apunte_por_id()", [$id]);

        if (count($result['result_sets'][0]) == 0) {
            $this->logger->error($this->user_id, '404', 'No se encontró el apunte');
            return ["errno" => 404, "error" => "No se encontró el apunte"];
        }

        return $result['result_sets'][0][0];
    }

    public function create(
        $titulo,
        $descripcion,
        $materia,
        $archivo,           // array estilo $_FILES: ['name' => ..., 'tmp_name' => ...]
        $curso = null,
        $division = null,
        $visibilidad = 'publico'
    ) {
        if (!isset($_SESSION[APP_NAME])) {
            $this->logger->error($this->user_id, '403', 'No autorizado');
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario = $_SESSION[APP_NAME]["user"];
        $usuarioId = (int) $usuario["id"];

        // Completar datos derivados de sesión
        $usuario_cargador_id = $usuarioId;
        $escuela_id = (int) $usuario["escuela_id"];
        $anio_lectivo_id = (int) $usuario["id_anio_lectivo"];

        // Validaciones
        if ($titulo == "") {
            $this->logger->error($this->user_id, '400', 'Falta el título');
            return ["errno" => 400, "error" => "Falta el título"];
        }
        if ($descripcion == "") {
            $this->logger->error($this->user_id, '400', 'Falta la descripción');
            return ["errno" => 400, "error" => "Falta la descripción"];
        }
        if ($materia == "" || !is_numeric($materia)) {
            $this->logger->error($this->user_id, '400', 'Falta el ID de la materia');
            return ["errno" => 400, "error" => "Falta el ID de la materia"];
        }
        if ($anio_lectivo_id == "" || !is_numeric($anio_lectivo_id)) {
            $this->logger->error($this->user_id, '400', 'Falta el ID del año lectivo');
            return ["errno" => 400, "error" => "Falta el ID del año lectivo"];
        }
        if (!in_array($visibilidad, ["publico", "curso"])) {
            $this->logger->error($this->user_id, '400', 'Visibilidad inválida');
            return ["errno" => 400, "error" => "Visibilidad inválida"];
        }
        if (!is_array($archivo) || !isset($archivo['name'], $archivo['tmp_name'])) {
            $this->logger->error($this->user_id, '400', 'Falta el archivo');
            return ["errno" => 400, "error" => "Falta el archivo"];
        }

        // Archivos (nombres temporales)
        $nombreArchivo = $archivo['name'];
        $rutaTemporal = $archivo['tmp_name'];

        // Metadatos: MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $rutaTemporal);
        finfo_close($finfo);

        // Verificar tipo permitido (PDF o imagen)
        $tiposPermitidos = [
            'application/pdf'
            // 'image/jpeg',
            // 'image/png'
            // 'image/gif'
        ];
        if (!in_array($tipoMime, $tiposPermitidos)) {
            $this->logger->error($this->user_id, '400', 'Tipo de archivo no permitido. Solo se aceptan PDF o imágenes.');
            return ["errno" => 400, "error" => "Tipo de archivo no permitido. Solo se aceptan PDF o imágenes."];
        }

        // Normalización de opcionales
        $curso_id = (isset($curso) && is_numeric($curso)) ? (int) $curso : null;
        $nivel = null; // según tu modelo actual
        $division = (isset($division) && $division !== "") ? (string) $division : null;

        // Carpeta destino única por usuario
        $hashUsuario = hash("sha256", (string) $usuarioId);
        $carpetaDestino = "../data/uploads/" . $hashUsuario . "/";
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $nombreArchivo);
        $rutaFinal = $carpetaDestino . uniqid('', true) . '_' . $safeName;

        $sha256 = hash_file('sha256', $rutaTemporal);
        $bytes = filesize($rutaTemporal);

        // Verificar si el archivo ya existe
        $existing = $this->query("SELECT id FROM archivos_apuntes WHERE sha256 = '" . $sha256 . "'");
        if ($existing && count($existing) > 0) {
            $this->logger->error($this->user_id, '409', 'Este archivo ya ha sido subido anteriormente.');
            return ["errno" => 409, "error" => "Este archivo ya ha sido subido anteriormente."];
        }

        // Transacción
        $this->begin();
        try {
            $this->query("SET @apunte_id := 0");
            $this->callSP(
                "CALL sp_crear_apunte(?,?,?,?,?,?,?,?,?,?, @apunte_id)",
                [
                    (string) $titulo,
                    (string) $descripcion,
                    (int) $usuario_cargador_id,
                    (int) $escuela_id,
                    (int) $materia,
                    (int) $anio_lectivo_id,
                    $curso_id,
                    $nivel,
                    $division,
                    (string) $visibilidad
                ],
                ["@apunte_id"]
            );

            $row = $this->query("SELECT @apunte_id AS id");
            $apunte_id = isset($row[0]["id"]) ? (int) $row[0]["id"] : 0;
            if ($apunte_id <= 0) {
                $this->logger->error($this->user_id, '500', 'No se obtuvo el ID del apunte');
                $this->rollback();
                return ["errno" => 500, "error" => "No se obtuvo el ID del apunte"];
            }

            // Mover el archivo físicamente después de confirmar que el apunte se creó
            if (!move_uploaded_file($rutaTemporal, $rutaFinal)) {
                $this->logger->error($this->user_id, '500', 'Error al mover el archivo al destino');
                $this->rollback();
                return ["errno" => 500, "error" => "Error al mover el archivo al destino"];
            }

            $this->query("SET @archivo_id := 0");
            $this->callSP(
                "CALL sp_insert_archivo_apunte(?,?,?,?,?,?,?, @archivo_id)",
                [
                    (int) $apunte_id,
                    (string) $rutaFinal,
                    (string) $tipoMime,
                    (int) $bytes,
                    (string) $sha256,
                    1,
                    (int) $usuarioId
                ],
                ["@archivo_id"]
            );

            $row2 = $this->query("SELECT @archivo_id AS id");
            $archivo_id = isset($row2[0]["id"]) ? (int) $row2[0]["id"] : 0;
            if ($archivo_id <= 0) {
                $this->logger->error($this->user_id, '500', 'No se obtuvo el ID del archivo');
                $this->rollback();
                return ["errno" => 500, "error" => "No se obtuvo el ID del archivo"];
            }

            $this->commit();
            return [
                "errno" => 202,
                "error" => "El archivo se subió correctamente",
                "apunte_id" => $apunte_id,
                "archivo_id" => $archivo_id
            ];
        } catch (Throwable $e) {
            $this->rollback();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'uk_aa_sha') !== false) {
                $this->logger->error($this->user_id, '409', 'Este archivo ya ha sido subido anteriormente.');
                return ["errno" => 409, "error" => "Este archivo ya ha sido subido anteriormente."];
            }
            $this->logger->error($this->user_id, '500', 'DB error: ' . $e->getMessage());
            return ["errno" => 500, "error" => "DB error: " . $e->getMessage()];
        }
    }
public function update($apunte_id, $form)
    {

        $usuario = $_SESSION[APP_NAME]["user"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error($this->user_id, '400', 'ID de apunte inválido');
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (isset($form["titulo"]) && $form["titulo"] == "") {
            $this->logger->error($this->user_id, '400', 'Falta el título');
            return ["errno" => 400, "error" => "Falta el título"];
        }
        if (isset($form["descripcion"]) && $form["descripcion"] == "") {
            $this->logger->error($this->user_id, '400', 'Falta la descripción');
            return ["errno" => 400, "error" => "Falta la descripción"];
        }
        if (isset($form["materia"]) && (!is_numeric($form["materia"]) || $form["materia"] <= 0)) {
            $this->logger->error($this->user_id, '400', 'ID de materia inválido');
            return ["errno" => 400, "error" => "ID de materia inválido"];
        }
        if (isset($form["anio_lectivo_id"]) && (!is_numeric($form["anio_lectivo_id"]) || $form["anio_lectivo_id"] <= 0)) {
            $this->logger->error($this->user_id, '400', 'ID de año lectivo inválido');
            return ["errno" => 400, "error" => "ID de año lectivo inválido"];
        }

        // Si se está actualizando estado, usar método específico
        if (isset($form["estado"])) {
            return $this->updateEstado($apunte_id, $form["estado"], $form["motivo_rechazo"] ?? null);
        }

        // Campos opcionales
        $updates = [];

        $response = $this->callSP(
                "CALL sp_update_apunte(?,?,?)",
                [
                    (string) $form["titulo"],
                    (string) $form["descripcion"],
                    (int) $apunte_id
                ]
            );

        if ($response > 0) {
            return ["errno" => 200, "error" => "Apunte actualizado correctamente"];
        } else {
            return ["errno" => 500, "error" => "Error al crear el apunte"];
        }
    }

    public function delete($apunte_id)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error($this->user_id, '400', 'ID de apunte inválido');
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        $response = $this->callSP("sp_delete_apunte", [$apunte_id]);

        if ($response > 0) {
            return ["errno" => 200, "error" => "Apunte borrado correctamente"];
        } else {
            $this->logger->error($this->user_id, '500', 'Error al borrar el apunte');
            return ["errno" => 500, "error" => "Error al borrar el apunte"];
        }
    }

    // Iniciar procesamiento de documento con IA
    public function startProcessing($apunte_id)
    {
        require_once dirname(__DIR__) . "/libs/DocumentAI.php";

        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error($this->user_id, '400', 'ID de apunte inválido');
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        // Obtener la ruta del archivo desde la BD
        $sql = "SELECT ruta_archivo FROM archivos_apuntes WHERE apunte_id = " . (int)$apunte_id . " AND es_principal = 1";
        $result = $this->query($sql);
        if (!$result || count($result) == 0) {
            $this->logger->error($this->user_id, '404', 'Archivo no encontrado');
            return ["errno" => 404, "error" => "Archivo no encontrado"];
        }
        $ruta_archivo = $result[0]['ruta_archivo'];

        $documentAI = new DocumentAI();
        $processingId = $documentAI->startProcessing($ruta_archivo, $apunte_id);

        return ["errno" => 200, "processing_id" => $processingId];
    }

    // Verificar estado del procesamiento
    public function checkProcessingStatus($processing_id)
    {
        require_once dirname(__DIR__) . "/libs/DocumentAI.php";

        $documentAI = new DocumentAI();
        $status = $documentAI->checkProcessingStatus($processing_id);

        if ($status['status'] === 'completed') {
            // Actualizar estado del apunte basado en el resultado
            $this->updateEstadoFromProcessing($processing_id, $status['result']);
        }

        return $status;
    }

    // Actualizar estado del apunte basado en resultado de IA
    private function updateEstadoFromProcessing($processing_id, $result)
    {
        // Obtener apunte_id del archivo de estado
        $tempDir = sys_get_temp_dir();
        $statusFile = $tempDir . '/' . $processing_id . '.status';
        if (!file_exists($statusFile)) {
            return;
        }
        $status = json_decode(file_get_contents($statusFile), true);
        $apunte_id = $status['apunte_id'];

        $nuevo_estado = $result['status'] === 'approved' ? 'aprobado' : 'rechazado';
        $motivo = isset($result['reason']) ? $result['reason'] : null;

        // Actualizar en BD
        $sql = "UPDATE apuntes SET estado = '" . $nuevo_estado . "', motivo_rechazo = " . ($motivo ? "'" . $motivo . "'" : "NULL") . " WHERE id = " . (int)$apunte_id;
        $this->query($sql);

        return ["errno" => 202, "error" => "Apunte eliminado correctamente"];
    }
}