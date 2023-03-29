<?php
	require_once 'db.php';
	$db = db_connect($db_host, $db_name, $db_user, $db_pass);
	$user_id = db_handleAccount($db);
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> <?php echo $page_name; ?> </title>
	<link rel="stylesheet" href="css/teststyles.css">
</head>

<body>

<nav>
	<h1> CSCI311 Web Game </h1>
	<ul>
		<li> <a href='index'> Play Game </a> </li>
		<li> <a href='scores'> View High Scores </a> </li>

		<?php
		$name = 'Login';

		// Try to get account name if logged in
		if($user_id){
			$result = db_getUsersById($db, $user_id);

			if($result->rowCount() == 1)
				$name = $result->fetch()['name'];
		}
		echo "<li> <a href='account'> $name </a> </li>";

		?>
	</ul>
</nav>

<div class='content'>

	<h2> <?php echo $page_name; ?> </h2>
