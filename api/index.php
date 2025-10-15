<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once "../models/Logger.php";

// --- Manejo simple de errores (no cambia la lógica existente) ---
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_error_handler(function ($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'errno' => 500,
        'error' => "PHP error: $message",
        'file'  => $file,
        'line'  => $line,
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'errno' => 500,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
});
// ----------------------------------------------------------------

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (!isset($_GET['model'])) {
        echo json_encode("No se especifico un modelo.");
        exit();
    }

    if (!isset($_GET['method'])) {
        echo json_encode("No se especifico un método.");
        exit();
    }

    require_once "../.env.php";
    require_once "../models/DBAbstract.php";

    if (!file_exists("../models/" . $_GET['model'] . ".php")) {
        echo json_encode("El modelo especificado no existe.");
        exit();
    }
    require_once("../models/" . $_GET['model'] . ".php");

    $modelo = new $_GET['model']();
    $metodo = $_GET['method'];

    $params = $_GET;
    unset($params['model'], $params['method']);

    if (!method_exists($modelo, $metodo)){
        echo json_encode("El método especificado no existe en el modelo.");
        exit();
    }

    $respuesta = call_user_func_array([$modelo, $metodo], $params ? array_values($params) : []);
    echo json_encode($respuesta);

} elseif ($method == 'POST') {
    $input = file_get_contents('php://input');
    $bodyParams = [];
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        $bodyParams = json_decode($input, true) ?? [];
    } else {
        $bodyParams = $_POST;
    }

    if (!isset($bodyParams['model'])) {
        echo json_encode("No se especifico un modelo.");
        exit();
    }

    if (!isset($bodyParams['method'])) {
        echo json_encode("No se especifico un método.");
        exit();
    }

    require_once "../.env.php";
    require_once "../models/DBAbstract.php";
    require_once "../models/ThumbnailGenerator.php";
    require_once "../libs/DocumentAI.php";

    if (!file_exists("../models/" . $bodyParams['model'] . ".php")) {
        echo json_encode("El modelo especificado no existe.");
        exit();
    }
    require_once("../models/" . $bodyParams['model'] . ".php");

    $modelo = new $bodyParams['model']();
    $metodo = $bodyParams['method'];

    unset($bodyParams['model'], $bodyParams['method']);

    if (!method_exists($modelo, $metodo)){
        echo json_encode("El método especificado no existe en el modelo.");
        exit();
    }

    // Si hay archivos, agregarlos como parámetro
    if (!empty($_FILES['input_file'])) {
        // Si es un array de archivos (múltiples), pasar el array completo
        if (is_array($_FILES['input_file']['name'])) {
            $archivos = [];
            $fileCount = count($_FILES['input_file']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $archivos[] = [
                    'name' => $_FILES['input_file']['name'][$i],
                    'type' => $_FILES['input_file']['type'][$i],
                    'tmp_name' => $_FILES['input_file']['tmp_name'][$i],
                    'error' => $_FILES['input_file']['error'][$i],
                    'size' => $_FILES['input_file']['size'][$i]
                ];
            }
            $params = [
                $bodyParams['titulo'] ?? '',
                $bodyParams['materia'] ?? '',
                $archivos,
                $bodyParams['descripcion'] ?? '',
                $bodyParams['curso'] ?? null,
                $bodyParams['division'] ?? null,
                $bodyParams['visibilidad'] ?? 'publico'
            ];
        } else {
            // Archivo único (compatibilidad hacia atrás)
            $params = [
                $bodyParams['titulo'] ?? '',
                $bodyParams['materia'] ?? '',
                $_FILES['input_file'],
                $bodyParams['descripcion'] ?? '',
                $bodyParams['curso'] ?? null,
                $bodyParams['division'] ?? null,
                $bodyParams['visibilidad'] ?? 'publico'
            ];
        }
        $respuesta = call_user_func_array([$modelo, $metodo], $params);
    } else {
        $respuesta = call_user_func_array([$modelo, $metodo], $bodyParams ? array_values($bodyParams) : []);
    }
    echo json_encode($respuesta);

} else {
    echo json_encode("Método HTTP no soportado.");
    exit();
}
?>
