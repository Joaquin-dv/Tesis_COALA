<?php 
	/***
	 * 
	 * Doxygen
	 * 
	 * index.php trabaja como un ROUTER - FIREWALL
	 * 
	 * */

	require ".env.php"; /*Variables de entorno*/
	require "models/DBAbstract.php"; /*Modelo de conexión a la db*/
	require_once 'models/Usuarios.php';
	require "lib/mopla/Mopla.php"; /*Motor de plantillas*/

	session_start();
	
	$section = "landing"; /*por defecto section es landing*/

	if(isset($_GET['slug'])){ /* en caso de que se especifique una sección*/
		$section = $_GET['slug'];
	}

	/* Se especifico una sección pero esta no existe */
	if(!file_exists("controllers/".$section."Controller.php")){
		$section = "error404"; /*lo llevamos a la seccion de error*/
	}

	/*Se carga el controlador*/
	include "controllers/".$section."Controller.php";

?>