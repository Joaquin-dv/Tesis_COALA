<?php

    // Incluir el modelo de Apuntes y helpers
    require_once "models/Apuntes.php";
    require_once "lib/helpers/apunteHelpers.php";

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("inicio");

	$tpl->printExtends(["apunte", "modalSubirApunte"]);

    // Instanciar el modelo de Apuntes
    $apuntesModel = new Apuntes();

    try {
        // Obtener apuntes recientes (últimos 6 apuntes aprobados)
        $apuntesRecientes = $apuntesModel->getApuntesRecientes(6);
        $apuntesRecientesHTML = generarHTMLApuntes($apuntesRecientes, 'No hay apuntes recientes disponibles.');

        // Obtener apuntes destacados (los más antiguos aprobados como "destacados")
        $apuntesDestacados = $apuntesModel->getApuntesDestacados(8);
        $apuntesDestacadosHTML = generarHTMLApuntes($apuntesDestacados, 'No hay apuntes destacados disponibles.');

        // Asignar variables a la plantilla
        $tpl->assignVar([
            'APUNTES_RECIENTES' => $apuntesRecientesHTML,
            'APUNTES_DESTACADOS' => $apuntesDestacadosHTML,
            'TOTAL_RECIENTES' => count($apuntesRecientes),
            'TOTAL_DESTACADOS' => count($apuntesDestacados),
            'SIN_APUNTES_RECIENTES' => empty($apuntesRecientes) ? 'true' : 'false',
            'SIN_APUNTES_DESTACADOS' => empty($apuntesDestacados) ? 'true' : 'false'
        ]);

    } catch (Exception $e) {
        // En caso de error, mostrar mensaje de error
        $errorHTML = generarHTMLError('Error al cargar los apuntes: ' . $e->getMessage());
        $tpl->assignVar([
            'ERROR' => 'true',
            'MENSAJE_ERROR' => 'Error al cargar los apuntes: ' . htmlspecialchars($e->getMessage()),
            'APUNTES_RECIENTES' => $errorHTML,
            'APUNTES_DESTACADOS' => $errorHTML,
            'SIN_APUNTES_RECIENTES' => 'true',
            'SIN_APUNTES_DESTACADOS' => 'true'
        ]);
    }

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
	
?>