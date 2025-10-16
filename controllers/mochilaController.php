<?php

	/* Log de acceso a la página */
	$logger = new Logger();
	$logger->pageLoad(null, 'mochila');

    $tpl = new Mopla('mochila');

    // Se carga el componente

    $apunteExtend = new Extend("apunte");

    
    // Se carga el modelo de apuntes
	$apunte = new Apuntes();

    // Array para guardar el componente con la informacion cargada
	$lista_favoritos = "";
	$lista_en_revision = "";
	$lista_aprobados = "";
	$lista_rechazados = "";
    
    //obtengo 5 apuntes
	$lista_apuntes = $apunte->getApuntesByAlumno($_SESSION[APP_NAME]['user']['id'], true);
	$lista_apuntes_favoritos = $apunte->getApuntesFavoritosByAlumno($_SESSION[APP_NAME]['user']['id'], true);
	
	// Cargo la informacion en los componentes
	foreach ($lista_apuntes as $row) {
        if($row['ESTADO'] == "aprobado"){
            $componente_estado = '<div class="icono_estado"><i class="fa-solid fa-circle-check"></i></div>';
            $row['COMPONENTE_ESTADO'] = $componente_estado;
            $lista_aprobados .= $apunteExtend->assignVar($row);
        }

        if($row['ESTADO'] == "rechazado"){
            $componente_estado = '<div class="icono_estado"><i class="fa-solid fa-circle-xmark"></i></div>';
            $row['COMPONENTE_ESTADO'] = $componente_estado;
            $lista_rechazados .= $apunteExtend->assignVar($row);
        }
        
        if($row['ESTADO'] == "en_revision"){
            $componente_estado = '<div class="icono_estado"><i class="fa-solid fa-clock"></i></div>';
            $row['COMPONENTE_ESTADO'] = $componente_estado;
            $lista_en_revision .= $apunteExtend->assignVar($row);
        }
	}

    //Carga la informacion de los apuntes favoritos
    foreach ($lista_apuntes_favoritos as $row) {
        $componente_estado = '<div class="icono_estado"><i id="corazon_favorito" class="fa-solid fa-heart"></i></div>';
        $row['COMPONENTE_ESTADO'] = $componente_estado;
        $lista_favoritos .= $apunteExtend->assignVar($row);    
	}

    // Verificar listas vacías y asignar mensajes
    if (empty($lista_favoritos)) {
        $lista_favoritos = '<p class="msg_vacio">No hay apuntes favoritos</p>';
    }
    if (empty($lista_aprobados)) {
        $lista_aprobados = '<p class="msg_vacio">No hay apuntes aprobados</p>';
    }
    if (empty($lista_rechazados)) {
        $lista_rechazados = '<p class="msg_vacio">No hay apuntes rechazados</p>';
    }
    if (empty($lista_en_revision)) {
        $lista_en_revision = '<p class="msg_vacio">No hay apuntes en revisión</p>';
    }

    $tpl->printExtends(["modalSubirApunte","mobile_nav"]);

    // $tpl->assignVar(["TITULO" => "Apuntes de Cálculo I", "MATERIA" => "Cálculo I", "ESCUELA" => "UTN FRBA", "AÑO" => "2020", "PUNTUACION" => "4.5", "IMAGEN" => '']);
	$tpl->assignVar(["APUNTES_FAVORITOS" => $lista_favoritos]);
	$tpl->assignVar(["APUNTES_APROBADOS" => $lista_aprobados]);
	$tpl->assignVar(["APUNTES_RECHAZADOS" => $lista_rechazados]);
	$tpl->assignVar(["APUNTES_EN_REVISION" => $lista_en_revision]);

    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo']]);

    /* Imprime la plantilla en la página */
	$tpl->printToScreen();

?>