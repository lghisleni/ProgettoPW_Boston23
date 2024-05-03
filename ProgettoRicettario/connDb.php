<?php
	$servername= "localhost";
	$username = "programmazzioneweblg";
	$dbname= "my_programmazioneweblg";
	$password = null;
	$error = false;
	
	try {
		$conn = new PDO("mysql:host=" . $servername . ";" .
										"dbname=" . $dbname, 
											$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, 
												PDO::ERRMODE_EXCEPTION);
	} catch(PDOException$e) {
		echo "<p>DB Error: " . $e->getMessage() . "</p>";
		$error = true;
	}
?>
