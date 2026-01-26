<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Aggiungi commento</title>
<link rel="stylesheet" href="style_private.css">
</head>
<body>

<?php
require_once("connessione.php");
require_once("header_private.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO comments (id_user, content) VALUES (:user, :content)");
    $stmt->bindParam(':user', $_SESSION['user_id']);
    $stmt->bindParam(':content', $content);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<div class="container">
<h2>Aggiungi commento</h2>
<form method="POST">
    <textarea name="content" required></textarea><br><br>
    <button type="submit">Salva</button>
</form>
</div>

</body>
</html>