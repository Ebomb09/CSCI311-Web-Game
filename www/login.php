<!DOCTYPE html>
<html lang="en">

	<head>
	</head>

	<body>

	<div class="login">
		<h1>Login</h1>
		<?php
			// hard coded login and password
			$hcusr = "test";
			$hcpss = "1234";
			$username = $_POST["username"];
			$password = $_POST["password"];

			if ($username == $hcusr && $password == $hcpss){
				echo "<h2>Welcome ". $username . "!</h2><br>";
				setcookie("username", $username, time() + 3600);	// 24 hours expiration
				setcookie("password", $password, time() + 3600);
			} else {
				echo "<h2>". $username . " is not a current user</h2><br>";
			}
		?>
	</div>
	</body>
</html>
