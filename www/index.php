<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/teststyles.css" rel="stylesheet">
		<title>Games Testing</title>
	</head>

	<body>

		<?php
			require_once 'include/db.php';

			$db = db_connect($db_host, $db_name, $db_user, $db_pass);
			$user_id = db_handleAccount($db);

			if($user_id)
				require 'game.php';
			else
				require 'account-login.php';
		?>

	</body>

</html>
