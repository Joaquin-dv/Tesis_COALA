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
    }
?>