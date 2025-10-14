<?php
    // ============================ CONTROLADOR DETALLE APUNTE ============================

    // Cargamos el motor de plantillas Mopla
    $tpl = new Mopla("detalleApunte");

    // Se carga el componente
    $comentarioExtend = new Extend("comentarioApunte");

    // Se carga el modelo de apuntes
    $apunte = new Apuntes();

    // Verificar si el usuario no está logueado
    if (!isset($_SESSION[APP_NAME])) {
        header("Location: ?slug=login");
        exit();
    }

    // Cargamos la informacion del apunte
    $info_apunte = $apunte->getApunteById($_GET['apunteId'], true)[0];

    // Verificar si el apunte está en favoritos del usuario actual
    $es_favorito = $apunte->esFavorito($_GET['apunteId']);
    $es_favorito = $es_favorito ? 'favorito-activo' : '';

    // Array para guardar el componente con la informacion cargada
    $lista_comentarios = "";

    // Se carga el modelo de comentarios
    $lista_comentarios_apunte = $apunte->getComentariosByApunte($_GET['apunteId']);

    // Obtener la ruta del archivo del apunte
    $ruta_archivo_result = $apunte->getRutaApunteById($_GET['apunteId']);

    if(is_array($ruta_archivo_result) && isset($ruta_archivo_result['errno'])){
        $ruta_archivo = "";
        $error_archivo = "No se encontro el archivo";
    } else {
        $ruta_archivo = $ruta_archivo_result;
        $error_archivo = "";
    }

    // Manejar envío de comentario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
        $resultado = $apunte->createComentario($_GET['apunteId'], $_POST['comentario']);
        if ($resultado['errno'] === 201) {
            // Recargar comentarios después de crear uno nuevo
            $lista_comentarios_apunte = $apunte->getComentariosByApunte($_GET['apunteId']);
        }
    }
    // Incrementar las visitas del apunte (descomentariar para probar)
    // $apunte->incrementarVisitas($_GET['apunteId']);

    // Cargo la informacion en los componentes
    foreach ($lista_comentarios_apunte as $row) {
        $lista_comentarios .= $comentarioExtend->assignVar($row);
    }

    if (empty($lista_comentarios)) {
        $lista_comentarios = '<p class="msg_vacio">No hay comentarios del apunte</p>';
    }

    // Verificamos que el apunte exista    DESCOMENTAR ESTO PARA PROBAR
    // if (!$info_apunte) {
    //     // Redirigir a una página de error o mostrar un mensaje
    //     header("Location: ?slug=errorApunteNoEncontrado");
    //     exit();
    // }

    // Cargamos los componentes necesarios
    $tpl->assignVar(["TITULO" => $info_apunte['TITULO'], "MATERIA" => $info_apunte['MATERIA'], "ESCUELA" => $info_apunte['ESCUELA'], "AÑO" => $info_apunte['AÑO'], "PROMEDIO_CALIFICACIONES" => $info_apunte['PROMEDIO_CALIFICACIONES'], "CANTIDAD_PUNTUACIONES" => $info_apunte['CANTIDAD_PUNTUACIONES'], "NOMBRE_AUTOR" => $info_apunte['NOMBRE_AUTOR'], "FECHA_CREACION" => $info_apunte['FECHA_CREACION'], "RUTA_ARCHIVO" => $ruta_archivo, "ERROR_ARCHIVO" => $error_archivo, "ES_FAVORITO" => $es_favorito, "MOSTRAR_TOAST_COMENTARIO" => isset($mostrar_toast_comentario) ? 'true' : 'false']);
    $tpl->assignVar(["COMENTARIOS_APUNTE" => $lista_comentarios]);

    // Cargamos la informacion del usuario logueado
    $tpl->assignVar(["NOMBRE_USUARIO" => $_SESSION[APP_NAME]['user']['nombre_completo'], "USER_ROLE" => $_SESSION[APP_NAME]['user']['rol']]);
    
	$rol = isset($_SESSION[APP_NAME]['user']['rol']) ? $_SESSION[APP_NAME]['user']['rol'] : "Invitado";
    $tpl->assignVar(["USER_ROLE" => $rol]);

    // Mostramos la vista
    $tpl->printToScreen();

?>