<?php

/**
 * CacheHelper - Gestión de versiones y caché
 */
class CacheHelper
{
    /**
     * Genera URL con versión basada en fecha de modificación del archivo
     */
    public static function asset($path)
    {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/COALA/Tesis_COALA/' . ltrim($path, '/');
        
        if (file_exists($fullPath)) {
            $version = filemtime($fullPath);
            return $path . '?v=' . $version;
        }
        
        return $path . '?v=' . APP_VERSION;
    }

    /**
     * Establece headers de control de caché
     */
    public static function setCacheHeaders($maxAge = 3600)
    {
        header('Cache-Control: public, max-age=' . $maxAge);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    }

    /**
     * Establece headers para evitar caché
     */
    public static function setNoCacheHeaders()
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}