<?php

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla('apuntesReportados');

	$tpl->printExtends(["apunte"]);

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'apuntesReportados');

	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
?>