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
        private $materia_id;
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
            $this->materia_id = 0;
            $this->anio_lectivo_id = 0;
            $this->curso_id = null;
            $this->nivel = null;
            $this->division = null;
            $this->visibilidad = "publico";
            $this->estado = "pendiente";
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
        public function getCant(){
            
            // query("CALL getCant()");

            return count($this->query("SELECT * FROM `apuntes`"));
        }
        
        
        /* registra un nuevo apunte */
        public function create($form){

            if(!isset($_SESSION[APP_NAME])){
                return ["errno" => 403, "error" => "No autorizado"];
            }

            $usuario = $_SESSION[APP_NAME]["user"];
            // var_dump($usuario);
            // Validaciones
            if($form["titulo"] == ""){
                return ["errno" => 400, "error" => "Falta el título"];
            }
            if($form["descripcion"] == ""){
                return ["errno" => 400, "error" => "Falta la descripción"];
            }

            $form["usuario_cargador_id"] = $usuario->email;
            
            $form["escuela_id"] = $usuario->getSchoolID();
            
            if($form["materia_id"] == "" || !is_numeric($form["materia_id"])){
                return ["errno" => 400, "error" => "Falta el ID de la materia"];
            }
            if($form["anio_lectivo_id"] == "" || !is_numeric($form["anio_lectivo_id"])){
                return ["errno" => 400, "error" => "Falta el ID del año lectivo"];
            }
            if(!in_array($form["visibilidad"], ["publico", "curso"])){
                return ["errno" => 400, "error" => "Visibilidad inválida"];
            }

            // Campos opcionales
            $curso_id = is_numeric($form["curso_id"]) ? $form["curso_id"] : "NULL";
            $nivel = is_numeric($form["nivel"]) ? $form["nivel"] : "NULL";
            $division = ($form["division"] != "") ? "'".$form["division"]."'" : "NULL";

            // Insertar en la base de datos
            $sql = "INSERT INTO `apuntes` (`id`, `titulo`, `descripcion`, `usuario_cargador_id`, `escuela_id`, `materia_id`, `anio_lectivo_id`, `curso_id`, `nivel`, `division`, `visibilidad`, `estado`, `verificado_por_docente`, `verificado_por_usuario_id`, `verificado_en`, `estado_ia`, `motivo_rechazo`, `creado_en`, `actualizado_en`, `borrado_en`) 
                    VALUES (NULL, '".$form["titulo"]."', '".$form["descripcion"]."', ".$form["usuario_cargador_id"].", ".$form["escuela_id"].", ".$form["materia_id"].", ".$form["anio_lectivo_id"].", ".$curso_id.", ".$nivel.", ".$division.", '".$form["visibilidad"]."', 'pendiente', 0, NULL, NULL, 'no_escaneado', NULL, current_timestamp(), current_timestamp(), NULL);";
            
            $response = $this->query($sql);

            if($response > 0){
                return ["errno" => 202, "error" => "Apunte creado correctamente", "apunte_id" => $response];
            } else {
                return ["errno" => 500, "error" => "Error al crear el apunte"];
            }
        }

        public function update($apunte_id, $form){
            // Validaciones
            if(!is_numeric($apunte_id) || $apunte_id <= 0){
                return ["errno" => 400, "error" => "ID de apunte inválido"];
            }
            if(isset($form["titulo"]) && $form["titulo"] == ""){
                return ["errno" => 400, "error" => "Falta el título"];
            }
            if(isset($form["descripcion"]) && $form["descripcion"] == ""){
                return ["errno" => 400, "error" => "Falta la descripción"];
            }
            if(isset($form["escuela_id"]) && (!is_numeric($form["escuela_id"]) || $form["escuela_id"] <= 0)){
                return ["errno" => 400, "error" => "ID de escuela inválido"];
            }
            if(isset($form["materia_id"]) && (!is_numeric($form["materia_id"]) || $form["materia_id"] <= 0)){
                return ["errno" => 400, "error" => "ID de materia inválido"];
            }
            if(isset($form["anio_lectivo_id"]) && (!is_numeric($form["anio_lectivo_id"]) || $form["anio_lectivo_id"] <= 0)){
                return ["errno" => 400, "error" => "ID de año lectivo inválido"];
            }
            if(isset($form["visibilidad"]) && !in_array($form["visibilidad"], ["publico", "curso"])){
                return ["errno" => 400, "error" => "Visibilidad inválida"];
            }

            // Campos opcionales
            $updates = [];
        }

        public function delete($apunte_id){
            if(!is_numeric($apunte_id) || $apunte_id <= 0){
                return ["errno" => 400, "error" => "ID de apunte inválido"];
            }

            $sql = "UPDATE `apuntes` SET `borrado_en` = current_timestamp() WHERE `id` = ".$apunte_id." AND `borrado_en` IS NULL;";
            $this->query($sql);

            return ["errno" => 202, "error" => "Apunte eliminado correctamente"];
        }

        /**
         * Obtiene una lista de apuntes con paginación y filtros opcionales
         * 
         * @param int $page Página actual (por defecto 1)
         * @param int $limit Límite de apuntes por página (por defecto 10)
         * @param array $filters Filtros opcionales (materia_id, escuela_id, anio_lectivo_id, etc.)
         * @return array Lista de apuntes con información de paginación
         */
        public function getApuntes($page = 1, $limit = 10, $filters = []){
            // Validar parámetros
            $page = max(1, intval($page));
            $limit = max(1, min(50, intval($limit))); // Máximo 50 por página
            $offset = ($page - 1) * $limit;

            // Construir la consulta base
            $sql = "SELECT a.*, 
                           m.nombre as materia_nombre,
                           e.nombre as escuela_nombre,
                           al.anio as anio_lectivo,
                           u.nombre as usuario_nombre
                    FROM `apuntes` a
                    LEFT JOIN `materias` m ON a.materia_id = m.id
                    LEFT JOIN `escuelas` e ON a.escuela_id = e.id
                    LEFT JOIN `anios_lectivos` al ON a.anio_lectivo_id = al.id
                    LEFT JOIN `usuarios` u ON a.usuario_cargador_id = u.id
                    WHERE a.`borrado_en` IS NULL 
                    AND a.`estado` = 'aprobado'";

            // Aplicar filtros de forma segura
            if(isset($filters['materia_id']) && is_numeric($filters['materia_id'])){
                $sql .= " AND a.materia_id = " . intval($filters['materia_id']);
            }
            if(isset($filters['escuela_id']) && is_numeric($filters['escuela_id'])){
                $sql .= " AND a.escuela_id = " . intval($filters['escuela_id']);
            }
            if(isset($filters['anio_lectivo_id']) && is_numeric($filters['anio_lectivo_id'])){
                $sql .= " AND a.anio_lectivo_id = " . intval($filters['anio_lectivo_id']);
            }
            if(isset($filters['busqueda']) && !empty($filters['busqueda'])){
                $busqueda = addslashes($filters['busqueda']);
                $sql .= " AND (a.titulo LIKE '%" . $busqueda . "%' OR a.descripcion LIKE '%" . $busqueda . "%')";
            }

            // Ordenar por fecha de creación (más recientes primero)
            $sql .= " ORDER BY a.creado_en DESC";

            // Agregar paginación
            $sql .= " LIMIT " . $limit . " OFFSET " . $offset;

            // Ejecutar consulta
            $apuntes = $this->query($sql);

            // Obtener el total de registros para la paginación
            $countSql = "SELECT COUNT(*) as total FROM `apuntes` a WHERE a.`borrado_en` IS NULL AND a.`estado` = 'aprobado'";
            
            // Aplicar los mismos filtros para el conteo
            if(isset($filters['materia_id']) && is_numeric($filters['materia_id'])){
                $countSql .= " AND a.materia_id = " . intval($filters['materia_id']);
            }
            if(isset($filters['escuela_id']) && is_numeric($filters['escuela_id'])){
                $countSql .= " AND a.escuela_id = " . intval($filters['escuela_id']);
            }
            if(isset($filters['anio_lectivo_id']) && is_numeric($filters['anio_lectivo_id'])){
                $countSql .= " AND a.anio_lectivo_id = " . intval($filters['anio_lectivo_id']);
            }
            if(isset($filters['busqueda']) && !empty($filters['busqueda'])){
                $busqueda = addslashes($filters['busqueda']);
                $countSql .= " AND (a.titulo LIKE '%" . $busqueda . "%' OR a.descripcion LIKE '%" . $busqueda . "%')";
            }

            $totalResult = $this->query($countSql);
            $total = $totalResult[0]['total'] ?? 0;

            // Calcular información de paginación
            $totalPages = ceil($total / $limit);
            $hasNext = $page < $totalPages;
            $hasPrev = $page > 1;

            return [
                'apuntes' => $apuntes,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_items' => $total,
                    'items_per_page' => $limit,
                    'has_next' => $hasNext,
                    'has_prev' => $hasPrev,
                    'next_page' => $hasNext ? $page + 1 : null,
                    'prev_page' => $hasPrev ? $page - 1 : null
                ]
            ];
        }

        /**
         * Obtiene un apunte específico por ID
         * 
         * @param int $apunte_id ID del apunte
         * @return array|null Datos del apunte o null si no existe
         */
        public function getApunteById($apunte_id){
            if(!is_numeric($apunte_id) || $apunte_id <= 0){
                return null;
            }

            $apunte_id = intval($apunte_id);
            $sql = "SELECT a.*, 
                           m.nombre as materia_nombre,
                           e.nombre as escuela_nombre,
                           al.anio as anio_lectivo,
                           u.nombre as usuario_nombre
                    FROM `apuntes` a
                    LEFT JOIN `materias` m ON a.materia_id = m.id
                    LEFT JOIN `escuelas` e ON a.escuela_id = e.id
                    LEFT JOIN `anios_lectivos` al ON a.anio_lectivo_id = al.id
                    LEFT JOIN `usuarios` u ON a.usuario_cargador_id = u.id
                    WHERE a.id = " . $apunte_id . " AND a.`borrado_en` IS NULL";

            $result = $this->query($sql);
            return $result[0] ?? null;
        }

        /**
         * Obtiene los apuntes más recientes para la página de inicio
         * 
         * @param int $limit Límite de apuntes a obtener (por defecto 6)
         * @return array Lista de apuntes recientes
         */
        public function getApuntesRecientes($limit = 6){
            $limit = max(1, min(20, intval($limit))); // Máximo 20 para la página de inicio

            $sql = "SELECT a.*, 
                           m.nombre as materia_nombre,
                           e.nombre as escuela_nombre,
                           al.anio as anio_lectivo,
                           u.nombre_completo as usuario_nombre
                    FROM `apuntes` a
                    LEFT JOIN `materias` m ON a.materia_id = m.id
                    LEFT JOIN `escuelas` e ON a.escuela_id = e.id
                    LEFT JOIN `anios_lectivos` al ON a.anio_lectivo_id = al.id
                    LEFT JOIN `usuarios` u ON a.usuario_cargador_id = u.id
                    WHERE a.`borrado_en` IS NULL 
                    AND a.`estado` = 'aprobado'
                    ORDER BY a.creado_en DESC
                    LIMIT " . $limit;

            return $this->query($sql);
        }

        /**
         * Obtiene apuntes destacados para la página de inicio
         * Por ahora, obtiene los más antiguos aprobados como "destacados"
         * En el futuro se podría implementar lógica de recomendaciones
         * 
         * @param int $limit Límite de apuntes a obtener (por defecto 8)
         * @return array Lista de apuntes destacados
         */
        public function getApuntesDestacados($limit = 8){
            $limit = max(1, min(20, intval($limit))); // Máximo 20 para la página de inicio

            $sql = "SELECT a.*, 
                           m.nombre as materia_nombre,
                           e.nombre as escuela_nombre,
                           al.anio as anio_lectivo,
                           u.nombre_completo as usuario_nombre
                    FROM `apuntes` a
                    LEFT JOIN `materias` m ON a.materia_id = m.id
                    LEFT JOIN `escuelas` e ON a.escuela_id = e.id
                    LEFT JOIN `anios_lectivos` al ON a.anio_lectivo_id = al.id
                    LEFT JOIN `usuarios` u ON a.usuario_cargador_id = u.id
                    WHERE a.`borrado_en` IS NULL 
                    AND a.`estado` = 'aprobado'
                    ORDER BY a.creado_en ASC
                    LIMIT " . $limit;

            return $this->query($sql);
        }
    }
?>