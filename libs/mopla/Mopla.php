<?php

/**
 * clase Mopla
 * 
 * Motor de plantillas
 */
class Mopla
{

	private $name_tpl;
	private $buffer_tpl;

	function __construct($tpl_name)
	{
		$this->name_tpl = $tpl_name;

		$this->load_tpl();
	}

	/**
	 * 
	 * Carga la tpl en buffer_tpl
	 * 
	 * */
	private function load_tpl()
	{

		/* en caso que la vista no exista */
		if (!file_exists('views/' . $this->name_tpl . 'View.tpl.php')) {

			echo "<b>Motor de plantilla:</b> Mopla<br>";
			echo "<b>Error:</b> no se encontro la vista <b>" . $this->name_tpl . "</b>";

			exit();
		}

		/* la vista existe */

		$this->buffer_tpl  = file_get_contents('views/' . $this->name_tpl . 'View.tpl.php');

		/*****************************/
		/* Para levantar los extends por defecto (Sistema probablemente temporal) */

		$array_extends_default = ["htmlHead", "appHeader"];

		/* recorro el arreglo que tiene los extends por defecto*/
		foreach ($array_extends_default as $key => $value) {


			$buffer_extends = file_get_contents('views/extends/' . $value . 'Extends.tpl.php');

			$this->buffer_tpl = str_replace("@extends(" . $value . ")", $buffer_extends, $this->buffer_tpl);
		}

		/*******************************/


		/* para la autocarga de variables por defecto en la plantilla */
		$environment_vars = [
			"FONDO_PAGINA",
			"TEXTO_PRINCIPAL",
			"FONDO_HEADER_GRADIENTE_INICIO",
			"FONDO_HEADER_GRADIENTE_FIN",
			"TEXTO_INVERTIDO",
			"TEXTO_HOVER_NAV",
			"FONDO_BOTON",
			"FONDO_BOTON_HOVER",
			"FONDO_SECCION_CLARA",
			"FONDO_TARJETA",
			"TITULO_TARJETA",
			"FONDO_FOOTER",
			"SOMBRA_SUAVE",
			"SOMBRA_MUY_SUAVE",
			"APP_NAME",
			"APP_DESCRIPTION",
			"APP_AUTHOR",
			"APP_SLOGAN",
			"APP_URL",
			"APP_VERSION"
		];

		foreach ($environment_vars as $key => $var) {
			$array_replace_assoc[$var] = constant($var);
		}


		/*$array_replace_assoc["APP_DESCRIPTION"] = APP_DESCRIPTION;
			$array_replace_assoc["APP_AUTHOR"] = APP_AUTHOR;*/

		$this->assignVar($array_replace_assoc);

		return true;
	}

	/*busca {{ lo que sea }}*/
	function assignVar($array_replace_assoc)
	{

		foreach ($array_replace_assoc as $var => $value) {
			$this->buffer_tpl = str_replace("{{ " . $var . " }}", $value, $this->buffer_tpl);
		}
		
		// Procesar assets con versiones
		$this->processAssets();
	}
	
	/**
	 * Procesa assets agregando versiones automáticamente
	 */
	private function processAssets()
	{
		// Buscar y reemplazar enlaces CSS
		$this->buffer_tpl = preg_replace_callback(
			'/href=["\']([^"\']*\.css)["\']/',
			function($matches) {
				return 'href="' . CacheHelper::asset($matches[1]) . '"';
			},
			$this->buffer_tpl
		);
		
		// Buscar y reemplazar scripts JS
		$this->buffer_tpl = preg_replace_callback(
			'/src=["\']([^"\']*\.js)["\']/',
			function($matches) {
				return 'src="' . CacheHelper::asset($matches[1]) . '"';
			},
			$this->buffer_tpl
		);
	}

	function printExtends($array_extends_assoc)
	{
		/* recorro el arreglo que tiene los extends por defecto*/
		foreach ($array_extends_assoc as $key => $value) {

			$buffer_extends = file_get_contents('views/extends/' . $value . 'Extends.tpl.php');

			$this->buffer_tpl = str_replace("@extends(" . $value . ")", $buffer_extends, $this->buffer_tpl);
		}
	}

	function printToScreen()
	{
		echo $this->buffer_tpl;
	}
}
