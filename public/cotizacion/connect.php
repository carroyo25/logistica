<?php 
	$dsn = "mysql:dbname=logistica;host=localhost";
	$user = "root";
	//$password = "odigo72";
	$password = "zBELTUAKpNQvCOl6";
	$errorDbConexion = true;

	try {
		$pdo = new PDO($dsn,$user,$password);
		$errorDbConexion = false;
	}
	catch ( PDOException $e) {
		echo 'Error al conectarnos ' . $e->getMessage();
	}

	$pdo->exec("SET CHARACTER SET utf8"); // <--utf8
?>