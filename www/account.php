<!DOCTYPE html>
<html>

	<head>

	</head>

	<body>

		<?php
			require_once 'include/db.php';

			$db = db_connect($db_host, $db_name, $db_user, $db_pass);
			$user_id = getcookie('user_id');
			$logged_in = false;		

			$event_type = getPOST('type');
			$event_user = getPOST('username');
			$event_pass = getPOST('password');

			switch($event_type){

				case 'login': 

					foreach(db_getUsersByName($db, $event_user) as $row){

						if($row['password'] == $event_pass){
							setcookie('user_id', $row['id']);
							$user_id = $row['id'];
						}
					}
					break;

				case 'create':

					if(db_addUser($db, $event_user, $event_pass)){

						// Check if actual user account was created
						foreach(db_getUsersByName($db, $event_user) as $row){
							
							if($row['password'] == $event_pass){
								setcookie('user_id', $row['id']);
								$user_id = $row['id'];
							}
						}
					}
					break;

				case 'logout':
					setcookie('user_id', '');
					$user_id = 0;
					break;
			}

			if($user_id > 0 && db_getUsersById($db, $user_id)->rowCount() > 0)
				$logged_in = true;

			if($logged_in)
				require 'account-manage.php';
			else
				require 'account-login.php';
		?>

	</body>

</html>
