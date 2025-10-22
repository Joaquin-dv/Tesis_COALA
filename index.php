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
	require_once 'models/ThumbnailGenerator.php';
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

	$secciones_permitidas_logeado = array("inicio","explorar","mochila","clases","detalleApunte","logout");
	$secciones_permitidas_deslogeado = array("inicio","explorar","landing","login","registro","registerConfirm","error404","logout");
	$secciones_permitidas_demo = array("inicio","explorar","detalleApunte","registro");
	$secciones_permitidas_admin = array("dashboard");


	/* Se especifico una sección pero esta no existe */
	if(!file_exists("controllers/".$section."Controller.php")){
		$section = "error404"; /*lo llevamos a la seccion de error*/
	}

	/* Protección de secciones */
	/*Si la sección está en el array de secciones permitidas para usuarios logeados*/
	if(in_array($section, $secciones_permitidas_logeado) == true && in_array($section, $secciones_permitidas_deslogeado) == false){
		if(!isset($_SESSION[APP_NAME])){ /*y el usuario no está logeado*/
			header("Location: index.php?slug=login&msg=requiere_login"); /*lo llevamos a login con mensaje*/
			exit();
		}
	}
	
	// if(in_array($section, $secciones_permitidas_deslogeado) == true){
	// 	if(isset($_SESSION[APP_NAME]) && $_SESSION[APP_NAME]['user']['rol'] != 'demo'){ /*y el usuario está logeado y no es demo*/
	// 		header("Location: index.php?slug=inicio"); /*lo llevamos a inicio*/
	// 	}
	// }

	/* Protección para usuario demo */
	if(in_array($section, $secciones_permitidas_logeado) == true && !in_array($section, $secciones_permitidas_demo)){
		if(isset($_SESSION[APP_NAME]) && $_SESSION[APP_NAME]['user']['rol'] == 'demo'){
			header("Location: index.php?slug=inicio"); /*lo llevamos a inicio si intenta acceder a secciones no permitidas*/
		}
	}

	/* Protección para usuario admin */
	if(in_array($section, $secciones_permitidas_admin) == true){
		if(!isset($_SESSION[APP_NAME]) || $_SESSION[APP_NAME]['user']['rol'] != 'admin'){
			header("Location: index.php?slug=login&msg=requiere_login"); /*lo llevamos a login con mensaje*/
			exit();
		}
	}

	/*Se carga el controlador*/
	include "controllers/".$section."Controller.php";

?>