<?php

/**
 * Funciones helper para la generación de extends de apuntes
 * Utiliza el sistema de extends de Mopla para reutilizar la estructura
 */

/**
 * Genera el HTML para una lista de apuntes usando el extend
 * 
 * @param array $apuntes Lista de apuntes
 * @param string $mensajeSinApuntes Mensaje a mostrar cuando no hay apuntes
 * @return string HTML de los apuntes
 */
function generarHTMLApuntes($apuntes, $mensajeSinApuntes = 'No hay apuntes disponibles.') {
    if(empty($apuntes)) {
        return '<p class="sin-apuntes">' . htmlspecialchars($mensajeSinApuntes) . '</p>';
    }
    
    $html = '';
    foreach($apuntes as $apunte) {
        $html .= '<article class="apunte">
            <figure>
                <img src="/views/static/img/inicio/foto_apunte.png" alt="Imagen del apunte">
            </figure>
            <section class="informacion">
                <h2>' . htmlspecialchars($apunte['titulo']) . '</h2>
                <p>' . htmlspecialchars($apunte['materia_nombre'] ?? 'Sin materia') . ' - ' . htmlspecialchars($apunte['escuela_nombre'] ?? 'Sin escuela') . '</p>
                <p>' . htmlspecialchars($apunte['anio_lectivo'] ?? 'Sin año') . ' - 4.5</p>
            </section>
        </article>';
    }
    
    return $html;
}


/**
 * Genera el HTML para un mensaje de error
 * 
 * @param string $mensaje Mensaje de error
 * @return string HTML del mensaje de error
 */
function generarHTMLError($mensaje) {
    return '<p class="error-apuntes">' . htmlspecialchars($mensaje) . '</p>';
}

?>
