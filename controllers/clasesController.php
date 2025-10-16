<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'clases');

    $tpl = new Mopla('clases');

    $tpl->printExtends(["modalSubirApunte"]);

    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);
    
    /* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>