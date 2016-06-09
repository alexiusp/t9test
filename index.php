<?php
$act = (array_key_exists('act', $_GET))? $_GET['act'] : 'index';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">
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
					$t9 = new T9Class();
					break;
				default:
					require_once('/templates/index.html');
					break;
			}
		?>
	</body>
</html>
