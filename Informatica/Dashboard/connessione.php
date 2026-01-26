<?php
$hostname = "localhost";
	$dbname = "mensajes";
	$user = "root";
	
	$pass = "";
	try{
	$conn = new PDO ("mysql:host=$hostname;dbname=$dbname", $user, $pass);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
    echo "Errore: " . $e->getMessage();
    die();
	}
	echo "connessione avvenuta con successo";
	?>