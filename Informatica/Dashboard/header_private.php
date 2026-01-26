<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index_form1.php");
    exit;
}
?>
<nav>
    <a href="dashboard.php">Home</a>
    <a href="add_comment.php">Aggiungi</a>
    <a href="edit_comment.php">Modifica</a>
    <a href="delete_comment.php">Elimina</a>
    <a href="logout.php">Logout</a>
</nav>