<?php 
	/**
	 * 
	 * BDAbstract.php esta clase es solo para realizar la conexión contra la base de datos
	 * 
	 * */
	class DBAbstract
	{

		private $db;
		
		/*Cuando se crea el objeto se genera la conexion a la base de datos*/
		function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}

		/*Por ahora solo sirve para hacer select*/
		
		public function query($ssql){
			return $this->db->query($ssql)->fetch_all(MYSQLI_ASSOC);
		}
	}
?>