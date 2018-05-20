<?php

	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBNAME', 'carthafind');

	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

	if (!$conn) {
		die("Connessione al database fallita: " . $conn->connect_error);
	}

?>
