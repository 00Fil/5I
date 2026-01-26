<html>
	<head>
	</head>
	<body>
			<h1>Login</h1>
			<?php
				session_start();
				if(isset($_SESSION['error'])){
					echo "<p style='color:red'>".$_SESSION['error']."</p>";
					unset($_SESSION['error']);
				}
			
			?>
			<form action="login.php" method="POST">
			<input type="text" name="username" placeholder="Username o email" required>
			<input type="password" name="password" placeholder="password" required>
			<button type="submit">Accedi</button>
			</form>
			
			<p>Non sei registrato? registrati! <a href="register_form1.php">Registrati</a>
			</p>
			
			
	</body>
</html>
<!-- 
Mostra form con username/email e password
Mostra eventuale errore memorizzato in sessione
+-------------------------+
| [Errore se presente]    |
| Username/Email [______] |
| Password       [______] |
| [Accedi]               |
| [Link Registrazione]   |
+-------------------------+
-->