<?php
/**
 * Class implementing the storage of phone contacts with T9-like search
 *
 * The idea is to store an additional field in the database with "code" which
 * represents all necessary fields "encoded" to numbers of the phone keyboard
 * where these letters belongs to
 *
 * @package T9Contacts
 * @author    Aleksei Podgaev
 * @copyright 2016
 */
 namespace T9Contacts;

class T9Class {
	private $db;
	public $error;

	function __construct() {
		$this->db = new \mysqli("localhost", "mysql", "mysql", "t9test");
		if ($mysqli->connect_errno) {
			$this->error = "Die Verbindung mit DB wurde nicht erstellt: " . $mysqli->connect_error;
		} else $this->error = null;
   }

	 /**
	  * Encoding function
		*
		* encodes all characters
		* in a given string to phone keys
	  *
	  * @param     string $inputStr string to encode
	  * @return    string
	  */
	 private function encode($inputStr) {
		 //
		 /**
		  * remove spaces and slashes
			* may be we should remove other symbols like "-" too
		  */
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

	/**
	 * Adds contact to the database
	 *
	 * @param     string $name Surname of the contact
	 * @param     string $vorname First name of the contact
	 * @param     string $tel optional phone number
	 * @return    string
	 */
	 public function addContact($name, $vorname, $tel = "") {
		 /**
		  * resulting string
		  */
		 $code = "";
		 /**
		  * Escape and quote input parameters and encode them
		  *
		  * @todo implement proper validation of input and remove escaping
		  */
		 $n = '\''.$this->db->real_escape_string($name).'\'';
		 $code .= $this->encode($n);
		 $v = '\''.$this->db->real_escape_string($vorname).'\'';
		 $code .= $this->encode($v);
		 $t = (empty($tel))? 'NULL' : '\''.$this->db->real_escape_string($tel).'\'';
		 if(!empty($tel)) $code .= $this->encode($t);
		 /**
		  * May be it has sense to add unencoded strings to the indexed field
		  * so we can make more precise search for names
		  */
		 $code = '\''.$code.'\'';
		 $result = $this->db->query("insert into `contacts` (`name`, `vorname`, `tel`, `code`) values ($n, $v, $t, $code)");
		 if($result === false) return $this->db->error;
		 return "Der eingegebene Kontakt wurde erfolgreich hinzugefügt";
	 }

	 /**
	  * Searches the database for a given string
		*
		* converts given string to a "code",
		* then searches for a substring in a field "code"
	  *
	  * @param     string $word search keyword given by the user
	  * @return    mixed
	  */
	 public function search($word) {
		 $code = $this->encode($this->db->real_escape_string($word));
		 $codePattern = '\'%'.$code.'%\'';
		 $result = $this->db->query("select `name`, `vorname`, `tel` from `contacts` where `code` like $codePattern");
		 if($result === false) $this->error = $this->db->error;
		 return $result;
	 }

}
?>
