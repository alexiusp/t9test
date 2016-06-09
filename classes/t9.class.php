<?php
class T9Class {
	private $db;
	function __construct() {
		$this->db = new mysqli("localhost", "mysql", "mysql", "t9test");
		if ($mysqli->connect_errno) {
			echo "Die Verbindung mit DB wurde nicht erstellt: " . $mysqli->connect_error;
		}
		echo "OK";
   }
}
?>
