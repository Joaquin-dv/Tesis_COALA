<?php

	$tpl = new Mopla('inicio');

	// Se levanta el componente
	$componente_apunte = new Mopla('components/tarjeta_apunte');

	// Variable donde se guardan los apuntes con los datos cargados
	$lista_apuntes_recientes = "";

	// ====== VISTOS RECIENTEMENTE ======

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 10; $i++) {
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
		$lista_apuntes_recientes .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("VISTOS_RECIENTEMENTE", $lista_apuntes_recientes);

	// ====== PARA TI ======

	$lista_apuntes_para_ti = "";

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
		$lista_apuntes_para_ti .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("PARA_TI", $lista_apuntes_para_ti);

	$tpl->print();

?>