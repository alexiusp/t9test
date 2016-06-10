<?php
/**
 * Main entry of application
 *
 * @package T9Contacts
 * @author    Aleksei Podgaev
 * @copyright 2016
 */
 namespace T9Contacts;

/**
 * include T9Class
 */
 require_once('/classes/t9.class.php');

 /**
  * "action" - defines what should we do
  */
$act = (array_key_exists('act', $_GET))? $_GET['act'] : 'index';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>T9 Test</title>
	</head>
	<body>
		<?php
			// decide which function to call
			// "router" in terms of MVC
			switch ($act) {
				case 'add':
					$result = "";
					// if post is filled with data we should add a new contact
					if(is_array($_POST) && array_key_exists('name', $_POST)) {
						try {
							$t9 = new T9Class();
							if(isset($t9->error))
								throw new Exception("Verbindungfehler: ".$t9->error, 1);
							/**
							 * @todo it would be better to implement some validation
							 */
							$name = $_POST['name'];
							$vorname = $_POST['vorname'];
							$tel = $_POST['tel'];
							if(empty($name) || empty($vorname))
								throw new Exception("Falsche Eingaben", 1);
							$result = $t9->addContact($name, $vorname, $tel);
						} catch (Exception $e) {
							$result = $e->getMessage();
						}
					}
					// show form for adding new contact
					require_once('/templates/add.form.html');
					break;
				case 'search':
					$result = null;
					$error = "";
					$word = "";
					// if POST is filled with data we should make a search
					// for given keyword in a database
					if(is_array($_POST) && array_key_exists('word', $_POST)) {
						try {
							$t9 = new T9Class();
							if(isset($t9->error))
								throw new Exception("Verbindungfehler: ".$t9->error, 1);
							/**
							 * @todo it would be better to implement some validation
							 */
							$word = $_POST['word'];
							$result = $t9->search($word);
							if($result === false) {
								$error = "DB sagt: ".$t9->error;
							} else {
								require_once('/templates/result.html');
							}
						} catch (Exception $e) {
							$error = "PHP sagt: ".$e->getMessage();
						}
					}
					require_once('/templates/search.html');
					break;
				default:
					require_once('/templates/index.html');
					break;
			}
		?>
	</body>
</html>
