<?php 
	/***
	 * 
	 * Doxygen
	 * 
	 * index.php trabaja como un ROUTER - FIREWALL
	 * 
	 * */

	require_once ".env.php"; /*Variables de entorno*/
	require_once "models/DBAbstract.php"; /*Modelo de conexión a la db*/
	require_once 'models/Usuarios.php';
	require_once 'models/Apuntes.php';
	require_once 'models/Escuelas.php';
	require_once "lib/mopla/Mopla.php"; /*Motor de plantillas*/
	require_once "lib/mopla/Extends.php"; /*Motor de componentes*/

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