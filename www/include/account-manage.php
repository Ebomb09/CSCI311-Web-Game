<?php

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$icon = getPOST('icon');		

		if($icon != '')
			db_updateUserIcon($db, $user_id, $icon);
	}

	$results = db_getUsersById($db, $user_id);

	if($results->rowCount() == 1){
		$row = $results->fetch();
		$name = $row['name'];
		$icon = $row['icon'];
	}

	echo "<h3> Logged in as $name</h3>";
	echo "<img src='$icon'>";
?>

<hr>

<h3> Edit Profile </h3>

<form method="POST">
	<h4> Icon </h4>

	<?php foreach(glob("images/icons/*.png") as $file){ ?>
		<label>
			<img src='<?php echo $file; ?>'>
			<input type='radio' name='icon' value='<?php echo $file; ?>' <?php if ($file == $icon) echo 'checked'; ?>>
		</label>
	<?php } ?>

	<input type="submit" value="Save changes">
</form>

<hr>

<form method="POST">
	<input name="type" type="submit" value="logout">
</form>
