<?php
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
			switch ($act) {
				case 'new':
					require_once('/templates/add.form.html');
					break;
				case 'add':
					require_once('/classes/t9.class.php');
					$result = "";
					try {
						$t9 = new T9Class();
						if(isset($t9->error)) throw new Exception($t9->error, 1);
						$name = $_POST['name'];
						$vorname = $_POST['vorname'];
						$tel = $_POST['tel'];
						if(empty($name) || empty($vorname)) throw new Exception("Wrong parameters", 1);
						if($t9->addContact($name, $vorname, $tel) === false) {
							$result = $t9->error;
						} else {
							$result = "OK";
						}
					} catch (Exception $e) {
						$result = $e->message;
					}
					require_once('/templates/add.form.html');
					break;
				case 'search':
					
				default:
					require_once('/templates/index.html');
					break;
			}
		?>
	</body>
</html>
