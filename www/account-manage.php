<div>

<?php

	$results = db_getUsersById($db, $user_id);

	if($results->rowCount() == 1){
		$row = $results->fetch();
		$name = $row['name'];
		$icon = $row['icon'];
	}

	echo "<h1> Currently logged in as $name</h1>";
	echo "<img src='images/$icon'>";
?>

	<form method="POST">
		<input name="type" type="submit" value="logout">
	</form>

</div>
