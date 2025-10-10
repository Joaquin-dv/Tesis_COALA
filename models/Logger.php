<?php

    /**
     * 
     * Formato de columnas:
     * accion | fecha y hora | ip | navegador | usuario(id) | evento
     *
     * acciones: 
     * ? -> consulta
     * * -> modificacion
     * - -> eliminacion
     * + -> creacion
     * > -> logueo
     * < -> deslogueo
     *Eventos:
     * - Logueo / Deslogueo
     * - Consultar: guarda el texto del evento (por ejemplo: "El usuario entro al apunte X")
     * - Creación: guarda el ID del objeto creado
     * - Modificación: guarda el ID del objeto modificado
     * - Eliminación: guarda el ID del objeto eliminado (soft delete)
     *
     * Métodos públicos:
     * - logueo(int $usuario, string $evento = 'El usuario se logueó')
     * - deslogueo(int $usuario, string $evento = 'El usuario se deslogueó')
     * - consulta(int $usuario, string $evento, int $idConsultado)
     * - creacion(int $usuario, string $objeto, int $idCreado)
     * - modificacion(int $usuario, string $objeto, int $idModificado)
     * - eliminacion(int $usuario, string $objeto, int $idEliminado)
     *
     * **/

class Logger {
    private $archivo;

    public function __construct($ruta = null){
        if ($ruta === null){
            $ruta = 'data/logs/app.log';
        }
        $this->archivo = $ruta;

        $directorio = dirname($this->archivo);
        if(!is_dir($directorio)){
            mkdir($directorio,0775,true);
        }
    }
    
    public function logueo ($usuario,$evento='El usuario ingreso a la plataforma'){
        $this->write('>',$usuario,$evento);
    }
    public function deslogueo ($usuario,$evento='El usuario salio de la plataforma'){
        $this->write('<',$usuario,$evento);
    }
    public function consulta ($usuario,$evento='El usuario consulto el elemento ', $idConsultado){
        $this->write('?',$usuario,$evento,$idConsultado);
    }
    public function creacion ($usuario,$objeto,$idCreado){
        $this->write('+',$usuario,"El usuario creo el {$objeto} de id:{$idCreado}");
    }
    public function modificacion ($usuario,$objeto, $idModificado){
        $this->write('*',$usuario,"El usuario modifico el {$objeto} de id:{$idModificado}");
    }
    public function eliminacion ($usuario,$objeto, $idEliminado){
        $this->write('-',$usuario,"El usuario modifico el {$objeto} de id:{$idEliminado}");
    }

    private function write ($accion,$usuario,$evento){
        $fechaHora = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $datos=[$accion,$fechaHora,$ip,$navegador,$usuario,$evento];
        $linea=implode('|',$datos).PHP_EOL;
        
        /**No existe y esta vacio**/
        if(!file_exists($this->archivo) && filesize($this->archivo) === 0){
            $encabezado= "accion | fecha y hora | ip | navegador | id_usuario | evento". PHP_EOL;
            file_put_contents($this->archivo,$encabezado);
        }

        /**Existe y esta vacio**/
        if(file_exists($this->archivo) && filesize($this->archivo) === 0){
            $encabezado= "accion | fecha y hora | ip | navegador | id_usuario | evento". PHP_EOL;
            file_put_contents($this->archivo,$encabezado);
        }
        file_put_contents($this->archivo,$linea,FILE_APPEND);
    }

}

?>