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

    public function __construct()
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
                    "IMAGEN" => $this->getRutaThumbnailByIdApunte($row["APUNTE_ID"]) ?? "/views/static/img/inicio/foto_apunte.webp",
                    "USUARIO_ID" => $row["USUARIO_ID"],
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
                    "COMPONENTE_ESTADO" => "", // se asigna luego en el controlador
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
            $this->logger->error('','500',"No se obtuvo el ID del apunte correctamente");
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];;
        }

        $result = $this->callSP("CALL sp_obtener_apunte_por_id(?)", [$apunte_id]);

        if ($formated === true) {
            $temp_array = [];
            foreach ($result['result_sets'][0] as $row) {
                $temp_array[] = [
                    "TITULO" => $row["TITULO"],
                    "DESCRIPCION" => empty($row["DESCRIPCION"]) ? "Sin descripción" : $row["DESCRIPCION"],
                    "MATERIA" => $row["MATERIA"],
                    "FECHA_CREACION" => $row["FECHA_CREACION"],
                    "ESCUELA" => $row["ESCUELA"],
                    "AÑO" => $row["AÑO"],
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (float) $row["PUNTUACION"] : null,
                    "IMAGEN" => "",
                    "NOMBRE_AUTOR" => $row["NOMBRE_USUARIO"],
                    "CANTIDAD_PUNTUACIONES" => $row["CANTIDAD_CALIFICACIONES"],
                    "CANTIDAD_VISTAS" => $row["CANTIDAD_VISTAS"],
                ];
                // Si el apunte no tiene calificaciones, forzamos a 0 la puntuación
                if ($row["PROMEDIO_CALIFICACIONES"] === null) {
                    $temp_array[0]["PROMEDIO_CALIFICACIONES"] = "0";
                } else {
                    $temp_array[0]["PROMEDIO_CALIFICACIONES"] = number_format((float)$row["PROMEDIO_CALIFICACIONES"], 1);
                }
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    // Sumar vistas
    public function incrementarVisitas($apunte_id, $usuario_id = null)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];
        }

        if(!is_null($usuario_id) && !isset($_SESSION[APP_NAME])){
            return ["errno" => 403, "error" => "No autorizado"];
        }

        if ($usuario_id !== null && (!is_numeric($usuario_id) || $usuario_id <= 0)) {
            return ["errno" => 500, "error" => "No se obtuvo el ID del usuario correctamente"];
        }
        
        $this->callSP("CALL sp_sumar_vista_apunte(?,?)", [$apunte_id, $usuario_id]);
        return ["errno" => 200, "error" => "Visitas incrementadas"];
    }

    public function getRutaApunteById($apunte_id)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error('','500',"No se obtuvo el ID del apunte correctamente");
            return ["errno" => 500, "error" => "No se obtuvo el ID del apunte correctamente"];
        }

        $result = $this->callSP("CALL sp_obtener_ruta_apunte_por_id(?)", [$apunte_id]);

        if (count($result['result_sets'][0]) > 0) {
            return $result['result_sets'][0][0]['RUTA_ARCHIVO'];
        } else {
            $this->logger->error('','404',"No se encontro la ruta del apunte");
            return ["errno" => 404, "error" => "No se encontro la ruta del apunte"];
        }
    }

    public function getRutaThumbnailByIdApunte($apunte_id){
        $rutaArchivo = $this->getRutaApunteById($apunte_id);
        if(isset($rutaArchivo["errno"])){
            return null;
        }
            $ultimaBarra = strrpos($rutaArchivo, '/');

            $thumbnailPath = substr($rutaArchivo, 0, $ultimaBarra + 1) . 'thumbnail.jpg';
            $thumbnailPathFS = $_SERVER['DOCUMENT_ROOT'] . $thumbnailPath;
            if (!file_exists($thumbnailPathFS)) {
                return null;
            }
            return $thumbnailPath;

    }

    /**
     * Obtiene todos los apuntes de un alumno por su ID
     */
    public function getApuntesByAlumno($alumno_id, bool $formated = false)
    {
        if (!is_numeric($alumno_id) || $alumno_id <= 0) {
            $this->logger->error('','500',"No se obtuvo el ID del alumno correctamente");
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
                    "IMAGEN" => $this->getRutaThumbnailByIdApunte($row["APUNTE_ID"]) ?? "/views/static/img/inicio/foto_apunte.webp",
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
            $this->logger->error('','500',"No se obtuvo el ID del alumno correctamente");
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
                    "IMAGEN" => $this->getRutaThumbnailByIdApunte($row["APUNTE_ID"]) ?? "/views/static/img/inicio/foto_apunte.webp",
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


    /**
     * Genera y asegura la carpeta destino y las rutas de archivo/BD.
     * Estructura: data/uploads/<hashUsuario>/<hashApunte>/Apunte.pdf y thumbnail.jpg
     */
    // Reemplaza tu función por esta
    private function generarRutasArchivo(int $usuarioId, int $apunteId, string $titulo, string $extension = 'pdf'): array
    {
        $hashUsuario = hash('sha256', (string) $usuarioId);
        $hashApunte  = hash('sha256', (string) $apunteId);

        $carpetaDestino = "../data/uploads/{$hashUsuario}/{$hashApunte}/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        // --- nombre de archivo desde el título ---
        $base = trim((string)$titulo);
        // permitir letras, números, espacios, punto, guion y guion bajo; lo demás a "_"
        $base = preg_replace('/[^\p{L}\p{N}\s._-]/u', '_', $base);
        // colapsar espacios y reemplazarlos por "_"
        $base = preg_replace('/\s+/', '_', $base);
        // por si queda vacío
        if ($base === '' || $base === '_') {
            $base = 'Apunte';
        }
        // (opcional simple) recortar a un tamaño razonable
        if (strlen($base) > 120) {
            $base = substr($base, 0, 120);
        }

        $nombreArchivoFinal   = $base . '.' . strtolower($extension);
        $nombreThumbnailFinal = "thumbnail.jpg";

        $rutaFisicaPdf   = $carpetaDestino . $nombreArchivoFinal;
        $rutaFisicaThumb = $carpetaDestino . $nombreThumbnailFinal;

        // Rutas para la base de datos (desde web root)
        $rutaBdPdf   = "/data/uploads/{$hashUsuario}/{$hashApunte}/{$nombreArchivoFinal}";
        $rutaBdThumb = "/data/uploads/{$hashUsuario}/{$hashApunte}/thumbnail.jpg";

        return [
            'carpeta_destino'   => $carpetaDestino,
            'ruta_fisica_pdf'   => $rutaFisicaPdf,
            'ruta_fisica_thumb' => $rutaFisicaThumb,
            'ruta_bd_pdf'       => $rutaBdPdf,
            'ruta_bd_thumb'     => $rutaBdThumb,
        ];
    }

    
    /* registra un nuevo apunte */

    public function create(
        $titulo,
        $materia,
        $archivos,          // array de archivos o array estilo $_FILES
        $descripcion = null,
        $curso = null,
        $division = null,
        $visibilidad = 'publico'
    ) {
        if (!isset($_SESSION[APP_NAME])) {
            $this->logger->error('','403',"No autorizado");
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario   = $_SESSION[APP_NAME]["user"];
        $usuarioId = (int) $usuario["id"];

        // Completar datos derivados de sesión
        $usuario_cargador_id = $usuarioId;
        $escuela_id          = (int) $usuario["escuela_id"];
        $anio_lectivo_id     = (int) $usuario["id_anio_lectivo"];

        // Validaciones
        if ($titulo == "") {
            $this->logger->error($usuarioId,'400',"Falta el título");
            return ["errno" => 400, "error" => "Falta el título"];
        }
        // if ($descripcion == "") {
        //     $this->logger->error($usuarioId,'400',"Falta la descripción");
        //     return ["errno" => 400, "error" => "Falta la descripción"];
        // }
        if ($materia == "" || !is_numeric($materia)) {
            $this->logger->error($usuarioId,'400',"Falta el ID de la materia");
            return ["errno" => 400, "error" => "Falta el ID de la materia"];
        }
        if ($anio_lectivo_id == "" || !is_numeric($anio_lectivo_id)) {
            $this->logger->error($usuarioId,'400',"Falta el ID del año lectivo");
            return ["errno" => 400, "error" => "Falta el ID del año lectivo"];
        }
        if (!in_array($visibilidad, ["publico", "curso"])) {
            $this->logger->error($usuarioId,'400',"Visibilidad inválida");
            return ["errno" => 400, "error" => "Visibilidad inválida"];
        }
        if (empty($archivos)) {
            $this->logger->error($usuarioId,'400',"Falta el archivo");
            return ["errno" => 400, "error" => "Falta el archivo"];
        }

        // Determinar si es un array de archivos o un solo archivo
        $esArrayArchivos = is_array($archivos) && isset($archivos[0]) && is_array($archivos[0]);
        if (!$esArrayArchivos) {
            // Convertir archivo único a array para unificar lógica
            $archivos = [$archivos];
        }

        // Determinar tipo de archivos y procesar
        $primerArchivo = $archivos[0];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMimePrimerArchivo = finfo_file($finfo, $primerArchivo['tmp_name']);
        finfo_close($finfo);

        $esImagen = strpos($tipoMimePrimerArchivo, 'image/') === 0;
        $esPDF = $tipoMimePrimerArchivo === 'application/pdf';

        if (!$esImagen && !$esPDF) {
            $this->logger->error($usuarioId,'400',"Tipo de archivo no permitido. Solo se aceptan imágenes o PDF.");
            return ["errno" => 400, "error" => "Tipo de archivo no permitido. Solo se aceptan imágenes o PDF."];
        }

        // Si son imágenes, crear PDF primero
        if ($esImagen) {
            // Verificar que todos sean imágenes
            foreach ($archivos as $archivo) {
                $tipoMime = finfo_file($finfo, $archivo['tmp_name']);
                finfo_close($finfo);
                if (strpos($tipoMime, 'image/') !== 0) {
                    $this->logger->error($usuarioId,'400',"Todos los archivos deben ser imágenes si se suben múltiples.");
                    return ["errno" => 400, "error" => "Todos los archivos deben ser imágenes si se suben múltiples."];
                }
            }

            // Generar rutas temporales para el PDF
            $tempDir = sys_get_temp_dir();
            $tempPdfName = 'temp_' . uniqid() . '.pdf';
            $pdfTemporalPath = $tempDir . DIRECTORY_SEPARATOR . $tempPdfName;

            // Crear PDF desde imágenes
            $imagePaths = array_map(function($archivo) {
                return $archivo['tmp_name'];
            }, $archivos);

            $generadorPDF = new ThumbnailGenerator($tempDir);
            if (!$generadorPDF->generatePDFFromImages($imagePaths, $pdfTemporalPath)) {
                $this->logger->error($usuarioId,'500',"Error al crear PDF desde imágenes. Revisa los logs de error para más detalles.");
                return ["errno" => 500, "error" => "Error al crear PDF desde imágenes. Revisa los logs de error para más detalles."];
            }

            // Ahora tratar como si fuera un PDF único
            $archivoFinal = [
                'name' => $titulo . '.pdf',
                'tmp_name' => $pdfTemporalPath,
                'type' => 'application/pdf',
                'size' => filesize($pdfTemporalPath)
            ];
            $tipoMime = 'application/pdf';
        } else {
            // Es PDF único
            if (count($archivos) > 1) {
                $this->logger->error($usuarioId,'400',"Solo se permite un archivo PDF.");
                return ["errno" => 400, "error" => "Solo se permite un archivo PDF."];
            }
            $archivoFinal = $archivos[0];
            $tipoMime = $tipoMimePrimerArchivo;
        }

        // Archivos (nombres temporales)
        $nombreArchivo = $archivoFinal['name'];
        $rutaTemporal  = $archivoFinal['tmp_name'];

        // Normalización de opcionales
        $curso_id = (isset($curso) && is_numeric($curso)) ? (int) $curso : null;
        $nivel    = null; // según tu modelo actual
        $division = (isset($division) && $division !== "") ? (string) $division : null;

        // Calcular hash y tamaño ANTES de mover
        $sha256 = hash_file('sha256', $rutaTemporal);
        $bytes  = filesize($rutaTemporal);

        // Evitar duplicado exacto por hash
        $existing = $this->query("SELECT id FROM archivos_apuntes WHERE sha256 = '" . $sha256 . "'");
        if ($existing && count($existing) > 0) {
            $this->logger->error($usuarioId,'409',"Este archivo ya ha sido subido anteriormente.");
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
                $this->logger->error($usuarioId,'500',"No se obtuvo el ID del apunte");
                return ["errno" => 500, "error" => "No se obtuvo el ID del apunte"];
            }

            // Generar rutas finales (PDF y thumbnail) ahora que ya tenemos $apunte_id
            $rutas = $this->generarRutasArchivo($usuarioId, $apunte_id, $titulo, 'pdf');

            // Mover el archivo físicamente (a .../Apunte.pdf)
            if ($esImagen) {
                // Para archivos generados desde imágenes, usar copy en lugar de move_uploaded_file
                if (!copy($rutaTemporal, $rutas['ruta_fisica_pdf'])) {
                    $this->rollback();
                    $this->logger->error($usuarioId,'500',"Error al copiar el archivo generado al destino");
                    return ["errno" => 500, "error" => "Error al copiar el archivo generado al destino"];
                }

                // Verificar que el archivo copiado sea válido
                if (!file_exists($rutas['ruta_fisica_pdf']) || filesize($rutas['ruta_fisica_pdf']) === 0) {
                    $this->rollback();
                    $this->logger->error($usuarioId,'500',"El archivo copiado está vacío o no existe");
                    return ["errno" => 500, "error" => "El archivo copiado está vacío o no existe"];
                }

                // Verificar que sea un PDF válido
                $pdfContent = file_get_contents($rutas['ruta_fisica_pdf']);
                if (strpos($pdfContent, '%PDF-') !== 0) {
                    $this->rollback();
                    $this->logger->error($usuarioId,'500',"El archivo copiado no es un PDF válido");
                    return ["errno" => 500, "error" => "El archivo copiado no es un PDF válido"];
                }

                error_log("PDF final verification: size=" . filesize($rutas['ruta_fisica_pdf']) . ", valid PDF header: " . (strpos($pdfContent, '%PDF-') === 0 ? 'yes' : 'no'));

            } else {
                // Para archivos subidos normalmente, usar move_uploaded_file
                if (!move_uploaded_file($rutaTemporal, $rutas['ruta_fisica_pdf'])) {
                    $this->rollback();
                    $this->logger->error($usuarioId,'500',"Error al mover el archivo al destino");
                    return ["errno" => 500, "error" => "Error al mover el archivo al destino"];
                }
            }

            // ---- Generación de thumbnail (no crítica) ----
            try {
                $rutaPdfReal = realpath($rutas['ruta_fisica_pdf']);

                $generadorThumb = new ThumbnailGenerator($rutas['carpeta_destino']);
                $rutaThumbGenerada = $generadorThumb->generateFromPDF($rutaPdfReal, 'thumbnail');
            } catch (Throwable $eThumb) {
                // Log error but don't fail the upload
                $this->logger->error($usuarioId,'500',"Error generando thumbnail: " . $eThumb->getMessage());
            }

            // Limpiar archivos temporales si se crearon desde imágenes
            if ($esImagen && isset($pdfTemporalPath) && file_exists($pdfTemporalPath)) {
                unlink($pdfTemporalPath);
            }

            // Para archivos de imagen, cambiar el tipo MIME apropiado
            if ($esImagen) {
                $tipoMime = 'application/pdf';
            }

            // Guardar registro de archivo principal (PDF)
            $this->query("SET @archivo_id := 0");
            $this->callSP(
                "CALL sp_insert_archivo_apunte(?,?,?,?,?,?,?, @archivo_id)",
                [
                    (int) $apunte_id,
                    (string) $rutas['ruta_bd_pdf'],
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
                $this->logger->error($usuarioId,'500',"No se obtuvo el ID del archivo");
                return ["errno" => 500, "error" => "No se obtuvo el ID del archivo"];
            }

            $this->commit();
             $this->logger->creacion($usuarioId,'apunte',$apunte_id);
            return [
                "errno"       => 202,
                "error"       => "El archivo se subió correctamente",
                "apunte_id"   => $apunte_id,
                "archivo_id"  => $archivo_id
            ];
        } catch (Throwable $e) {
            $this->rollback();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'uk_aa_sha') !== false) {
                $this->logger->error($usuarioId,'409',"Este archivo ya ha sido subido anteriormente.");
                return ["errno" => 409, "error" => "Este archivo ya ha sido subido anteriormente."];
            }
            $this->logger->error($usuarioId,'500',"DB error: " . $e->getMessage());
            return ["errno" => 500, "error" => "DB error: " . $e->getMessage()];
        }
    }


    public function update($apunte_id, $form)
    {

        $usuario = $_SESSION[APP_NAME]["user"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (isset($form["titulo"]) && $form["titulo"] == "") {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'400',"Falta el título");
            return ["errno" => 400, "error" => "Falta el título"];
        }
        if (isset($form["descripcion"]) && $form["descripcion"] == "") {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'400',"Falta la descripción");
            return ["errno" => 400, "error" => "Falta la descripción"];
        }
        // if(isset($form["escuela_id"]) && (!is_numeric($form["escuela_id"]) || $form["escuela_id"] <= 0)){
        //     return ["errno" => 400, "error" => "ID de escuela inválido"];
        // }
        if (isset($form["materia"]) && (!is_numeric($form["materia"]) || $form["materia"] <= 0)) {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'400',"ID de materia inválido");
            return ["errno" => 400, "error" => "ID de materia inválido"];
        }
        if (isset($form["anio_lectivo_id"]) && (!is_numeric($form["anio_lectivo_id"]) || $form["anio_lectivo_id"] <= 0)) {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'400',"ID de año lectivo inválido");
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
            $this->logger->modificacion(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'apunte',$apunte_id);
            return ["errno" => 200, "error" => "Apunte actualizado correctamente"];
        } else {
            $this->logger->error(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'500',"Error al crear el apunte");
            return ["errno" => 500, "error" => "Error al crear el apunte"];
        }
    }

    public function delete($apunte_id)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error('','400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        $response = $this->callSP("sp_delete_apunte", [$apunte_id]);

        if ($response > 0) {
            $this->logger->eliminacion(isset($usuario_id)?$usuario_id:(isset($usuarioId)?$usuarioId:''),'apunte',$apunte_id);
            return ["errno" => 200, "error" => "Apunte borrado correctamente"];
        } else {
            $this->logger->error('','500',"Error al borrar el apunte");
            return ["errno" => 500, "error" => "Error al borrar el apunte"];
        }
    }

    // Iniciar procesamiento de documento con IA
    public function startProcessing($apunte_id)
    {
        require_once dirname(__DIR__) . "/libs/DocumentAI.php";

        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error('','400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        // Obtener la ruta del archivo desde la BD
        $sql = "SELECT ruta_archivo FROM archivos_apuntes WHERE apunte_id = " . (int)$apunte_id . " AND es_principal = 1";
        $result = $this->query($sql);
        if (!$result || count($result) == 0) {
            $this->logger->error('','404',"Archivo no encontrado");
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
            $this->logger->error('','400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (!in_array($estado, ['pendiente', 'en_revision', 'aprobado', 'rechazado'])) {
            $this->logger->error('','400',"Estado inválido");
            return ["errno" => 400, "error" => "Estado inválido"];
        }

        $sql = "UPDATE apuntes SET estado = '" . $estado . "', motivo_rechazo = " . ($motivo ? "'" . $motivo . "'" : "NULL") . " WHERE id = " . (int)$apunte_id;
        $response = $this->query($sql);

        if ($response) {
            return ["errno" => 200, "error" => "Estado actualizado correctamente"];
        } else {
            $this->logger->error('','500',"Error al actualizar estado");
            return ["errno" => 500, "error" => "Error al actualizar estado"];
        }
    }

    public function getComentariosByApunte($apunte_id, bool $formated = false)
    {
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error('','500',"No se obtuvo el ID del apunte correctamente");
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
                    "PUNTUACION" => isset($row["PUNTUACION"]) ? (int) $row["PUNTUACION"] : null,
                ];
            }
            return $temp_array;
        }

        // Si no querés formateo, devolvés el resultset crudo
        return $result['result_sets'][0];
    }

    public function createComentario($apunte_id, $texto_comentario, $puntuacion = null)
    {
        if (!isset($_SESSION[APP_NAME])) {
            $this->logger->error('','403',"No autorizado");
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario = $_SESSION[APP_NAME]["user"];
        $usuario_id = (int) $usuario["id"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error($usuario_id,'400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }
        if (empty(trim($texto_comentario))) {
            $this->logger->error($usuario_id,'400',"El comentario no puede estar vacío");
            return ["errno" => 400, "error" => "El comentario no puede estar vacío"];
        }
        if (strlen($texto_comentario) > 500) {
            $this->logger->error($usuario_id,'400',"El comentario es demasiado largo (máximo 500 caracteres)");
            return ["errno" => 400, "error" => "El comentario es demasiado largo (máximo 500 caracteres)"];
        }
        if ($puntuacion !== null && (!is_numeric($puntuacion) || $puntuacion < 1 || $puntuacion > 5)) {
            $this->logger->error($usuario_id,'400',"La puntuación debe ser entre 1 y 5");
            return ["errno" => 400, "error" => "La puntuación debe ser entre 1 y 5"];
        }
        // Debug: verificar parámetros
        
        // Llamar al stored procedure con puntuación
        $result = $this->callSP("CALL sp_crear_comentario(?,?,?,?)", [
            (int) $apunte_id,
            (int) $usuario_id,
            (string) trim($texto_comentario),
            $puntuacion !== null ? (int) $puntuacion : null
        ]);
        
        // Debug: verificar resultado

        if ($result && isset($result['result_sets'][0][0]['comentario_id'])) {
            return [
                "errno" => 201,
                "error" => "Comentario creado correctamente",
                "comentario_id" => $result['result_sets'][0][0]['comentario_id']
            ];
        } else {
            $this->logger->error($usuario_id,'500',"Error al crear el comentario");
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
            $this->logger->error('','403',"No autorizado");
            return ["errno" => 403, "error" => "No autorizado"];
        }

        // Usuario logueado
        $usuario = $_SESSION[APP_NAME]["user"];
        $usuario_id = (int) $usuario["id"];

        // Validaciones
        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            $this->logger->error($usuario_id,'400',"ID de apunte inválido");
            return ["errno" => 400, "error" => "ID de apunte inválido"];
        }

        // Llamar al stored procedure
        $result = $this->callSP("CALL sp_toggle_favorito(?,?)", [
            (int) $usuario_id,
            (int) $apunte_id
        ]);

        // Método funcionando correctamente

        if ($result && isset($result['result_sets']) && is_array($result['result_sets']) && count($result['result_sets']) > 0) {
            if (isset($result['result_sets'][0]) && is_array($result['result_sets'][0]) && count($result['result_sets'][0]) > 0) {
                $row = $result['result_sets'][0][0];
                if (isset($row['errno']) && isset($row['error'])) {
                    $errno = (int) $row['errno'];
                    $error_msg = $row['error'];
                    $activo = isset($row['activo']) ? $row['activo'] : null;

                    return [
                        "errno" => $errno,
                        "error" => $error_msg,
                        "activo" => $activo
                    ];
                } else {
                    error_log("Campos requeridos no encontrados en resultado: " . json_encode($row));
                    return ["errno" => 500, "error" => "Respuesta del procedimiento almacenado inválida"];
                }
            } else {
                error_log("Result set vacío: " . json_encode($result['result_sets']));
                $this->logger->error($usuario_id,'500',"Result set vacío");
                return ["errno" => 500, "error" => "Result set vacío"];
            }
        } else {
            error_log("Resultado inválido del SP: " . json_encode($result));
            $this->logger->error($usuario_id,'500',"Error al cambiar estado de favorito");
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
     * Obtiene la puntuación del usuario actual para un apunte
     * @param int $apunte_id ID del apunte
     * @return int|null Puntuación del usuario o null si no ha puntuado
     */
    public function getPuntuacionUsuario($apunte_id)
    {
        if (!isset($_SESSION[APP_NAME])) {
            return null;
        }

        $usuario = $_SESSION[APP_NAME]["user"];
        $usuario_id = (int) $usuario["id"];

        if (!is_numeric($apunte_id) || $apunte_id <= 0) {
            return null;
        }

        $sql = "SELECT puntuacion FROM comentarios WHERE usuario_id = " . (int)$usuario_id . " AND apunte_id = " . (int)$apunte_id . " AND puntuacion IS NOT NULL LIMIT 1";
        $result = $this->query($sql);

        return isset($result[0]['puntuacion']) ? (int) $result[0]['puntuacion'] : null;
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
                    "IMAGEN" => $this->getRutaThumbnailByIdApunte($row["APUNTE_ID"]) ?? "/views/static/img/inicio/foto_apunte.webp",
                    "USUARIO_ID" => $row["USUARIO_ID"],
                    "NIVEL_CURSO" => $row["NIVEL_CURSO"],
                    "COMPONENTE_ESTADO" => "",
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
