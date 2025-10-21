<?php

/**
 * Funciones helper para plantillas
 */

/**
 * Genera URL de asset con versión automática
 */
function asset($path) {
    return CacheHelper::asset($path);
}

/**
 * Genera URL de CSS con versión
 */
function css($path) {
    if (strpos($path, 'views/static/') !== 0) {
        $path = 'views/static/css/' . ltrim($path, '/');
    }
    return CacheHelper::asset($path);
}

/**
 * Genera URL de JS con versión
 */
function js($path) {
    if (strpos($path, 'views/static/') !== 0) {
        $path = 'views/static/js/' . ltrim($path, '/');
    }
    return CacheHelper::asset($path);
}

/**
 * Genera URL de imagen con versión
 */
function img($path) {
    if (strpos($path, 'views/static/') !== 0) {
        $path = 'views/static/img/' . ltrim($path, '/');
    }
    return CacheHelper::asset($path);
}