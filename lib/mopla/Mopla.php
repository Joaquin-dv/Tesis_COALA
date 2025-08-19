<?php 
	
	class Mopla{

		private $vista;
		private $buffer;

		function __construct($name_view){
			// levantar la plantilla
			$this->buffer = file_get_contents("views/".$name_view."View.html");

			$this->vista = $this->buffer;
		}

		function restore(){
			$this->buffer = $this->vista;
		}


		function setVar($name_var, $value){
			// alterar la plantilla 
			$this->buffer=str_replace("{{".$name_var."}}", $value, $this->buffer);
		}

		function setVars($vars){
			foreach ($vars as $key => $value) {

				$this->setVar($key, $value);
			}
		}

		function getBuffer(){
			return $this->buffer;
		}

		function print(){
			// imprimir la plantilla
			echo $this->buffer;
		}

	
	}


 ?>