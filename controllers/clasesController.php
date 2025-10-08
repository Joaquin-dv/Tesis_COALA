<?php

    $tpl = new Mopla('clases');

    $tpl->printExtends(["modalSubirApunte"]);

    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);
    
    /* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>