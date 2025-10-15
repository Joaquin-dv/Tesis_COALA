<?php

	/* Se instancia a la clase del motor de plantillas */
	$tpl = new Mopla("landing");

	$tpl->printExtends(["footer"]);
	/* Imprime la plantilla en la página */
	$tpl->printToScreen();
?>