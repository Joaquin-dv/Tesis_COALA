<?php

    $tpl = new Mopla('mochila');

    $tpl->printExtends(["apunte_mochila", "modalSubirApunte"]);

    // $tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);

    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);

    /* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>