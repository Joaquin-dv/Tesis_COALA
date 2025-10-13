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
     * Obtiene un apunte por su ID
     */
    public function getApunteById($apunte_id, $formated = false)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apunte_por_id(?)", [$apunte_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "FECHA_CREACION" => $row["FECHA_CREACION"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : null,
                    "IMAGEN" => "",
                    "NOMBRE_USUARIO" => $row["NOMBRE_USUARIO"],
                    "CANTIDAD_PUNTUACIONES" => $row["CANTIDAD_CALIFICACIONES"],
                ];
                // Si el apunte no tiene calificaciones, forzamos a 0 la puntuación
                if ($row["PROMEDIO_CALIFICACIONES"] === null) {
                    $temp_array[0]["PROMEDIO_CALIFICACIONES"] = "0";
                }else{
                    $temp_array[0]["PROMEDIO_CALIFICACIONES"] = number_format((float)$row["PROMEDIO_CALIFICACIONES"], 1);
                }
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    public function getRutaApunteById($apunte_id)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];
        }

        $result = $this->callSP("CALL sp_obtener_ruta_apunte_por_id(?)", [$apunte_id]);

        if (count($result['result_sets'][0]) > 0) {
            return $result['result_sets'][0][0]['RUTA_ARCHIVO'];
        } else {
            return ["errno" => 404, "error" => "No se encontro la ruta del apunte"];
        }
    }

    /**
     * Obtiene todos los apuntes de un alumno por su ID
     */
    public function getApuntesByAlumno($alumno_id, bool $formated = false)
    {
        if (!is_numeric($alumno_id) || $alumno_id <= 0) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del alumno correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apuntes_por_alumno(?)", [$alumno_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "APUNTE_ID" => $row["APUNTE_ID"],
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : "Sin calificar",
                    "IMAGEN" => "",
                    "ESTADO" => $row["ESTADO"],
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
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
            return ["errno" => 500, "error" => "No se obtuvo el ID del alumno correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apuntes_favoritos_por_alumno(?)", [$alumno_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "APUNTE_ID" => $row["APUNTE_ID"],
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : "Sin calificar",
                    "IMAGEN" => "",
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
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

    
    /* registra un nuevo apunte */
    // Create viejo (Guardado temporalmente)
    // public function create($form)
    // {
    //     if (!isset($_SESSION[APP_NAME])) {
    //         return ["errno" => 403, "error" => "No autorizado"];
    //     }

    //     // Usuario logueado
    //     $usuario = $_SESSION[APP_NAME]["user"];
    //     $usuarioId = (int)$usuario["id"];

    //     // Completar datos derivados de sesión
    //     $form["usuario_cargador_id"] = $usuarioId;
    //     $form["escuela_id"]          = (int)$usuario["escuela_id"];
    //     $form["anio_lectivo_id"]     = (int)$usuario["id_anio_lectivo"];
    //     $form["visibilidad"]         = "publico";

    //     // Validaciones
    //     if ($form["titulo"] == "") {return ["errno" => 400, "error" => "Falta el título"];}
    //     if ($form["descripcion"] == "") {return ["errno" => 400, "error" => "Falta la descripción"];}
    //     if ($form["materia"] == "" || !is_numeric($form["materia"])) {return ["errno" => 400, "error" => "Falta el ID de la materia"];}
    //     if ($form["anio_lectivo_id"] == "" || !is_numeric($form["anio_lectivo_id"])) {return ["errno" => 400, "error" => "Falta el ID del año lectivo"];}
    //     if (!in_array($form["visibilidad"], ["publico", "curso"])) {return ["errno" => 400, "error" => "Visibilidad inválida"];}
    //     if (!isset($_FILES['btn_subir_archivo'])) {return ["errno" => 400, "error" => "Falta el archivo"];}

    //     // Normalización de opcionales
    //     $curso_id = (isset($form["curso"]) && is_numeric($form["curso"])) ? (int)$form["curso"] : null;
    //     $nivel    = null; // según tu modelo actual
    //     $division = (isset($form["division"]) && $form["division"] !== "") ? (string)$form["division"] : null;

    //     // Archivos (nombres temporales)
    //     $nombreArchivo = $_FILES['btn_subir_archivo']['name'];
    //     $rutaTemporal  = $_FILES['btn_subir_archivo']['tmp_name'];

    //     // Carpeta destino única por usuario
    //     $hashUsuario     = hash("sha256", (string)$usuarioId);
    //     $carpetaDestino  = "data/uploads/" . $hashUsuario . "/";
    //     if (!file_exists($carpetaDestino)) {
    //         mkdir($carpetaDestino, 0777, true);
    //     }
    //     $safeName  = preg_replace('/[^A-Za-z0-9._-]/', '_', $nombreArchivo);
    //     $rutaFinal = $carpetaDestino . uniqid('', true) . '_' . $safeName;

    //     // Metadatos
    //     $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    //     $tipoMime = finfo_file($finfo, $rutaTemporal);
    //     finfo_close($finfo);

    //     $sha256 = hash_file('sha256', $rutaTemporal);
    //     $bytes  = filesize($rutaTemporal);

    //     // Transacción: DB sólo se confirma si el archivo se movió y ambos SPs ok
    //     $this->begin();
    //     try {
    //         // OUT var
    //         $this->query("SET @apunte_id := 0");

    //         // SP: crear apunte
    //         $this->callSP(
    //             "CALL sp_crear_apunte(?,?,?,?,?,?,?,?,?,?, @apunte_id)",
    //             [
    //                 (string)$form["titulo"],
    //                 (string)$form["descripcion"],
    //                 (int)$form["usuario_cargador_id"],
    //                 (int)$form["escuela_id"],
    //                 (int)$form["materia"],
    //                 (int)$form["anio_lectivo_id"],
    //                 $curso_id,   // puede ser null
    //                 $nivel,      // null
    //                 $division,   // null o string
    //                 (string)$form["visibilidad"]
    //             ],
    //             ["@apunte_id"]
    //         );

    //         $row = $this->query("SELECT @apunte_id AS id");
    //         $apunte_id = isset($row[0]["id"]) ? (int)$row[0]["id"] : 0;
    //         if ($apunte_id <= 0) {
    //             $this->rollback();
    //             return ["errno" => 500, "error" => "No se obtuvo el ID del apunte"];
    //         }

    //         // Mover el archivo físicamente
    //         if (!move_uploaded_file($rutaTemporal, $rutaFinal)) {
    //             $this->rollback();
    //             return ["errno" => 500, "error" => "Error al mover el archivo al destino"];
    //         }

    //         // OUT var para archivo
    //         $this->query("SET @archivo_id := 0");

    //         // SP: insertar metadatos del archivo
    //         $this->callSP(
    //             "CALL sp_insert_archivo_apunte(?,?,?,?,?,?,?, @archivo_id)",
    //             [
    //                 (int)$apunte_id,
    //                 (string)$rutaFinal,
    //                 (string)$tipoMime,
    //                 (int)$bytes,
    //                 (string)$sha256,
    //                 1,                // es_principal
    //                 (int)$usuarioId
    //             ],
    //             ["@archivo_id"]
    //         );

    //         $row2 = $this->query("SELECT @archivo_id AS id");
    //         $archivo_id = isset($row2[0]["id"]) ? (int)$row2[0]["id"] : 0;
    //         if ($archivo_id <= 0) {
    //             $this->rollback();
    //             return ["errno" => 500, "error" => "No se obtuvo el ID del archivo"];
    //         }

    //         // OK
    //         $this->commit();
    //         return [
    //             "errno"      => 202,
    //             "error"      => "El archivo se subió correctamente",
    //             "apunte_id"  => $apunte_id,
    //             "archivo_id" => $archivo_id
    //         ];
    //     } catch (Throwable $e) {
    //         $this->rollback();
    //         return ["errno" => 500, "error" => "DB error: " . $e->getMessage()];
    //     }
    // }


    public function create(
        $titulo,
        $descripcion = null,
        $materia,
        $archivo,           // array estilo $_FILES: ['name' => ..., 'tmp_name' => ...]
        $curso = null,
        $division = null,
        $visibilidad = 'publico'
    ) {
        if (!isset($_SESSION[APP_NAME])) {
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
            return ["errno" => 400, "error" => "Falta el título"];
        }
        if ($descripcion == "") {
            return ["errno" => 400, "error" => "Falta la descripción"];
        }
        if ($materia == "" || !is_numeric($materia)) {
            return ["errno" => 400, "error" => "Falta el ID de la materia"];
        }
        if ($anio_lectivo_id == "" || !is_numeric($anio_lectivo_id)) {
            return ["errno" => 400, "error" => "Falta el ID del año lectivo"];
        }
        if (!in_array($visibilidad, ["publico", "curso"])) {
            return ["errno" => 400, "error" => "Visibilidad inválida"];
        }
        if (!is_array($archivo) || !isset($archivo['name'], $archivo['tmp_name'])) {
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

        // Ruta para guardar en BD (absoluta desde web root)
        $rutaBd = "/data/uploads/" . $hashUsuario . "/" . basename($rutaFinal);

        $sha256 = hash_file('sha256', $rutaTemporal);
        $bytes = filesize($rutaTemporal);

        // Verificar si el archivo ya existe
        $existing = $this->query("SELECT id FROM archivos_apuntes WHERE sha256 = '" . $sha256 . "'");
        if ($existing && count($existing) > 0) {
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
                $this->rollback();
                return ["errno" => 500, "error" => "No se obtuvo el ID del apunte"];
            }

            // Mover el archivo físicamente después de confirmar que el apunte se creó
            if (!move_uploaded_file($rutaTemporal, $rutaFinal)) {
                $this->rollback();
                return ["errno" => 500, "error" => "Error al mover el archivo al destino"];
            }

            $this->query("SET @archivo_id := 0");
            $this->callSP(
                "CALL sp_insert_archivo_apunte(?,?,?,?,?,?,?, @archivo_id)",
                [
                    (int) $apunte_id,
                    (string) $rutaBd,
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
                return ["errno" => 409, "error" => "Este archivo ya ha sido subido anteriormente."];
            }
            return ["errno" => 500, "error" => "DB error: " . $e->getMessage()];
        }
    }

    public function update($apunte_id, $form)
    {

        $usuario = $_SESSION[APP_NAME]["user"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (isset($form["titulo"]) && $form["titulo"] == "") {
            return ["errno" => 400, "error" => "Falta el título"];
        }
        if (isset($form["descripcion"]) && $form["descripcion"] == "") {
            return ["errno" => 400, "error" => "Falta la descripción"];
        }
        // if(isset($form["escuela_id"]) && (!is_numeric($form["escuela_id"]) || $form["escuela_id"] <= 0)){
        //     return ["errno" => 400, "error" => "ID de escuela inválido"];
        // }
        if (isset($form["materia"]) && (!is_numeric($form["materia"]) || $form["materia"] <= 0)) {
            return ["errno" => 400, "error" => "ID de materia inválido"];
        }
        if (isset($form["anio_lectivo_id"]) && (!is_numeric($form["anio_lectivo_id"]) || $form["anio_lectivo_id"] <= 0)) {
            return ["errno" => 400, "error" => "ID de año lectivo inválido"];
        }
        // if(isset($form["visibilidad"]) && !in_array($form["visibilidad"], ["publico", "curso"])){
        //     return ["errno" => 400, "error" => "Visibilidad inválida"];
        // }

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
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        $response = $this->callSP("sp_delete_apunte", [$apunte_id]);

        if ($response > 0) {
            return ["errno" => 200, "error" => "Apunte borrado correctamente"];
        } else {
            return ["errno" => 500, "error" => "Error al borrar el apunte"];
        }
    }

    // Iniciar procesamiento de documento con IA
    public function startProcessing($apunte_id)
    {
        require_once dirname(__DIR__) . "/libs/DocumentAI.php";

        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        // Obtener la ruta del archivo desde la BD
        $sql = "SELECT ruta_archivo FROM archivos_apuntes WHERE apunte_id = " . (int)$apunte_id . " AND es_principal = 1";
        $result = $this->query($sql);
        if (!$result || count($result) == 0) {
            return ["errno" => 404, "error" => "Archivo no encontrado"];
        }
        $ruta_archivo = $result[0]['ruta_archivo'];

        // Convertir ruta web a ruta del sistema de archivos
        $ruta_archivo_fs = $_SERVER['DOCUMENT_ROOT'] . $ruta_archivo;

        $documentAI = new DocumentAI();
        $processingId = $documentAI->startProcessing($ruta_archivo_fs, $apunte_id);

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
    }

    // Método público para actualizar estado manualmente si es necesario
    public function updateEstado($apunte_id, $estado, $motivo = null)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (!in_array($estado, ['pendiente', 'en_revision', 'aprobado', 'rechazado'])) {
            return ["errno" => 400, "error" => "Estado inválido"];
        }

        $sql = "UPDATE apuntes SET estado = '" . $estado . "', motivo_rechazo = " . ($motivo ? "'" . $motivo . "'" : "NULL") . " WHERE id = " . (int)$apunte_id;
        $response = $this->query($sql);

        if ($response) {
            return ["errno" => 200, "error" => "Estado actualizado correctamente"];
        } else {
            return ["errno" => 500, "error" => "Error al actualizar estado"];
        }
    }

    public function getComentariosByApunte($apunte_id, bool $formated = false)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];
        }

        $result = $this->callSP("CALL sp_obtener_comentarios_por_apunte(?)", [$apunte_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "NOMBRE_USUARIO" => $row["NOMBRE_USUARIO"],
                    "TEXTO_COMENTARIO" => $row["TEXTO_COMENTARIO"],
                    "FECHA_CREACION" => $row["FECHA_CREACION"],
                ];
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    public function createComentario($apunte_id, $texto_comentario)
    {
        if (!isset($_SESSION[APP_NAME])) {
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario = $_SESSION[APP_NAME]["user"];
        $usuario_id = (int) $usuario["id"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (empty(trim($texto_comentario))) {
            return ["errno" => 400, "error" => "El comentario no puede estar vacío"];
        }
        if (strlen($texto_comentario) > 500) {
            return ["errno" => 400, "error" => "El comentario es demasiado largo (máximo 500 caracteres)"];
        }

        // Llamar al stored procedure
        $result = $this->callSP("CALL sp_crear_comentario(?,?,?)", [
            (int) $apunte_id,
            (int) $usuario_id,
            (string) trim($texto_comentario)
        ]);

        if ($result && isset($result['result_sets'][0][0]['comentario_id'])) {
            return [
                "errno" => 201,
                "error" => "Comentario creado correctamente",
                "comentario_id" => $result['result_sets'][0][0]['comentario_id']
            ];
        } else {
            return ["errno" => 500, "error" => "Error al crear el comentario"];
        }
    }

    /**
     * Toggle de favorito: agrega si no existe, o cambia el estado activo/inactivo
     * @param int $apunte_id ID del apunte
     * @return array Resultado de la operación con el estado final
     */
    public function toggleFavorito($apunte_id)
    {
        if (!isset($_SESSION[APP_NAME])) {
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario = $_SESSION[APP_NAME]["user"];
        $usuario_id = (int) $usuario["id"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        // Método funcionando correctamente

        // Llamar al stored procedure
        $result = $this->callSP("CALL sp_toggle_favorito(?,?)", [
            (int) $usuario_id,
            (int) $apunte_id
        ]);

        // Método funcionando correctamente

        if ($result && isset($result['result_sets']) && is_array($result['result_sets']) && count($result['result_sets']) > 0) {
            if (isset($result['result_sets'][0]) && is_array($result['result_sets'][0]) && count($result['result_sets'][0]) > 0) {
                $row = $result['result_sets'][0][0];
                if (isset($row['activo'])) {
                    $activo = (int) $row['activo'];

                    return [
                        "errno" => 200,
                        "error" => $activo ? "Apunte agregado a favoritos" : "Apunte removido de favoritos",
                        "activo" => $activo
                    ];
                } else {
                    error_log("Campo 'activo' no encontrado en resultado: " . json_encode($row));
                    return ["errno" => 500, "error" => "Campo 'activo' no encontrado en respuesta"];
                }
            } else {
                error_log("Result set vacío: " . json_encode($result['result_sets']));
                return ["errno" => 500, "error" => "Result set vacío"];
            }
        } else {
            error_log("Resultado inválido del SP: " . json_encode($result));
            return ["errno" => 500, "error" => "Error al cambiar estado de favorito"];
        }
    }

    /**
         * Verifica si un apunte está en favoritos del usuario actual
         * @param int $apunte_id ID del apunte
         * @return bool True si está en favoritos, false si no
         */
        public function esFavorito($apunte_id)
        {
            if (!isset($_SESSION[APP_NAME])) {
                return false;
            }
    
            // Usuario logueado
            $usuario = $_SESSION[APP_NAME]["user"];
            $usuario_id = (int) $usuario["id"];
    
            // Validaciones
            if (!is_numeric($apunte_id) || $apunte_id <= 0) {
                return false;
            }
    
            // Query directa para verificar si existe el favorito activo
            $sql = "SELECT COUNT(*) as count FROM favoritos WHERE usuario_id = " . (int)$usuario_id . " AND apunte_id = " . (int)$apunte_id . " AND activo = 1";
            $result = $this->query($sql);
    
            return isset($result[0]['count']) && (int) $result[0]['count'] > 0;
        }

    /**
     * Busca apuntes con filtros opcionales usando stored procedure
     * @param string $query Texto de búsqueda (título, descripción, materia)
     * @param int|null $anio ID del año lectivo
     * @param string|null $modalidad Modalidad (ej: Informatica, Alimentos)
     * @param string|null $materia Nombre de la materia
     * @param int $limit Límite de resultados
     * @param bool $formated Si formatear para vista
     * @return array
     */
    public function searchApuntes($query = "", $anio = null, $modalidad = null, $materia = null, $limit = 100, bool $formated = false)
    {
        $limit = (int) $limit;

        // Si no hay búsqueda ni filtros, usar getApuntes normal
        if (empty($query) && $anio === null && $modalidad === null && $materia === null) {
            return $this->getApuntes($limit, $formated);
        }

        // Usar query directa para evitar problemas de collation
        $sql = "SELECT a.id AS APUNTE_ID, a.titulo AS TITULO, m.nombre AS MATERIA, e.nombre AS ESCUELA,
                       al.anio AS AÑO, AVG(co.puntuacion) AS PUNTUACION, a.usuario_cargador_id AS USUARIO_ID, c.nivel AS NIVEL_CURSO
                FROM apuntes a
                LEFT JOIN materias m ON a.materia_id = m.id
                LEFT JOIN escuelas e ON a.escuela_id = e.id
                LEFT JOIN anios_lectivos al ON a.anio_lectivo_id = al.id
                LEFT JOIN comentarios co ON a.id = co.apunte_id
                LEFT JOIN cursos c ON a.curso_id = c.id
                WHERE a.estado = 'aprobado'";

        if (!empty($query)) {
            $searchTerm = "%" . addslashes($query) . "%";
            $sql .= " AND (a.titulo LIKE '$searchTerm' OR a.descripcion LIKE '$searchTerm' OR m.nombre LIKE '$searchTerm')";
        }

        if ($anio !== null && is_numeric($anio)) {
            $sql .= " AND c.nivel = " . (int)$anio;
        }

        if ($materia !== null) {
            $materiaEscaped = addslashes($materia);
            $sql .= " AND m.nombre = '$materiaEscaped'";
        }

        $sql .= " GROUP BY a.id ORDER BY a.creado_en DESC LIMIT " . (int)$limit;

        $result = $this->query($sql);
        if ($formated === true) {
            $temp_array = [];
            foreach ($result as $row) {
                $temp_array[] = [
                    "APUNTE_ID" => $row["APUNTE_ID"],
                    "TITULO" => $row["TITULO"],
                    "MATERIA" => $row["MATERIA"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : null,
                    "IMAGEN" => "",
                    "USUARIO_ID" => $row["USUARIO_ID"],
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
                ];
            }
            return $temp_array;
        }

        return $result['result_sets'][0];
    }

    /**
     * Obtiene todos los años lectivos disponibles
     * @return array Lista de años con ID y nombre
     */
    public function getAniosLectivos()
    {
        $result = $this->callSP("CALL sp_obtener_anios_lectivos()");
        return $result['result_sets'][0];
    }

    /**
     * Obtiene las materias disponibles para un año lectivo específico
     * @param int $anio_id ID del año lectivo
     * @return array Lista de materias
     */
    public function getMateriasPorAnio($anio)
    {
        if (!is_numeric($anio) || $anio <= 0) {
            return [];
        }

        $result = $this->callSP("CALL sp_obtener_materias_por_anio(?)", [$anio]);
        return $result['result_sets'][0];
    }

}

