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


// MySQL query
$sql = "SELECT u.name, u.icon, s.score, s.user_id FROM users AS u JOIN scores AS s ON u.id = s.user_id ORDER BY s.score DESC, u.name ASC";

// Prepare statement
$stmt = $db->prepare($sql);

// Execute statement
$stmt->execute();

$i = 1;
// Check if any rows were returned
if ($stmt->rowCount() > 0) {
    // Output data of each row
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        	$icon = $row["icon"];
        	$score = $row["score"];        	
        	$name = $row["name"];
	    // Top 3 scores
		if($i < 4){
			print "<tr>";
			print "<td class='icon'> <img src='images/$icon' alt='$name profile icon'> </td>";
			print "<td class='username'><b> $name </b></td>";
			print "<td class='score'><b> $score </b></td>";
			print "</tr>";
	// The rest
		} else {
			print "<tr>";
			print "<td class='icon'> <img src='images/$icon' alt='$name profile icon'> </td>";
			print "<td class='username'> $name </td>";
			print "<td class='score'> $score </td>";
			print "</tr>";
		}
		$i++;
    }
} else {
    echo "0 results";
}

// Close statement
$stmt = null;
?>
</table>



<?php
	require 'include/tail.php';
?>
