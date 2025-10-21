<?php
/**
 * Servidor de archivos estáticos con control de caché
 */

require_once ".env.php";
require_once "libs/CacheHelper.php";

$file = $_GET['file'] ?? '';
$basePath = __DIR__ . '/views/static/';
$filePath = $basePath . $file;

// Verificar que el archivo existe y está dentro del directorio permitido
if (!$file || !file_exists($filePath) || strpos(realpath($filePath), realpath($basePath)) !== 0) {
    http_response_code(404);
    exit('File not found');
}

// Obtener información del archivo
$fileInfo = pathinfo($filePath);
$extension = strtolower($fileInfo['extension']);

// Definir tipos MIME
$mimeTypes = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf'
];

$mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

// Establecer headers de caché (1 año para archivos estáticos)
CacheHelper::setCacheHeaders(31536000);
header('Content-Type: ' . $mimeType);

// Servir el archivo
readfile($filePath);

exit;
?>