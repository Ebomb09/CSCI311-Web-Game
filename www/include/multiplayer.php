<?php
	require_once 'db.php';
	$db = db_connect($db_host, $db_name, $db_user, $db_pass);
	$user_id = db_handleAccount($db);

	// Get user's name and whatever profile information
	$results = db_getUsersById($db, $user_id);

	if($results->rowCount() == 1){
		$row = $results->fetch();
		$name = $row['name'];
	}

	$file = $project_home.'/var/mp';	

	// Retrieve file contents
	$contents = '';

	if(file_exists($file))
		$contents = file_get_contents($file);

	$json = json_decode($contents, true);

	// Add POST variables
	if($user_id && $_SERVER['REQUEST_METHOD'] === 'POST'){
		$json['players'][$user_id]['name'] = $name;
		$json['players'][$user_id]['x'] = getPOST('x');
		$json['players'][$user_id]['y'] = getPOST('y');
		$json['players'][$user_id]['Vx'] = getPOST('Vx');
		$json['players'][$user_id]['Vy'] = getPOST('Vy');
	}

	// Re-encode the contents and save
	$contents = json_encode($json);
	file_put_contents($file, $contents);

	// Add user id to the json if logged in
	if($user_id){
		$json['user_id'] = $user_id;
		$contents = json_encode($json);
	}

	echo $contents;
?>
