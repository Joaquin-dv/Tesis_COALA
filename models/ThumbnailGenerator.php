<?php

// models/ThumbnailGenerator.php

class ThumbnailGenerator
{
    private $outputDir;

    public function __construct($outputDir = '../data/thumbnails_lost/')
    {
        // Asignar correctamente la propiedad y normalizar con barra final
        $this->outputDir = rtrim($outputDir, '/').'/';

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    /**
     * Retorna la ruta del directorio de salida.
     * @return string
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * Genera una miniatura JPG de la primera página de un PDF usando Imagick
     * @param string $pdfPath Ruta absoluta (o real) al archivo PDF
     * @param string $thumbnailName Nombre final del archivo JPG (sin extensión)
     * @return string|false Ruta de la miniatura generada o false si falla
     */
    public function generateFromPDF($pdfPath, $thumbnailName)
    {
        if (!file_exists($pdfPath) || !class_exists('Imagick')) {
            return false;
        }

        return $this->generateFromPDFImagick($pdfPath, $thumbnailName);
    }

    /**
     * Genera thumbnail usando Imagick (método original)
     */
    private function generateFromPDFImagick($pdfPath, $thumbnailName)
    {
        try {
            $imagick = new Imagick();
            $imagick->setResolution(150, 150);
            $imagick->readImage($pdfPath . '[0]');
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompressionQuality(85);

            $thumbnailPath = $this->outputDir . $thumbnailName . '.jpg';
            $imagick->writeImage($thumbnailPath);

            $imagick->clear();
            $imagick->destroy();

            return $thumbnailPath;
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Genera un PDF a partir de múltiples imágenes usando Imagick
     * @param array $imagePaths Array de rutas absolutas a las imágenes
     * @param string $pdfPath Ruta donde guardar el PDF generado
     * @return bool True si se generó correctamente, false si falla
     */
    public function generatePDFFromImages($imagePaths, $pdfPath)
    {
        if (empty($imagePaths) || !class_exists('Imagick')) {
            return false;
        }

        return $this->generatePDFFromImagesImagick($imagePaths, $pdfPath);
    }

    /**
     * Genera PDF usando Imagick (método original)
     */
    private function generatePDFFromImagesImagick($imagePaths, $pdfPath)
    {
        try {
            $imagick = new Imagick();

            foreach ($imagePaths as $imagePath) {
                if (!file_exists($imagePath)) {
                    continue;
                }

                $image = new Imagick($imagePath);
                $image->setImageFormat('pdf');
                $image->setImageCompressionQuality(90);
                $imagick->addImage($image);
            }

            if ($imagick->getNumberImages() === 0) {
                $imagick->clear();
                $imagick->destroy();
                return false;
            }

            $imagick->setImageFormat('pdf');
            $imagick->writeImages($pdfPath, true);

            $imagick->clear();
            $imagick->destroy();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }


}
