<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'error404');

	$tpl = new Mopla("error404");

	$tpl->printToScreen();

?>