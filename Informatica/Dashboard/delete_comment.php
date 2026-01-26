<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Elimina commento</title>
<link rel="stylesheet" href="style_private.css">
</head>
<body>

<?php
require_once("connessione.php");
require_once("header_private.php");

$stmt = $conn->prepare("SELECT * FROM comments WHERE id_user=:user");
$stmt->bindParam(':user', $_SESSION['user_id']);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
<h2>Elimina commento</h2>

<?php foreach($comments as $c): ?>
<?= htmlspecialchars($c['content']) ?> <a href="?id=<?= $c['id_comm'] ?>">X</a><br>
<?php endforeach; ?>
</div>

<?php
if(isset($_GET['id'])){
    $stmt = $conn->prepare("DELETE FROM comments WHERE id_comm=:id AND id_user=:user");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':user', $_SESSION['user_id']);
    $stmt->execute();

    header("Location: delete_comment.php");
    exit;
}
?>

</body>
</html>