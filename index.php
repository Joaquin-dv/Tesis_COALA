<?php 
    #Incluimos el controlador
	include 'lib/mopla/Mopla.php';

    #Se incluyen los modelos
	// include_once 'models/Pet.php';
	// include_once 'models/usuario.php';

    #Cargamos el controlador por defecto
	$controller = "landing";

    #Obtenemos la babosa (slug)
	if(isset($_GET["slug"])){
		$controller = $_GET["slug"];
	}  

    #Si el controlador que vino en la babosa no existe, cargamos el controlador error404
	if(!file_exists('controllers/'.$controller.'Controller.php')){
		$controller = "error404";
	}

    #Se carga el contorlador de la babosa
	include 'controllers/'.$controller.'Controller.php';

 ?>
