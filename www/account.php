<!DOCTYPE html>
<html>

	<head>

	</head>

	<body>

		<?php
			require_once 'include/db.php';

			$db = db_connect($db_host, $db_name, $db_user, $db_pass);
			$user_id = db_handleAccount($db);

			if($user_id)
				require 'account-manage.php';
			else
				require 'account-login.php';
		?>

	</body>

</html>
