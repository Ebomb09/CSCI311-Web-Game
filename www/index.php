<?php
	require_once 'include/db.php';
	$db = db_connect($db_host, $db_name, $db_user, $db_pass);
	$user_id = db_handleAccount($db);

	/* Page Structure */
	$page_name = 'Play Game';

	require 'include/head.php';

	if($user_id)
		require 'include/game.php';
	else
		require 'include/account-login.php';

	require 'include/tail.php';
?>
