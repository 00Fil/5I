<html>
	<head>
	</head>
	<body>
			<h1>Registrazione</h1>
			<?php
				session_start();
				if(isset($_SESSION['error'])){
					echo "<p style='color:red'>".$_SESSION['error']."</p>";
					unset($_SESSION['error']);
				}
			
			?>
			<form action="register.php" method="POST">
			Username
			<input type="text" name="username" placeholder="Username o email" required>
			<br><br>
			Password
			<input type="password" name="password" placeholder="password" required>
			<br><br>
			Email
			<input type="email" name="email" placeholder="Email" required>
			<button type="submit">Registrati</button>
			</form>
			
			<p>Non sei registrato? registrati! <a href="register.php">Registrati</a>
			</p>
			
			
	</body>
</html>
<!--
Mostra form con username, email e password
Mostra eventuale errore memorizzato in sessione
-->