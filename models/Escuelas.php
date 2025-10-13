<?php



class Escuelas extends DBAbstract {

    private $id;

    private $nombre;

    private $tipo;

    private $slug;

    private $creado_en;



    // Nuevo: logger y user_id cacheado desde la sesión

    private $logger;

    private $user_id;



    public function __construct() {

        parent::__construct();



        $this->id = null;

        $this->nombre = null;

        $this->tipo = null;

        $this->slug = null;

        $this->creado_en = null;



        // Inicializar logger y tomar user_id de la sesión una sola vez

        $this->logger = new Logger();

        $this->user_id = (isset($_SESSION[APP_NAME]["user"]["id"]) && is_numeric($_SESSION[APP_NAME]["user"]["id"]))

            ? (int) $_SESSION[APP_NAME]["user"]["id"]

            : 0;

    }



    public function getNameById($escuela_id) {

        $sql = "SELECT nombre FROM escuelas WHERE id = ".$escuela_id.";";

        $result = $this->query($sql);

        return $result[0]["nombre"];

    }



    public function getAnioLectivoById($anio_lectivo_id){

        $sql = "SELECT anio FROM anios_lectivos WHERE id = ".$anio_lectivo_id.";";

        $result = $this->query($sql);

        return $result[0]["anio"];

    }



    public function getMateriaByID($materia_id) {

        $sql = "SELECT nombre FROM materias WHERE id = ".$materia_id.";";

        $result = $this->query($sql);

        return $result[0]["nombre"];

    }



    public function getCursos($id_escuela, $id_anio_lectivo) {

        $sql = "SELECT * FROM cursos WHERE escuela_id = ".$id_escuela." AND anio_lectivo_id = ".$id_anio_lectivo." ORDER BY id ASC;";

        $result = $this->query($sql);

        return $result;

    }



    public function getCursoByNivelandDivision($nivel, $division, $id_escuela, $id_anio_lectivo) {

        $sql = "SELECT * FROM cursos WHERE nivel = ".$nivel." AND division = '".$division."' AND escuela_id = ".$id_escuela." AND anio_lectivo_id = ".$id_anio_lectivo." ORDER BY id ASC;";

        $result = $this->query($sql);

        return $result;

    }



    public function getMaterias($id_escuela, $id_anio_lectivo) {

        $sql = "SELECT * FROM materias WHERE escuela_id = ".$id_escuela." ORDER BY id ASC;";

        $result = $this->query($sql);

        return $result;

    }

}

?>