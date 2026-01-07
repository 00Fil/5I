<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Accedi</h1>

    <?php 
        if(isset($_SESSION['error'])) {
            echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
        }
    ?>

    <form action="login.php" method="POST">
        <label>Username o Email:</label> <br>
        <input type="text" name="username_email" required><br><br>

        <label>Password</label>
        <input type="password" name="Password" id="passwd" required> <br><br>

        <p>Non hai un account?</p> <a href="register_form.php">Registrati</a>
        <button type="submit">Registrati</button>
    </form>
</body>
</html>