<?php

/**
 * clase Mopla
 * 
 * Motor de plantillas
 */
class Extend
{

	private $name_extend;
	private $buffer_extend;

    private $original_extend;

	function __construct($extend_name)
	{
		$this->name_extend = $extend_name;

		$this->load_extend();
	}

	/**
	 * 
	 * Carga el componente en buffer_extend
	 * 
	 * */
	private function load_extend()
	{

		/* en caso que la vista no exista */
		if (!file_exists(filename: 'views/extends/' . $this->name_extend . 'Extends.tpl.php')) {

			echo "<b>Motor de componentes:</b> Extend<br>";
			echo "<b>Error:</b> No se encontro el componente <b>" . $this->name_extend . "</b>";

			exit();
		}

		/* la vista existe */

		$this->buffer_extend  = file_get_contents(filename: 'views/extends/' . $this->name_extend . 'Extends.tpl.php');

        $this->original_extend = $this->buffer_extend;

		return true;
	}

	/*busca {{ lo que sea }}*/
	function assignVar($array_replace_assoc)
	{
		foreach ($array_replace_assoc as $var => $value) {
			$this->buffer_extend = str_replace("{{ " . $var . " }}", $value, $this->buffer_extend);
		}

        $temp_array = $this->buffer_extend;

        $this->reset();

        return $temp_array;
	}

    function reset(){
        $this->buffer_extend = $this->original_extend;
    }

	function printExtend()
	{
		return $this->buffer_extend;
	}
}
