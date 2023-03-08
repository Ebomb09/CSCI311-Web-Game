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
				require_once 'include.php';

				$db = db_connect($db_host, $db_name, $db_user, $db_pass);

				// Check if there are any scores to submit
				if($_GET['submit'] == '1'){

					// Generic type checking
					if(is_numeric($_GET['score']) && is_numeric($_GET['user_id']) ){

						// Cast to appropriate typings
						$user = $_GET['user_id'];
						$score = (int)$_GET['score'];

						// Try to add user score
						db_addUserScore($db, (int)$_GET['user_id'], (int)$_GET['score']);
					}
				}

				foreach (db_getUserScores($db) as $row){

					$userID = $row['name'];
					$score = $row['score'];
					$icon = $row['icon'];

					print "<tr>";
					print "<td> $userID </td>";
					print "<td> <img src='images/$icon' alt='$userID profile icon'> </td>";
					print "<td> $score </td>";
					print "</tr>";
				}
			?>

		</table>

	</body>

</html>
