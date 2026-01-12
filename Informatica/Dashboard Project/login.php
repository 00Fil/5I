<?php

session_start();
require_once("connessione.php");

if ($_SERVER['REQUEST_METHOD' === 'POST']); {

$input = $_POST['username'];
$passwd = $_POST['password'];

try {


    // (1) Recupero dati utente
    $sql = "SELECT * FROM users WHERE username = :input OR email = :input";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $user=$stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = "Credenziali non valide.";
        header("Location: index_form1.php"); // Redirect
        exit;
    }
}
}



?>
