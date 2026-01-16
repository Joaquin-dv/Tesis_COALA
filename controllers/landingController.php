<?php

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("landing");

	$tpl->printExtends(["footer"]);

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'landing');

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
?>