<?php

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$icon = getPOST('icon');
		$currpass = getPOST('currpass');	
		$newpass1 = getPOST('newpass1');	
		$newpass2 = getPOST('newpass2');	

		if($icon != '')
			db_updateUserIcon($db, $user_id, $icon);
	}

	$results = db_getUsersById($db, $user_id);

	if($results->rowCount() == 1){
		$row = $results->fetch();
		$name = $row['name'];
		$icon = $row['icon'];
		$password_hash = $row['password_hash'];
	}

	
	if ($currpass != '' && $newpass1 != '' && $newpass2 != ''){
		if(password_verify($currpass, $password_hash)){
			if ($newpass1 == $newpass2){
				$password_hash = password_hash($newpass1, PASSWORD_DEFAULT);
				db_updateUserPassword($db, $user_id, $password_hash);
			}	
		}
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

<form method="POST">
	<h4> Change Password </h4>
	<table>
		<tr>
			<th></th>
			<th></th>
		</tr>	
		<tr>
			<td>Current password</td>
			<td>
					<div class="text-input">
						<input id="currpass" name="currpass" type="password" placeholder="current password" required>
					</div>
			</td>
		</tr>
		<tr>
			<td>New password </td>
			<td>
				<div class="text-input">
					<input id="newpass1" name="newpass1" type="password" placeholder="new password" required>
				</div>
			</td>
		</tr>
		<tr>
			<td>New password again</td>
			<td>
				<div class="text-input">
					<input id="newpass2" name="newpass2" type="password" placeholder="new password" required>
				</div>				
			</td>
		</tr>
	</table>	
	
	

	<input type="submit" value="Save changes">
</form>

<hr>

<form method="POST">
	<input name="type" type="submit" value="logout">
</form>
