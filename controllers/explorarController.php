<?php

    $tpl = new Mopla('explorar');

    // Se carga el componente
	$apunteExtend = new Extend("apunte");

	// Se carga el modelo de apuntes
	$apunte = new Apuntes();

	// ========================= CARGA DE COMPONENTE VISTO RECIENTEMENTE =========================


	// Array para guardar el componente con la informacion cargada
	$lista_explorar = "";

	// Obtener parámetros de búsqueda y filtros
	$query = isset($_GET['q']) ? trim($_GET['q']) : "";
	$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;
	$materia = isset($_GET['materia']) ? trim($_GET['materia']) : null;

	// Si hay búsqueda o filtros, usar searchApuntes, sino getApuntes normal
	if (!empty($query) || $anio !== null || $materia !== null) {
		$lista_apuntes = $apunte->searchApuntes($query, $anio, null, $materia, 30, true);
	} else {
		$lista_apuntes = $apunte->getApuntes(30, true);
	}

	// Obtener años lectivos para el filtro
	$anios_lectivos = $apunte->getAniosLectivos();

	// Formatear años para la vista
	$anios_html = "";
	foreach ($anios_lectivos as $anio_lectivo) {
		$anios_html .= '<a href="#" data-anio="' . $anio_lectivo['nivel'] . '">' . $anio_lectivo['nivel'] . 'º Año</a>';
	}

	// Cargo la informacion en el componente
	foreach ($lista_apuntes as $row) {
		$lista_explorar .= $apunteExtend->assignVar($row);
	}

	// Muestro los componentes con la info
	$tpl->assignVar(["EXPLORAR" => $lista_explorar]);

	$tpl->printExtends(["botonesFiltro" ]);

	// $tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);
	$tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo'], "ANIOS_LECTIVOS" => $anios_html]);
	/* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>
