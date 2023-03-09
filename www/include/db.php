<?php
	require_once 'project.php';

	$db_conf_dir = $project_home.'/conf/db.info';
	$db_conf = fopen($db_conf_dir, 'r');
	$db_host = trim(fgets($db_conf));
	$db_user = trim(fgets($db_conf));
	$db_pass = trim(fgets($db_conf));
	$db_name = trim(fgets($db_conf));
	fclose($db_conf);

	function getcookie($name){

		if(isset($_COOKIE["$name"]))
			return $_COOKIE["$name"];

		return '';
	}

	function getGET($name){

		if(isset($_GET["$name"]))
			return $_GET["$name"];

		return '';
	}

	function getPOST($name){

		if(isset($_POST["$name"]))
			return $_POST["$name"];

		return '';
	}

	function db_connect($db_host, $db_name, $db_user, $db_pass){

		try{
			$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
			return $db;
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage() . "<br>";
		}

		return false;
	}

	function db_getUsers($db, $id = false, $name = false){

		$results = false;

		if (!$db)
			return $results;

		if (is_numeric($id))
			$results = $db->query("SELECT * FROM users WHERE id=$id;");
		
		elseif (is_string($name))
			$results = $db->query("SELECT * FROM users WHERE name='$name';");

		elseif (!$id && !$name)
			$results = $db->query("SELECT * FROM users;");
 
		return $results;	
	}

	function db_getUsersByName($db, $name){
		return db_getUsers($db, false, $name);
	}

	function db_getUsersById($db, $id){
		return db_getUsers($db, $id, false);
	}

	function db_addUser($db, $name, $pass, $icon = ""){
		
		if(!$db)
			return false;

		// Check if the username is not already taken
		if(db_getUsersByName($db, $name)->rowCount() == 0){			
			return $db->query("INSERT INTO users(name, password, icon) VALUES ('$name', '$pass', '$icon');");
		}
		return false;
	}

	function db_getUserScores($db){

		$results = false;

		if(!$db)
			return $results;

		$results = $db->query("SELECT * FROM users JOIN scores ON users.id = scores.user_id;");

		return $results;
	}

	function db_addUserScore($db, $user_id, $score){

		if(!$db)
			return false;

		$found = false;

		// Check all user scores to see if already added
		foreach(db_getUserScores($db) as $row){

			if($row['user_id'] == $user_id){
				$found = true;

				if($row['score'] < $score){
					$db->query("UPDATE scores SET score=$score WHERE user_id=$user_id;");
					break;
				}
			}
		}

		// Try to add if not found
		if(!$found)
			$db->query("INSERT INTO scores(user_id, score) VALUES ($user_id, $score);");

		return true;
	}
?>
