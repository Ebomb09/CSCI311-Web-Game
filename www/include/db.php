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

	function db_handleAccount($db){

		if(!$db)	
			return false;

		$user_id = getcookie('user_id');
		$event_type = getPOST('type');
		$event_user = getPOST('username');
		$event_pass = getPOST('password');

		switch($event_type){

			case 'login': 

				foreach(db_getUsersByName($db, $event_user) as $row){

					if($row['password'] == $event_pass){
						setcookie('user_id', $row['id']);
						$user_id = $row['id'];
					}
				}
				break;

			case 'create':

				if(db_addUser($db, $event_user, $event_pass)){

					// Check if actual user account was created
					foreach(db_getUsersByName($db, $event_user) as $row){
						
						if($row['password'] == $event_pass){
							setcookie('user_id', $row['id']);
							$user_id = $row['id'];
						}
					}
				}
				break;

			case 'logout':
				setcookie('user_id', '');
				$user_id = 0;
				break;
		}

		if($user_id > 0 && db_getUsersById($db, $user_id)->rowCount() == 0)
			$user_id = 0;

		return $user_id;
	}
?>
