<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'reestablecer');

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("resetPassword");

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
	
?>