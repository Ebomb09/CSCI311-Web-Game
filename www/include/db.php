<?php
	require_once 'project.php';

	$db_conf_dir = $project_home.'/conf/db.info';
	$db_conf = fopen($db_conf_dir, 'r');
	$db_host = trim(fgets($db_conf));
	$db_user = trim(fgets($db_conf));
	$db_pass = trim(fgets($db_conf));
	$db_name = trim(fgets($db_conf));
	fclose($db_conf);

	$errors = "";

	function getcookie($name){

		if(isset($_COOKIE["$name"]))
			return $_COOKIE["$name"];

		return '';
	}

	function getsession($name){
		session_start();

		if(isset($_SESSION["$name"]))
			return $_SESSION["$name"];

		return '';
	}

	function setsession($name, $val){
		session_start();		

		$_SESSION["$name"] = $val;
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

		if (is_numeric($id)){
			$results = $db->prepare('SELECT * FROM users WHERE id=?;');
			$results->bindParam(1, $id, PDO::PARAM_INT);
			$results->execute();

		}elseif (is_string($name)){
			$results = $db->prepare('SELECT * FROM users WHERE name=?;');
			$results->bindParam(1, $name, PDO::PARAM_STR, 16);
			$results->execute();

		}elseif (!$id && !$name)
			$results = $db->query('SELECT * FROM users;');
 
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

		$results = db_getUsersByName($db, $name);

		// Check if the username is not already taken
		if($results && $results->rowCount() == 0){			
			$stmt = $db->prepare('INSERT INTO users(name, password, icon) VALUES (?, ?, ?);');
			$stmt->bindParam(1, $name, PDO::PARAM_STR, 16);
			$stmt->bindParam(2, $pass, PDO::PARAM_STR, 32);
			$stmt->bindParam(3, $icon, PDO::PARAM_STR, 512);

			if($stmt->execute())
				return $stmt;
		}
		return false;
	}

	function db_updateUserIcon($db, $user_id, $icon){

		if(!$db)
			return false;

		$results = db_getUsersById($db, $user_id);
		
		if($results && $results->rowCount() == 1){
			$stmt = $db->prepare('UPDATE users SET icon=? WHERE id=?');
			$stmt->bindParam(1, $icon, PDO::PARAM_STR, 512);
			$stmt->bindParam(2, $user_id, PDO::PARAM_INT);

			if($stmt->execute())
				return true;
		}
		return false;	
	}

	function db_getUserScores($db){

		$results = false;

		if(!$db)
			return $results;

		$results = $db->query('SELECT * FROM users JOIN scores ON users.id = scores.user_id;');

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
					$stmt = $db->prepare('UPDATE scores SET score=? WHERE user_id=?;');
					$stmt->bindParam(1, $score, PDO::PARAM_INT);
					$stmt->bindParam(2, $user_id, PDO::PARAM_INT);
					$stmt->execute();
					break;
				}
			}
		}

		// Try to add if not found
		if(!$found){
			$stmt = $db->prepare('INSERT INTO scores(user_id, score) VALUES (?, ?);');
			$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
			$stmt->bindParam(2, $score, PDO::PARAM_INT);
			$stmt->execute();
		}

		return true;
	}

	function db_createSession($db, $user_id){

		if(!$db)
			return false;

		// Try to delete from the sessions table
		$stmt = $db->prepare('DELETE FROM sessions WHERE user_id=?;');
		$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
		$stmt->execute();

		// Add new session
		$unique_id = random_bytes(16);
		$stmt = $db->prepare('INSERT INTO sessions(id, user_id) VALUES (?, ?);');
		$stmt->bindParam(1, $unique_id, PDO::PARAM_STR, 16);
		$stmt->bindParam(2, $user_id, PDO::PARAM_INT);
		$stmt->execute();

		return $unique_id;
	}

	function db_deleteSession($db, $session){
	
		if(!$db)
			return false;

		// Try to delete from the sessions table
		$stmt = $db->prepare('DELETE FROM sessions WHERE id=?;');
		$stmt->bindParam(1, $session, PDO::PARAM_STR, 16);
		$stmt->execute();

		return true;
	}

	function db_handleAccount($db){

		if(!$db)	
			return false;

		$session = getsession('session');
		$event_type = getPOST('type');
		$event_user = getPOST('username');
		$event_pass = getPOST('password');
		
		global $errors;

		switch($event_type){

			case 'login': 

				$results = db_getUsersByName($db, $event_user);
		
				if(!$results || $results->rowCount() != 1){
					$errors = "User $event_user doesn't exist!";
					break;
				}				

				$row = $results->fetch();

				if($row['password'] == $event_pass){
					$session = db_createSession($db, $row['id']);
					setsession('session', $session);
			
				}else
					$errors = "Incorrect Password!";
		
				break;

			case 'create':

				if(db_addUser($db, $event_user, $event_pass)){

					// Check if actual user account was created
					$results = db_getUsersByName($db, $event_user);
			
					if(!$results || $results->rowCount() != 1){
						$errors = "Failed to create user $event_user!";
						break;
					}				

					$row = $results->fetch();

					if($row['password'] == $event_pass){
						$session = db_createSession($db, $row['id']);
						setsession('session', $session);
				
					}else
						$errors = "Incorrect Password!";

				}else{
					$errors = "User $event_user already exists!";
				}	
				break;

			case 'logout':
				db_deleteSession($db, $session);
				setsession('session', '');
				$session = 0;
				break;
		}

		$user_id = 0;

		// Try and get the session using the id
		$stmt = $db->prepare('SELECT * FROM sessions WHERE id=?;');
		$stmt->bindParam(1, $session, PDO::PARAM_STR, 16);
		$stmt->execute();

		$results = $stmt->fetchAll();

		if($stmt->rowCount() == 1 && $results[0]['id'] == $session)
			$user_id = $results[0]['user_id'];

		return $user_id;
	}
?>
