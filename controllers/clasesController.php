<?php 

	$tpl = new Mopla('clases');

	// Se levanta el componente (TEMPORALMENTE ES EL APUNTE)
	$componente_apunte = new Mopla('components/tarjeta_apunte');
	$componente_modal = new Mopla('components/modalSubirApunte');

	// ====== CLASES ======

	$lista_clases = "";

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 6; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "",
			"TITULO" => "Titulo",
			"MATERIA" => "Historia",
			"ESCUELA" => "Roberto Arlt",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.5"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_clases .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("CLASES", $lista_clases);

	$tpl->setVar("MODAL", $componente_modal->getBuffer());

	$tpl -> print();

?>

