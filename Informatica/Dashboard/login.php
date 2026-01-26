<?php
session_start();
require_once('connessione.php');
//MANCA controllo se i dati sono stati inviati tramite POST
$input    = $_POST['username'];   // username o email
$password = $_POST['password'];

try {

    // 1) Recupero utente
    $sql = "SELECT * FROM users WHERE username = :input OR email = :input";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':input', $input);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Utente inesistente → accesso sempre negato
    if (!$user) {
        $_SESSION['error'] = "Credenziali non valide.";
        header("Location: index_form1.php");
        exit;
    }

    /* =====================================================
       2) CONTROLLO BLOCCO TEMPORANEO
       ===================================================== 
	   Se il campo bloccato_fino contiene una data futura significa che l’account è temporaneamente congelato.
       Confrontiamo:
       - time() con data/ora attuale
       - bloccato_fino con data/ora di sblocco
	     
	   */

    if ($user['bloccato_fino'] !== null && strtotime($user['bloccato_fino']) > time()) {
        
        $_SESSION['error'] = "Credenziali non valide.";
        header("Location: index_form1.php");
        exit;
    }

    /* =====================================================
       3) VERIFICA PASSWORD
       ===================================================== */

    if (password_verify($password, $user['pasword'])) {

        /* =====================================================
           4) RESET SICUREZZA (LOGIN RIUSCITO)
           ===================================================== 
		   Login corretto allora il sistema reset dei tentativi:
           - azzera il contatore tentativi
           - rimuove qualsiasi blocco temporaneo
        */
		  // Reset dei tentativi se il login è corretto
		$sql = "UPDATE users SET tentativi = 0, bloccato_fino = NULL WHERE id_user = :id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $user['id_user']);
		$stmt->execute();
        

        // Salva i dati dell'utente nella sessione 
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];

		//accesso consentito all'area privata
        header("Location: dashboard.php");
        exit;

    } else {

        /* =====================================================
           5) PASSWORD ERRATA → AUMENTO TENTATIVI
           ===================================================== */

        $tentativi = $user['tentativi'] + 1;

        if ($tentativi >= 5) {

            /* =====================================================
               6) BLOCCO ACCOUNT (ANTI BRUTE FORCE)
               ===================================================== 
			  Se i tentativi superano la soglia:
               - salva il nuovo numero di tentativi
               - congela l’account per 10 minuti
            */
			  // Blocco dell'account dopo troppi tentativi
			$sql = "UPDATE users SET tentativi = :t, bloccato_fino = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
			WHERE id_user = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':t', $tentativi);
			$stmt->bindParam(':id', $user['id_user']);
			$stmt->execute(); 
			   
        } else {

            // Solo incremento tentativi
            $up = $conn->prepare("
                UPDATE users 
                SET tentativi = :t 
                WHERE id_user = :id
            ");
            $up->bindParam(':t', $tentativi);
            $up->bindParam(':id', $user['id_user']);
            $up->execute();
        }

        $_SESSION['error'] = "Credenziali non valide.";
        header("Location: index_form1.php");
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Errore di sistema.";
    header("Location: index_form1.php");
    exit;
}
/*
Riceve dati da index_form.php
Controlla utente esiste
Controlla password con password_verify()
Blocca utente se tentativi >= 5 
Aggiorna tentativi:
Password corretta → reset tentativi
Password errata → incrementa tentativi
Salva info in sessione: user_id, username
Redirect su dashboard.php o ritorno al form con errore
*/