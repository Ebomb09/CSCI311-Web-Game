<?php

	$results = db_getUsersById($db, $user_id);

	if($results->rowCount() == 1){
		$row = $results->fetch();
		$name = $row['name'];
		$icon = $row['icon'];
	}

	echo "<h3> Currently logged in as $name</h3>";
	echo "<img src='images/$icon'>";
?>

<form method="POST">
	<input name="type" type="submit" value="logout">
</form>
