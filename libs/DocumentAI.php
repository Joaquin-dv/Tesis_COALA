<?php
/*
 * DocumentAI.php - Integración con Google Document AI para clasificación de apuntes
 */

require_once dirname(__DIR__) . '/libs/vendor/autoload.php';

use Google\ApiCore\ApiException;
use Google\ApiCore\OperationResponse;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\BatchProcessRequest;
use Google\Cloud\DocumentAI\V1\BatchDocumentsInputConfig;
use Google\Cloud\DocumentAI\V1\GcsPrefix;
use Google\Cloud\DocumentAI\V1\BatchProcessMetadata;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Google\Cloud\DocumentAI\V1\DocumentOutputConfig;
use Google\Cloud\DocumentAI\V1\GcsDestination;

class DocumentAI
{
    private $projectId;
    private $location;
    private $processorId;
    private $keyFilePath;
    private $credentials;

    public function __construct()
    {
        // Configuración de Google Cloud Document AI
        $this->projectId = GOOGLE_PROJECT_ID;
        $this->location = 'us'; // Ubicación del procesador
        $this->processorId = '3bd81ff2c1dee4e4'; // ID del procesador de clasificación
        // Crear credenciales desde constantes en lugar de archivo JSON
        $this->credentials = [
            'type' => 'service_account',
            'project_id' => GOOGLE_PROJECT_ID,
            'private_key_id' => GOOGLE_PRIVATE_KEY_ID,
            'private_key' => GOOGLE_PRIVATE_KEY,
            'client_email' => GOOGLE_CLIENT_EMAIL,
            'client_id' => GOOGLE_CLIENT_ID,
            'auth_uri' => GOOGLE_AUTH_URI,
            'token_uri' => GOOGLE_TOKEN_URI,
            'auth_provider_x509_cert_url' => GOOGLE_AUTH_PROVIDER_X509_CERT_URL,
            'client_x509_cert_url' => GOOGLE_CLIENT_X509_CERT_URL,
            'universe_domain' => GOOGLE_UNIVERSE_DOMAIN,
        ];
    }

    // Función para iniciar el procesamiento del documento
    public function startProcessing($filePath, $apunteId = null)
    {
        // Generar un ID único para esta sesión de procesamiento
        $processingId = uniqid('process_', true);

        // Guardar el archivo temporalmente con el ID
        $tempDir = sys_get_temp_dir();
        $savedPath = $tempDir . '/' . $processingId . '.pdf';
        copy($filePath, $savedPath); // Usar copy en lugar de move_uploaded_file ya que el archivo ya está subido

        // Crear archivo de estado
        $statusFile = $tempDir . '/' . $processingId . '.status';
        file_put_contents($statusFile, json_encode(['status' => 'processing', 'start_time' => time(), 'apunte_id' => $apunteId]));

        return $processingId;
    }

    // Función para verificar el estado del procesamiento
    public function checkProcessingStatus($processingId)
    {
        $tempDir = sys_get_temp_dir();
        $statusFile = $tempDir . '/' . $processingId . '.status';
        $pdfFile = $tempDir . '/' . $processingId . '.pdf';

        if (!file_exists($statusFile)) {
            return ['status' => 'error', 'message' => 'Procesamiento no encontrado'];
        }

        $status = json_decode(file_get_contents($statusFile), true);

        // Simular procesamiento que toma tiempo
        $elapsed = time() - $status['start_time'];
        if ($elapsed < 2) { // 2 segundos de "procesamiento"
            return ['status' => 'processing'];
        }

        // Si ya pasó el tiempo, procesar realmente
        if ($status['status'] === 'processing') {
            $result = $this->processDocument($pdfFile);
            $status['status'] = 'completed';
            $status['result'] = $result;
            file_put_contents($statusFile, json_encode($status));

            // Limpiar archivos
            unlink($pdfFile);
        }

        return $status;
    }

    // Función interna para procesar el documento (síncrona)
    private function processDocument($filePath)
    {
        // Create a client.
        $documentProcessorServiceClient = new DocumentProcessorServiceClient([
            'credentials' => $this->credentials,
        ]);

        // Prepare the request message.
        $name = $documentProcessorServiceClient->processorName($this->projectId, $this->location, $this->processorId);
        $content = file_get_contents($filePath);
        $rawDocument = new RawDocument([
            'content' => $content,
            'mime_type' => 'application/pdf',
        ]);
        $request = (new ProcessRequest())
            ->setName($name)
            ->setRawDocument($rawDocument);

        // Call the API and handle any network failures.
        try {
            $response = $documentProcessorServiceClient->processDocument($request);
            $document = $response->getDocument();

            $entities = $document->getEntities();
            $topClassification = '';
            $maxConfidence = 0;
            foreach ($entities as $entity) {
                if ($entity->getConfidence() > $maxConfidence) {
                    $maxConfidence = $entity->getConfidence();
                    $topClassification = $entity->getType();
                }
            }

            // Extraer texto del documento
            $text = $document->getText();

            // Verificar longitud mínima del texto
            $minTextLength = 120;
            $textLength = strlen($text);

            $allowedTypes = ['Apunte', 'Prueba', 'TrabajoPractico', 'Actividad-Tarea', 'Cuadro'];

            if (in_array(strtolower($topClassification), array_map('strtolower', $allowedTypes)) && $textLength >= $minTextLength) {
                return ['status' => 'approved', 'type' => $topClassification, 'text_length' => $textLength];
            } elseif (in_array(strtolower($topClassification), array_map('strtolower', $allowedTypes)) && $textLength < $minTextLength) {
                return ['status' => 'rejected', 'type' => $topClassification, 'reason' => 'texto insuficiente', 'text_length' => $textLength];
            } else {
                return ['status' => 'rejected', 'type' => $topClassification ?: 'desconocido', 'text_length' => $textLength];
            }
        } catch (ApiException $ex) {
            return ['status' => 'error', 'message' => $ex->getMessage()];
        }
    }
}