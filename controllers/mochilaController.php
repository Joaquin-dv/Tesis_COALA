<?php 

	$tpl = new Mopla('mochila');

	// Se levanta el componente
	$componente_apunte = new Mopla('components/mochila/apunte_mochila');

	// Variable donde se guardan los apuntes con los datos cargados
	$lista_apuntes_favoritos = "";

	// ====== FAVORITOS ======

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 5; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "https://placehold.co/80",
			"TITULO" => "Resumen de la Revolucion Francesa",
			"MATERIA" => "Historia",
			"ESCUELA" => "Fatima",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.3"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_apuntes_favoritos .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("APUNTES_FAVORITOS", $lista_apuntes_favoritos);

	// ====== APROBADOS ======

	// Variable donde se guardan los apuntes con los datos cargados
	$lista_apuntes_aprobados = "";

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 3; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "https://placehold.co/80",
			"TITULO" => "Resumen de la Revolucion Francesa",
			"MATERIA" => "Historia",
			"ESCUELA" => "Fatima",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.3"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_apuntes_aprobados .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("APUNTES_APROBADOS", $lista_apuntes_aprobados);

	// ====== PENDIENTES ======
	$lista_apuntes_pendientes = "";

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 2; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "https://placehold.co/80",
			"TITULO" => "Resumen de la Revolucion Francesa",
			"MATERIA" => "Historia",
			"ESCUELA" => "Fatima",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.3"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_apuntes_pendientes .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("APUNTES_PENDIENTES", $lista_apuntes_pendientes);

	// ====== RECHAZADOS ======
	$lista_apuntes_rechazados = "";

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 4; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "https://placehold.co/80",
			"TITULO" => "Resumen de la Revolucion Francesa",
			"MATERIA" => "Historia",
			"ESCUELA" => "Fatima",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.3"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_apuntes_rechazados .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("APUNTES_RECHAZADOS", $lista_apuntes_rechazados);

	$tpl -> print();

 ?>

