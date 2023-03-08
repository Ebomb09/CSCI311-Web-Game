<!DOCTYPE html>
<html>

	<head>

	</head>

	<body>

		<table>

			<tr>
				<th> User </th>
				<th> <!-- Icon --> </th>
				<th> Score </th>
			</tr>

			<?php
				require_once 'include/db.php';

				$db = db_connect($db_host, $db_name, $db_user, $db_pass);

				// Check if there are any scores to submit
				$added = 'none';

				if($_POST['post'] == '1'){

					// Generic type checking
					if(is_numeric($_POST['score']) && is_numeric($_POST['user_id']) ){

						// Cast to appropriate typings
						$user = $_POST['user_id'];
						$score = (int)$_POST['score'];

						// Try to add user score
						db_addUserScore($db, $user, $score);

						$added = $user;
					}
				}

				// Get all scores from database and append to the table
				foreach (db_getUserScores($db) as $row){

					$user_id = $row['user_id'];
					$name = $row['name'];
					$score = $row['score'];
					$icon = $row['icon'];
	
					// For now how to show what score was added
					if($added == $user_id)
						$name = '>>' . $name . '<<';

					print "<tr>";
					print "<td> $name </td>";
					print "<td> <img src='images/$icon' alt='$userID profile icon'> </td>";
					print "<td> $score </td>";
					print "</tr>";
				}
			?>

		</table>

	</body>

</html>
