<?php 

$hostname = "localhost";
$dbname = "5ipc03_auth";
$user = "5IPC03";
$passwd = "5IPC03";

try {

$conn = new PDO("mysql:host=$hostname;dbname=$dbname;",$user, $pass);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Errore: " . $e->getMessage());
}
?>