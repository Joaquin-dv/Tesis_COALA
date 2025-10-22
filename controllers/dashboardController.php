<?php
	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'dashboard');

    $tpl = new Mopla('dashboard');
	
	/* Asigna variables a la plantilla */
    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);
    
    /* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>