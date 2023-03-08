<!DOCTYPE html>
<html>

	<head>

	</head>

	<body>

		<table>

			<tr>
				<th> User </th>
				<th> Score </th>
			</tr>

			<?php
				require 'include.php';

				$db = db_connect();
				
				foreach ($db->query("SELECT * FROM scores") as $row){

					$userID = $row['user_id'];
					$score = $row['score'];

					print "<tr>";
					print "<td> $userID </td>";
					print "<td> $score </td>";
					print "</tr>";
				}
			?>

		</table>

	</body>

</html>
