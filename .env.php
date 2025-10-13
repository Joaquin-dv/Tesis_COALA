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

	/*Google Document AI credentials*/
	define('GOOGLE_PROJECT_ID', 'iron-fire-474523-h8');
	define('GOOGLE_PRIVATE_KEY_ID', '2af244f9ef209c834359fbf56195db2fdadb7932');
	define('GOOGLE_PRIVATE_KEY', "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDC5RosxQK3bREg\nN0bDyVeYZwcjouSjhEP2pO1aZRPv4dwyUfM7rGQly04dH2dx4Gmpx6kJShvx7uKd\nweoDpi8rwHTjXzXi6l3KNex6SuFsPnRuKY944edt9DcToP7lugP8MMerVyXXfZaz\nfL8RuQ8Wb67qoDhLf0xshTOh4qtFzqaoQ8cgAy5vRM/rz5JC1jT4QnZMsiws2XAI\nQMDEdkwytHexdq6wW9q3c/BaU01nyu05CK1oYhZUp4wXcgz5p9Uzuz79rLL3FSrQ\nyIBs5pG16z3h48nLbfw8NtHuLLVv/hP6M0t6IbcfcZyqjd/dRk4c6vKOJkEuSTd/\nIqt2iAr7AgMBAAECggEAFpi2iLNM5dR89FXEXCXnhJGJd93GkZjfwmSL6numrJzs\nyV7MC0KpF+KzC1hdR4xD4/30wNF5XPscBjt59PNbK8D0LHqZBlazCiNnaCRvb3vs\nRuuFqXfTu+FhU2LKuvruxFophFml1w7GHshbZOQmdiz7xFNQQ5yeUUS+YEomHoEr\n8H6NVksgEOKKPKpo9RhyfnTXBlYjmLIQISxViD+ndH3080P+h3GT5swMiOCPIw42\noK8ufyWVLWExSPAShyjtK9WNrhn7hpl/01EqhrJfj9w0t5tyhxf/JawkrQcyBrTk\nOYV1P9sHbq89aoRbMMvs5nCbfO9iNSumM4E3FLhS8QKBgQDgd2dVfvbAHWiyabf6\na1WrEi9GvIsVMHJzDYMxB5iKGFEck1kFiTbTRM+UftH/huOrRko8YHePk5V2Gp3R\n9L7brB+yCE0bSdy+ax+ATxxBHSF4APcankcgA6xLnbDWWSd4CzmfXZ/zPRbF3X8/\nm7sEbVPWwN2EDPVedgj37wXg8QKBgQDeRjRdp9aqQWX1Enx57JQ2C6EGYw1NO9qJ\ncg7RIQGMQpni86Iwz634huijlngoG3I+OpRJxR8ObbT+FF3tHjrzRXKzMUDhBNUn\n5Jc72tmVvUzPms7uQtkPV07UXs6MBxcyYgpOdQDe/4Z7cIhpVbREIaaB/B5mwwgZ\nJOGetedqqwKBgClGa47yGMd84Oqlu/nlUMxzPJRCSUTtMq5rVqtmXStPi2K4yY7W\nC2nP5mfE5jKZiDXPaAkwJ+wT1FDyVgDsg0f7n5xqIFubOmcdZZ5/bY+fnq7lZorT\nffqqEj2ZUpIntLVDQyZF3gqpOg3KTALTTRFkVR1RO6pzg48KH14P6sHRAoGAAkaX\nvKm+Qen/gD3bNmhcsBz4XhdfiH3nY+beDfgXivcXmkJCU9ucfWHsOdiNjGOTjN2O\nrR6ujbhD1SIiQA5CLkF0xi7n6iXhhNILVlqMRcM1aR69paTbkhOjw/rghICCFUr5\nrgn2o1Hcb6EcVG/DM7tgeA47xXOTvqYpBTX5k5cCgYBQWnp+KKwCJQSyeiNmcLx+\n+3FA9PKmBt8L9C49vMNRyOYrU5NTKUuT834ix4sc42kWWRLGnSeVG/QFLboyM2Tt\nepMnJO2emS1KA0f5AvvlUSGVL9BA+My6ZyPPSz+9B/3ebpkgHD7Cx9E+yx8pfzm8\nA6ab8n9xIaKapREHrOwPrA==\n-----END PRIVATE KEY-----\n");
	define('GOOGLE_CLIENT_EMAIL', 'coala-527@iron-fire-474523-h8.iam.gserviceaccount.com');
	define('GOOGLE_CLIENT_ID', '103757974372103326083');
	define('GOOGLE_AUTH_URI', 'https://accounts.google.com/o/oauth2/auth');
	define('GOOGLE_TOKEN_URI', 'https://oauth2.googleapis.com/token');
	define('GOOGLE_AUTH_PROVIDER_X509_CERT_URL', 'https://www.googleapis.com/oauth2/v1/certs');
	define('GOOGLE_CLIENT_X509_CERT_URL', 'https://www.googleapis.com/robot/v1/metadata/x509/coala-527%40iron-fire-474523-h8.iam.gserviceaccount.com');
	define('GOOGLE_UNIVERSE_DOMAIN', 'googleapis.com');

?>