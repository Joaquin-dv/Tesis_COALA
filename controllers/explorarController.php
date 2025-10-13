<?php

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

	$tpl->printExtends(["botonesFiltro", "mobile_nav" ]);

	// $tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);
	$tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);
	/* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>
