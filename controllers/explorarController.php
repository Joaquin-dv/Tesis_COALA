<?php

    // Incluir el modelo de Apuntes y helpers
    require_once "models/Apuntes.php";
    require_once "lib/helpers/apunteHelpers.php";

    // Instanciar el motor de plantillas
    $tpl = new Mopla('explorar');

    // Se carga el componente
	$apunteExtend = new Extend("apunte");

	// Se carga el modelo de apuntes
	$apunte = new Apuntes();

	// ========================= CARGA DE COMPONENTE VISTO RECIENTEMENTE =========================

	
	// Array para guardar el componente con la informacion cargada
	$lista_explorar = "";

	//obtengo 5 apuntes
	$lista_apuntes = $apunte->getApuntes(30, true);
	
	// Cargo la informacion en el componente
	foreach ($lista_apuntes as $row) {	
		$lista_explorar .= $apunteExtend->assignVar($row);
	}
	
	// Muestro los componentes con la info
	$tpl->assignVar(["EXPLORAR" => $lista_explorar]);

	// $tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);

    // Obtener parámetros de la URL para paginación y filtros
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
    
    // Preparar filtros
    $filters = [];
    if(isset($_GET['materia_id']) && is_numeric($_GET['materia_id'])){
        $filters['materia_id'] = intval($_GET['materia_id']);
    }
    if(isset($_GET['escuela_id']) && is_numeric($_GET['escuela_id'])){
        $filters['escuela_id'] = intval($_GET['escuela_id']);
    }
    if(isset($_GET['anio_lectivo_id']) && is_numeric($_GET['anio_lectivo_id'])){
        $filters['anio_lectivo_id'] = intval($_GET['anio_lectivo_id']);
    }
    if(isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))){
        $filters['busqueda'] = trim($_GET['busqueda']);
    }

    try {
        // Obtener la lista de apuntes con paginación
        $result = $apuntesModel->getApuntes($page, $limit, $filters);
        
        $apuntes = $result['apuntes'];
        $pagination = $result['pagination'];

        // Generar HTML para los apuntes
        $apuntesHTML = generarHTMLApuntes($apuntes, 'No se encontraron apuntes con los criterios de búsqueda.');

        // Asignar variables a la plantilla
        $tpl->assignVar([
            'APUNTES' => $apuntesHTML,
            'TOTAL_APUNTES' => $pagination['total_items'],
            'PAGINA_ACTUAL' => $pagination['current_page'],
            'TOTAL_PAGINAS' => $pagination['total_pages'],
            'TIENE_SIGUIENTE' => $pagination['has_next'] ? 'true' : 'false',
            'TIENE_ANTERIOR' => $pagination['has_prev'] ? 'true' : 'false',
            'PAGINA_SIGUIENTE' => $pagination['next_page'] ?? '',
            'PAGINA_ANTERIOR' => $pagination['prev_page'] ?? '',
            'FILTRO_MATERIA' => $filters['materia_id'] ?? '',
            'FILTRO_ESCUELA' => $filters['escuela_id'] ?? '',
            'FILTRO_ANIO' => $filters['anio_lectivo_id'] ?? '',
            'FILTRO_BUSQUEDA' => htmlspecialchars($filters['busqueda'] ?? ''),
            'LIMIT' => $limit,
            'SIN_APUNTES' => empty($apuntes) ? 'true' : 'false'
        ]);

    } catch (Exception $e) {
        // En caso de error, mostrar mensaje de error
        $errorHTML = generarHTMLError('Error al cargar los apuntes: ' . $e->getMessage());
        $tpl->assignVar([
            'ERROR' => 'true',
            'MENSAJE_ERROR' => 'Error al cargar los apuntes: ' . htmlspecialchars($e->getMessage()),
            'APUNTES' => $errorHTML,
            'SIN_APUNTES' => 'true'
        ]);
    }

    // Imprimir la plantilla en la página
    $tpl->printToScreen();

?>