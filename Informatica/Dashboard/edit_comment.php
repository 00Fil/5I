<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Modifica commento</title>
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

if(!isset($_GET['id']) && !isset($_POST['id'])){
    echo "<div class='container'><h2>Scegli commento da modificare</h2>";
    foreach($comments as $c){
        echo "<a href='edit_comment.php?id={$c['id_comm']}'>".htmlspecialchars($c['content'])."</a><br>";
    }
    echo "</div>";
    exit;
}

if(isset($_GET['id'])){
    $stmt = $conn->prepare("SELECT * FROM comments WHERE id_comm=:id AND id_user=:user");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':user', $_SESSION['user_id']);
    $stmt->execute();
    $c = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php if(isset($c)): ?>
<div class="container">
<form method="POST">
    <textarea name="content"><?= htmlspecialchars($c['content']) ?></textarea>
    <input type="hidden" name="id" value="<?= $c['id_comm'] ?>">
    <button type="submit">Aggiorna</button>
</form>
</div>
<?php endif; ?>

<?php
if(isset($_POST['id'])){
    $stmt = $conn->prepare("UPDATE comments SET content=:content WHERE id_comm=:id AND id_user=:user");
    $stmt->bindParam(':content', $_POST['content']);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':user', $_SESSION['user_id']);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

</body>
</html>