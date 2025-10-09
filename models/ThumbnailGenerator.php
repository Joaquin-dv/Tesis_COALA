<?php
// models/ThumbnailGenerator.php

class ThumbnailGenerator
{
    private $outputDir;

    public function __construct($outputDir = __DIR__ . '/../public/data/thumbnails/')
    {
        $this->outputDir = rtrim($outputDir, '/') . '/';
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    /**
     * Genera una miniatura JPG de la primera página de un PDF
     * @param string $pdfPath Ruta absoluta al archivo PDF
     * @param string $thumbnailName Nombre final del archivo JPG
     * @return string|false Ruta de la miniatura generada o false si falla
     */
    public function generateFromPDF($pdfPath, $thumbnailName)
    {
        if (!file_exists($pdfPath)) {
            return false;
        }

        try {
            $imagick = new Imagick();
            $imagick->setResolution(150, 150); // Buena calidad sin sobrecargar
            $imagick->readImage($pdfPath . '[0]'); // Solo la primera página
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompressionQuality(85);

            $thumbnailPath = $this->outputDir . $thumbnailName . '.jpg';
            $imagick->writeImage($thumbnailPath);

            $imagick->clear();
            $imagick->destroy();

            return $thumbnailPath;
        } catch (Exception $e) {
            error_log('Error generando miniatura: ' . $e->getMessage());
            return false;
        }
    }
}
