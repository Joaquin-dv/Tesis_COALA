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
 * ! -> error
 * P -> page load
 * 
 *Eventos:
 * - Logueo / Deslogueo
 * - Consultar: guarda el texto del evento (por ejemplo: "El usuario entro al apunte X")
 * - Creación: guarda el ID del objeto creado
 * - Modificación: guarda el ID del objeto modificado
 * - Eliminación: guarda el ID del objeto eliminado (soft delete)
 * - Error: guarda el código y mensaje de error
 *
 * Métodos públicos:
 * - logueo(int $usuario, string $evento = 'El usuario se logueó')
 * - deslogueo(int $usuario, string $evento = 'El usuario se deslogueó')
 * - consulta(int $usuario, string $evento, int $idConsultado)
 * - creacion(int $usuario, string $objeto, int $idCreado)
 * - modificacion(int $usuario, string $objeto, int $idModificado)
 * - eliminacion(int $usuario, string $objeto, int $idEliminado)
 * - error(int $usuario, string $codigoError, string $mensajeError)
 * - pageLoad(int $usuario, string $pagina)
 */

class Logger {
    private $archivo;
    private $archivoErrores;

    public function __construct($ruta = null){
        if ($ruta === null){
            $ruta = 'data/logs/app.log';
        }
        $this->archivo = $ruta;
        $this->archivoErrores = 'data/logs/error.log';

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
    public function consulta ($usuario=null,$query,$anio,$materia){

        if($usuario === null){
            if(isset($_SESSION[APP_NAME])){
                $usuario=$_SESSION[APP_NAME]['user']['id'];
            } else {
                $usuario = 0; // Usuario no logueado
            }
        }

        if (!empty($query) && empty($materia) && empty($anio)){
           $evento="el usuario busco {$query}";
        }elseif(!empty($anio) && empty($materia) %% empty($query)){
            $evento="el usuario busco apuntes del año {$anio}";
        }elseif(!empty($materia) && empty($anio) && empty($query)){
            $evento="el usuario busco apuntes de la materia {$materia}";
        }elseif(!empty($query) && !empty($anio) && !empty($materia)){
            $evento="el usuario busco {$query} del {$anio} año  de la materia {$materia}";
        
        $this->write('?',$usuario,$evento);
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

    /**
     * Registra un error en error.log
     * 
     * @param int|string $usuario ID del usuario (o 0 si no aplica)
     * @param string $codigoError Código del error (por ejemplo "404")
     * @param string $mensajeError Mensaje descriptivo del error
     */
    public function error ($usuario=null, $codigoError, $mensajeError){
        if($usuario === null){
            if(isset($_SESSION[APP_NAME])){
                $usuario=$_SESSION[APP_NAME]['user']['id'];
            }
        }
        $this->writeError('!',$usuario,"ERROR {$codigoError}: {$mensajeError}");
    }

    public function pageLoad ($usuario=null, $pagina){
        if($usuario === null){
            if(isset($_SESSION[APP_NAME])){
                $usuario=$_SESSION[APP_NAME]['user']['id'];
            } else {
                $usuario = 0; // Usuario no logueado
            }
        }

        // Si la pagina es detalle de apunte, obtener el ID del apunte
        if (strpos($pagina, 'detalleApunte?apunteId=') !== false) {
            $partes = explode('=', $pagina);
            if (isset($partes[1]) && is_numeric($partes[1])) {
                $idApunte = (int)$partes[1];
                $this->write('P',$usuario,"El usuario accedio al detalle del apunte ID: {$idApunte}");
                return;
            }
        }

        $this->write('P',$usuario,"El usuario accedio a la pagina: {$pagina}");
    }

    private function write ($accion,$usuario,$evento){
        $fechaHora = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $datos = [$accion, $fechaHora, $ip, $navegador, $usuario, $evento];
        $linea = implode('|', $datos) . PHP_EOL;

        if (!file_exists($this->archivo) || filesize($this->archivo) === 0) {
            $encabezado = "accion | fecha y hora | ip | navegador | id_usuario | evento" . PHP_EOL;
            file_put_contents($this->archivo, $encabezado);
        }

        file_put_contents($this->archivo, $linea, FILE_APPEND);
    }

    private function writeError ($accion, $usuario, $evento){
        $fechaHora = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $datos = [$accion, $fechaHora, $ip, $navegador, $usuario, $evento];
        $linea = implode('|', $datos) . PHP_EOL;

        if (!file_exists($this->archivoErrores) || filesize($this->archivoErrores) === 0) {
            $encabezado = "accion | fecha y hora | ip | navegador | id_usuario | evento" . PHP_EOL;
            file_put_contents($this->archivoErrores, $encabezado);
        }

        file_put_contents($this->archivoErrores, $linea, FILE_APPEND);
    }
}
?>