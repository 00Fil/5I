<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style_private.css">
</head>
<body>

<?php require_once("header_private.php"); ?>

<div class="container">
    <h2>Benvenuto <?=htmlspecialchars($_SESSION['username'])?></h2>
    <p>Area privata</p>
</div>

</body>
</html>