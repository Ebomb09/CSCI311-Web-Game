<?php
	$db_conf_dir = "../conf/db.info";
	$db_conf = fopen($db_conf_dir, "r");
	$db_host = trim(fgets($db_conf));
	$db_user = trim(fgets($db_conf));
	$db_pass = trim(fgets($db_conf));
	$db_name = trim(fgets($db_conf));
	fclose($db_conf);

	function db_connect(){

		try{
			$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
			return $db;
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage() . "<br>";
		}

		return false;
	}
?>

