<?php
require_once('connessione.php');
session_start();

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email non valida.";
    header('Location: register_form1.php');
    exit;
}

if (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[\W]/', $password)
) {
    $_SESSION['error'] = "Password non valida.";
    header('Location: register_form1.php');
    exit;
}

try {
    $query = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Username o email già in uso.";
        header('Location: register_form1.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, email, pasword)
              VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    header('Location: index_form1.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Errore durante la registrazione.";
    header('Location: register_form1.php');
    exit;
}
/*
Riceve dati da register_form1.php
Validazione:
Email valida
Password forte (lunghezza + maiuscola + minuscola + numero + simbolo)
Username/email unici
Cripta password con password_hash()
Inserisce utente nel DB
Redirect al login
*/