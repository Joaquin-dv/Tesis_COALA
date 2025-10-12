<?php 
	
	/**
	 * 
	 * Constantes y variables de entorno
	 * 
	 * */

	/*Nombre clave de la app*/
	define("APP_NAME", "COALA");
	define("APP_SLOGAN", "Conectando comunidades educativas");
	define("APP_DESCRIPTION", "Esta aplicacion es para que alumnos y profesores puedan interactuar entre si y compartir recursos educativos de manera facil y rapida.");
	define("APP_AUTHOR", "COALA Team");

	define("APP_URL", "https://coala.escuelarobertoarlt.com/");

	/*====== Colores de la web*/

	define("FONDO_PAGINA", "#f4f9f4"); // Fondo general verde muy suave
	define("TEXTO_PRINCIPAL", "#2e4a2f"); // Verde oscuro para textos principales
	define("FONDO_HEADER_GRADIENTE_INICIO", "#5cb85c"); // Verde claro para inicio del header
	define("FONDO_HEADER_GRADIENTE_FIN", "#3d8b3d"); // Verde oscuro para fin del header
	define("TEXTO_INVERTIDO", "#ffffff"); // Texto blanco para contraste
	define("TEXTO_HOVER_NAV", "#dfffd6"); // Verde muy claro para hover de navegación
	define("FONDO_BOTON", "#5cb85c"); // Verde claro para botones
	define("FONDO_BOTON_HOVER", "#3d8b3d"); // Verde oscuro para hover de botón
	define("FONDO_SECCION_CLARA", "#ffffff"); // Secciones claras
	define("FONDO_TARJETA", "#f4f9f4"); // Tarjetas con fondo verde muy suave
	define("TITULO_TARJETA", "#3d8b3d"); // Títulos en verde oscuro
	define("FONDO_FOOTER", "#2e4a2f"); // Verde muy oscuro para footer
	define("SOMBRA_SUAVE", "rgba(0, 0, 0, 0.1)"); // Sombra sutil
	define("SOMBRA_MUY_SUAVE", "rgba(0, 0, 0, 0.05)"); // Sombra muy suave

	/*Acceso a base de datos*/
	define("DB_HOST","srv1659.hstgr.io");
	define("DB_USER","u214138677_coala");
	define("DB_PASS","MizeNajo@25");
	define("DB_NAME","u214138677_coala");


	/*Acceso a correo electronico*/
	define('REMITENTE', 'noreply.coala@gmail.com');
	define('NOMBRE', 'COALA');
	define('PASSWORD', 'nmdywdybuibgyknw');

	define('HOST', 'smtp.gmail.com');
	define('PORT', '587');
	define('SMTP_AUTH', true);
	define('SMTP_SECURE', 'tls');

?>