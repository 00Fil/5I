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

if($user['bloccato_fino'] !== null && strtotime($user['bloccato_fino']) > time()); {
    $_SESSION['error'] = "Credenziali non valide.";
    header("Location: index_form1.php");
    exit;
}

if (password_verify($passwd,$user['password'])) {
    $sql = "UPDATE users SET tentativi = 0, bloccato_fino = null WHERE id_user=:id";
    $stmt->bindParam(':id', $user['id_user']);
    $stmt->execute();

$_SESSION['user_id'] = $user['id_user'];
$_SESSION['username'] = $user['username'];

header("Location: dashboard.php");
exit;

} else {

$tentativi = $user['tentativi'] + 1;

    if($tentativi>=5) {
        $sql = "UPDATE users SET tentativi = :t, blocca_fino = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id_user = :id";        
    } else {
        $up = $conn->prepare("UPDATE users SET tentativi = :t WHERE id_user =:id");
        $up->bindParam(':t', $tentativi);
        $up->bindParam(':id', $user['id_user']);
        $up->execute();
    }
} 

}
?>
