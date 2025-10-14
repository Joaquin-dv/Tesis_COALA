<?php

    class Escuelas extends DBAbstract {
        private $id;
        private $nombre;
        private $tipo;
        private $slug;
        private $creado_en;

        
        public function __construct() {
            parent::__construct();

            $this->id = null;
            $this->nombre = null;
            $this->tipo = null;
            $this->slug = null;
            $this->creado_en = null;
        }

        /**
         * Obtiene todas las escuelas disponibles
         * @return array Lista de escuelas con ID y nombre
         */
        public function getEscuelas()
        {
            $result = $this->callSP("CALL sp_obtener_escuelas()");
            return $result['result_sets'][0];
        }

        public function getNameById($escuela_id) {
            $sql = "SELECT nombre FROM `escuelas` WHERE id = ".$escuela_id.";";

            $result = $this->query($sql);

            return $result[0]["nombre"];
        }

        public function getAnioLectivoById($anio_lectivo_id){
            $sql = "SELECT anio FROM `anios_lectivos` WHERE id = ".$anio_lectivo_id.";";

            $result = $this->query($sql);

            return $result[0]["anio"];
        }

        public function getMateriaByID($materia_id) {
            $sql = "SELECT nombre FROM `materias` WHERE id = ".$materia_id.";";

            $result = $this->query($sql);

            return $result[0]["nombre"];
        }

        public function getNivelesByEscuela($id_escuela, $id_anio_lectivo) {
            $result = $this->callSP("CALL sp_obtener_niveles_por_escuela(?, ?)", [$id_escuela, $id_anio_lectivo]);
            return $result['result_sets'][0];
        } 

        public function getDivisionesPorNivel($id_escuela, $id_anio_lectivo, $nivel) {
            $result = $this->callSP("CALL sp_obtener_divisiones_por_nivel(?, ?, ?)", [$id_escuela, $id_anio_lectivo, $nivel]);
            return $result['result_sets'][0];
        }


        public function getCursos($id_escuela, $id_anio_lectivo) {
            
            $sql = "SELECT * FROM `cursos` WHERE escuela_id = ".$id_escuela." AND anio_lectivo_id = ".$id_anio_lectivo." ORDER BY id ASC;";

            $result = $this->query($sql);

            return $result;
        }

        public function getCursoByNivelandDivision($nivel, $division, $id_escuela, $id_anio_lectivo) {
            $sql = "SELECT * FROM `cursos` WHERE nivel = ".$nivel." AND division = '".$division."' AND escuela_id = ".$id_escuela." AND anio_lectivo_id = ".$id_anio_lectivo." ORDER BY id ASC;";

            $result = $this->query($sql);

            return $result;
        }

        public function getMateriasByCurso($id_escuela, $id_anio_lectivo, $id_curso) {
            $result = $this->callSP("CALL sp_obtener_materias_por_curso(?, ?, ?)", [$id_escuela, $id_anio_lectivo, $id_curso]);
            return $result['result_sets'][0];
        }

        public function getMaterias($id_escuela, $id_anio_lectivo) {
            $sql = "SELECT * FROM `materias` WHERE escuela_id = ".$id_escuela." ORDER BY id ASC;";

            $result = $this->query($sql);

            return $result;
        }
    }
?>