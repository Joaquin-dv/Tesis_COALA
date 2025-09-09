<?php

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("inicio");

	$tpl->printExtends(["apunte", "modalSubirApunte"]);

	$tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
	
?>