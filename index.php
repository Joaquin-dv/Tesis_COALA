<?php 
	/***
	 * 
	 * Doxygen
	 * 
	 * index.php trabaja como un ROUTER - FIREWALL
	 * 
	 * */
	date_default_timezone_set("America/Argentina/Buenos_Aires");

	require_once ".env.php"; /*Variables de entorno*/
	require_once "models/DBAbstract.php"; /*Modelo de conexión a la db*/
	require_once 'models/Usuarios.php';
	require_once 'models/Apuntes.php';
	require_once 'models/Escuelas.php';
	require_once 'models/Logger.php';
	require_once "libs/DocumentAI.php"; /*Procesamiento de documentos con IA*/
	require_once "libs/mopla/Mopla.php"; /*Motor de plantillas*/
	require_once "libs/mopla/Extends.php"; /*Motor de componentes*/

	include 'libs/Mailer/src/PHPMailer.php';
	include 'libs/Mailer/src/SMTP.php';
	include 'libs/Mailer/src/Exception.php';

	session_start();
	
	$section = "landing"; /*por defecto section es landing*/

	if(isset($_GET['slug']) && $_GET['slug'] != ""){ /* en caso de que se especifique una sección*/
		$section = $_GET['slug'];
	}

	$secciones_permitidas_logeado = array("inicio","explorar","mochila","clases");
	$secciones_permitidas_deslogeado = array("landing","login","registro","registerConfirm","error404");

	/* Se especifico una sección pero esta no existe */
	if(!file_exists("controllers/".$section."Controller.php")){
		$section = "error404"; /*lo llevamos a la seccion de error*/
	}

	/* Protección de secciones */
	/*Si la sección está en el array de secciones permitidas para usuarios logeados*/
	if(in_array($section, $secciones_permitidas_logeado) == true){
		if(!isset($_SESSION[APP_NAME])){ /*y el usuario no está logeado*/
			header("Location: index.php?slug=login"); /*lo llevamos a login*/
		}
	}elseif(in_array($section, $secciones_permitidas_deslogeado) == true){
		if(isset($_SESSION[APP_NAME])){ /*y el usuario está logeado*/
			header("Location: index.php?slug=inicio"); /*lo llevamos a inicio*/
		}
	}

	/*Se carga el controlador*/
	include "controllers/".$section."Controller.php";

?>