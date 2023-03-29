<?php
	require_once 'include/db.php';
	$db = db_connect($db_host, $db_name, $db_user, $db_pass);
	$user_id = db_handleAccount($db);

	/* Page Structure */
	$page_name = 'High Scores';

	require 'include/head.php';
?>

<table class="highscores">

	<tr>
		<th class="icon"> <!-- Icon --> </th>
		<th class="username"> User </th>
		<th class="score"> Score </th>
	</tr>

	<?php
		// Check if there are any scores to submit
		$score_added = false;

		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			// Generic type checking
			if($user_id && is_numeric($_POST['score'])){

				// Cast to appropriate typings
				$score = (int)$_POST['score'];

				// Try to add user score
				db_addUserScore($db, $user_id, $score);
				$score_added = true;
			}
		}

		// Get all scores from database and append to the table
		foreach (db_getUserScores($db) as $row){

			$scorer_id = $row['user_id'];
			$name = $row['name'];
			$score = $row['score'];
			$icon = $row['icon'];

			// For now how to show what score was added
			if($score_added && $user_id == $scorer_id)
				$name = '>>' . $name . '<<';

			print "<tr>";
			print "<td class='icon'> <img src='images/$icon' alt='$name profile icon'> </td>";
			print "<td class='username'> $name </td>";
			print "<td class='score'> $score </td>";
			print "</tr>";
		}
	?>

</table>


<?php
	require 'include/tail.php';
?>
