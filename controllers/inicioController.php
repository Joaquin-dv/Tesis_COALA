<?php

	// Se carga la plantilla
	$tpl = new Mopla("inicio");

	// Se carga el componente
	$apunteExtend = new Extend("apunte");

	$modalSubirApunteExtend = new Extend("modalSubirApunte");

	// Se carga el modelo de apuntes
	$apunte = new Apuntes();

	// ========================= CARGA DE COMPONENTE VISTO RECIENTEMENTE =========================

	// Array para guardar el componente con la informacion cargada
	$lista_vistos_recientemente = "";

	//obtengo 5 apuntes
	$lista_apuntes = $apunte->getApuntes(5, true);
	
	// Cargo la informacion en el componente
	foreach ($lista_apuntes as $row) {	
		$lista_vistos_recientemente .= $apunteExtend->assignVar($row);
	}
	
	// Muestro los componentes con la info
	$tpl->assignVar(["VISTOS_RECIENTEMENTE" => $lista_vistos_recientemente]);

	// ============================== CARGA DE COMPONENTE PARA TI ==============================

	// Array para guardar el componente con la informacion cargada
	$lista_para_ti = "";

	//obtengo 5 apuntes
	$lista_componente_para_ti = $apunte->getApuntes(15, true);
	
	// Cargo la informacion en el componente
	foreach ($lista_componente_para_ti as $row) {
		$lista_para_ti .= $apunteExtend->assignVar($row);
	}
	
	// Muestro los componentes con la info
	$tpl->assignVar(["PARA_TI" => $lista_para_ti]);

	// =========================================================================================

	$modalCargado = $modalSubirApunteExtend->assignVar(["MSG_ERROR" => ""]);

	$tpl->assignVar(["MODAL_SUBIR_APUNTE" => $modalCargado]);

	// $tpl->printExtends(["modalSubirApunte"]);
	
	if(isset($_POST["titulo"])){
		$result = $apunte->create($_POST);

		var_dump($result);
	}

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
	
?>