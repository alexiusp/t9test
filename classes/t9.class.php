<?php
class T9Class {
	private $db;
	public $error;
	function __construct() {
		$this->db = new mysqli("localhost", "mysql", "mysql", "t9test");
		if ($mysqli->connect_errno) {
			$this->error = "Die Verbindung mit DB wurde nicht erstellt: " . $mysqli->connect_error;
		} else $this->error = null;
   }
	 private function encode($inputStr) {
		 $word = str_replace(' ', '', $inputStr);
		 $word = str_replace('\'', '', $inputStr);
		 $word = preg_replace('/[a-c]/i', '2', $word);
		 $word = preg_replace('/[d-f]/i', '3', $word);
		 $word = preg_replace('/[g-i]/i', '4', $word);
		 $word = preg_replace('/[j-l]/i', '5', $word);
		 $word = preg_replace('/[m-o]/i', '6', $word);
		 $word = preg_replace('/[p-s]/i', '7', $word);
		 $word = preg_replace('/[t-v]/i', '8', $word);
		 $word = preg_replace('/[w-z]/i', '9', $word);
		 return $word;
	 }
	 public function addContact($name, $vorname, $tel = "") {
		 $code = "";
		 $n = '\''.$this->db->real_escape_string($name).'\'';
		 $code .= $this->encode($n);
		 $v = '\''.$this->db->real_escape_string($vorname).'\'';
		 $code .= $this->encode($v);
		 $t = (empty($tel))? 'NULL' : '\''.$this->db->real_escape_string($tel).'\'';
		 if(!empty($tel)) $code .= $this->encode($t);
		 $code = '\''.$code.'\'';
		 $result = $this->db->query("insert into contacts(name, vorname, tel, code) values ($n, $v, $t, $code)");
		 if($result === false) $this->error = $this->db->error;
		 return $result;
	 }
	 public function search($word) {
		 $code = $this->encode($this->db->real_escape_string($word));
		 $codePattern = '\'%'.$code.'%\'';
		 $result = $this->db->query("select (name, vorname, tel) from contacts where code like $codePattern");
	 }

}
?>
