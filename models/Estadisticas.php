<?php

class Estadisticas extends DBAbstract
{
    private $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger();
    }

    public function getEstadisticasGenerales() {
        $result = $this->callSP("CALL sp_obtener_estadisticas_generales()");
        return $result['result_sets'][0];
    }

    public function getApuntesPorMateria() {
        $result = $this->callSP("CALL sp_estadisticas_apuntes_por_materia()");
        return $result['result_sets'][0];
    }

    public function getApuntesAprobadosRechazados() {
        $result = $this->callSP("CALL sp_estadisticas_apuntes_aprobados_rechazados()");
        return $result['result_sets'][0];
    }

    public function getUsuariosPorEscuela() {
        $result = $this->callSP("CALL sp_estadisticas_usuarios_por_escuela()");
        return $result['result_sets'][0];
    }

    public function getApuntesMasVistos() {
        $result = $this->callSP("CALL sp_estadisticas_apuntes_mas_vistos()");
        return $result['result_sets'][0];
    }

    public function getNuevosUsuariosUltimoMes() {
        $fechaLimite = date('Y-m-d', strtotime('-1 month'));
        $result = $this->callSP("CALL sp_estadisticas_nuevos_usuarios_ultimo_mes('$fechaLimite')");
        return $result['result_sets'][0];
    }

    public function getUsuariosLogueadosUltimoMes() {
        $archivoLog = __DIR__ . '/../data/logs/app.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $fechaLimite = date('Y-m-d', strtotime('-1 month'));
        $logueos = [];
        
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'ingreso') !== false) {
                    $fechaLog = substr($line[1], 0, 10);
                    if ($fechaLog >= $fechaLimite) {
                        if (!isset($logueos[$fechaLog])) {
                            $logueos[$fechaLog] = 0;
                        }
                        $logueos[$fechaLog]++;
                    }
                }
            }
            fclose($handle);
        }
        
        $resultado = [];
        foreach ($logueos as $fecha => $cantidad) {
            $resultado[] = ['FECHA' => $fecha, 'CANTIDAD_LOGUEOS' => $cantidad];
        }
        
        return $resultado;
    }

    public function getErroresSistemaUltimoMes() {
        $archivoLog = __DIR__. '/../data/logs/error.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $fechaLimite = date('Y-m-d', strtotime('-1 month'));
        $errores = [];
        
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'ERROR') !== false) {
                    $fechaLog = substr($line[1], 0, 10);
                    if ($fechaLog >= $fechaLimite) {
                        preg_match('/ERROR (\d+)/', $line[5], $matches);
                        $codigoError = isset($matches[1]) ? 'ERROR ' . $matches[1] : 'ERROR DESCONOCIDO';
                        
                        if (!isset($errores[$codigoError])) {
                            $errores[$codigoError] = 0;
                        }
                        $errores[$codigoError]++;
                    }
                }
            }
            fclose($handle);
        }
        
        $resultado = [];
        foreach ($errores as $codigo => $cantidad) {
            $resultado[] = ['CODIGO_ERROR' => $codigo, 'CANTIDAD_ERRORES' => $cantidad];
        }
        
        return $resultado;
    }

    public function getBusquedasPorTipo() {
        $archivoLog = __DIR__ . '/../data/logs/app.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $tipos = [];
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'BUSQUEDA') !== false) {
                    preg_match('/BUSQUEDA (\w+):/', $line[5], $matches);
                    if (isset($matches[1])) {
                        $tipo = $matches[1];
                        if (!isset($tipos[$tipo])) {
                            $tipos[$tipo] = 0;
                        }
                        $tipos[$tipo]++;
                    }
                }
            }
            fclose($handle);
        }
        
        arsort($tipos);
        
        $resultado = [];
        foreach ($tipos as $tipo => $cantidad) {
            $resultado[] = ['TIPO_BUSQUEDA' => $tipo, 'CANTIDAD_BUSQUEDAS' => $cantidad];
        }
        return $resultado;
    }

    public function getTerminosMasBuscados() {
        $archivoLog = __DIR__ . '/../data/logs/app.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $terminos = [];
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'busco query:') !== false) {
                    preg_match('/busco query: (.+)/', $line[5], $matches);
                    if (isset($matches[1])) {
                        $termino = trim($matches[1]);
                        if (!isset($terminos[$termino])) {
                            $terminos[$termino] = 0;
                        }
                        $terminos[$termino]++;
                    }
                }
            }
            fclose($handle);
        }
        
        arsort($terminos);
        
        $resultado = [];
        foreach ($terminos as $termino => $cantidad) {
            $resultado[] = ['TERMINO_BUSCADO' => $termino, 'CANTIDAD_BUSQUEDAS' => $cantidad];
        }
        return $resultado;
    }

    public function getAniosMasBuscados() {
        $archivoLog = __DIR__ . '/../data/logs/app.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $anios = [];
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'busco anio:') !== false) {
                    preg_match('/busco anio: (.+)/', $line[5], $matches);
                    if (isset($matches[1])) {
                        $anio = trim($matches[1]);
                        if (!isset($anios[$anio])) {
                            $anios[$anio] = 0;
                        }
                        $anios[$anio]++;
                    }
                }
            }
            fclose($handle);
        }
        
        arsort($anios);
        
        $resultado = [];
        foreach ($anios as $anio => $cantidad) {
            $resultado[] = ['ANIO' => $anio, 'CANTIDAD_BUSQUEDAS' => $cantidad];
        }
        return $resultado;
    }

    public function getMateriasMasBuscadas() {
        $archivoLog = __DIR__ . '/../data/logs/app.log';
        
        if (!file_exists($archivoLog)) {
            return [];
        }
        
        $materias = [];
        
        $handle = fopen($archivoLog, 'r');
        if ($handle) {
            while (($line = fgetcsv($handle, null, "|")) !== false) {
                if (isset($line[5]) && strpos($line[5], 'busco materia') !== false) {
                    $fechaLog = substr($line[1], 0, 10);
                    preg_match('/busco materia: (.+)/', $line[5], $matches);
                    if (isset($matches[1])) {
                        $materiaBuscada = trim($matches[1]);
                        if (!isset($materias[$materiaBuscada])) {
                            $materias[$materiaBuscada] = 0;
                        }
                        $materias[$materiaBuscada]++;
                    }
                }
            }
            fclose($handle);
        }
        
        arsort($materias);
        
        $resultado = [];
        foreach ($materias as $materia => $cantidad) {
            $resultado[] = ['NOMBRE_MATERIA' => $materia, 'CANTIDAD_BUSQUEDAS' => $cantidad];
        }
        
        return $resultado;
    }

}