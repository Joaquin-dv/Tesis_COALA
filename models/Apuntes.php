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
    }
?>