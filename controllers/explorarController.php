<?php 

	$tpl = new Mopla('explorar');

	// Se levanta el componente
	$componente_apunte = new Mopla('components/tarjeta_apunte');

	// Variable donde se guardan los apuntes con los datos cargados
	$lista_apuntes_explorar = "";

	// ====== EXPLORAR ======

	// Carga de info en componentes (debe ser dinámica, osea un foreach con la info que trajo el modelo)
	for ($i = 0; $i < 5; $i++) {
		// Variables de prueba para los apuntes
		$vars = [
			"IMAGEN" => "",
			"TITULO" => "Funciones cuadráticas",
			"MATERIA" => "Matematica",
			"ESCUELA" => "Fatima",
			"AÑO" => "02/08/2025",
			"PUNTUACION" => "4.3"
		];

		// Se carga la info en el buffer componente
		$componente_apunte->setVars($vars);

		// Se guarda el buffer (componente cargado)
		$lista_apuntes_explorar .= $componente_apunte->getBuffer();

		// Se restaura el componente vacio para ser llenado nuevamente
		$componente_apunte->restore();
	}
	$tpl->setVar("APUNTES_EXPLORAR", $lista_apuntes_explorar);

	$tpl -> print();

 ?>


