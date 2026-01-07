Schema login/registrazione/dashboard
1 Database (users e comments)
CREATE TABLE users (
id_user INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
email VARCHAR(100) NOT NULL UNIQUE,
pasword VARCHAR(255) NOT NULL,
tentativi INT DEFAULT 0, -- per blocco tentativi
bloccato_fino DATETIME DEFAULT NULL -- per blocco temporaneo
);
CREATE TABLE comments (
id_comm INT AUTO_INCREMENT PRIMARY KEY,
id_user INT NOT NULL,
content TEXT NOT NULL,
FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

2 Connessione PHP (connessione.php)
&lt;?php
$hostname = &quot;localhost&quot;;
$dbname = &quot;mensajes&quot;;
$user = &quot;root&quot;;
$pass = &quot;&quot;;
try {
$conn = new PDO(&quot;mysql:host=$hostname;dbname=$dbname;&quot;, $user, $pass);
$conn-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die(&quot;Errore: &quot; . $e-&gt;getMessage());
}
?&gt;
3 Pagina HTML / Form
a) Login form (index_form.php)

● Mostra form con username/email e password
● Mostra eventuale errore memorizzato in sessione

+-------------------------+
| [Errore se presente] |
| Username/Email [______] |
| Password [______] |
| [Accedi] |
| [Link Registrazione] |
+-------------------------+

b) Registrazione form (register_form.php)
● Mostra form con username, email e password
● Mostra eventuale errore memorizzato in sessione

4 Logica PHP
a) Login (login.php)
● Riceve dati da index_form.php
● Controlla utente esiste
● Controlla password con password_verify()
● Blocca utente se tentativi &gt;= 5 (opzionale)
● Aggiorna tentativi:
○ Password corretta → reset tentativi
○ Password errata → incrementa tentativi
● Salva info in sessione: user_id, username
● Redirect su dashboard.php o ritorno al form con errore

b) Registrazione (register.php)
● Riceve dati da register_form.php
● Validazione:
○ Email valida
○ Password forte (lunghezza + maiuscola + minuscola + numero + simbolo)
○ Username/email unici
● Cripta password con password_hash()
● Inserisce utente nel DB
● Redirect al login

c) Dashboard (dashboard.php)
● Controlla sessione attiva
● Mostra menu di navigazione per commenti e logout
● Mostra benvenuto + username

d) Logout (logout.php)
● session_start()
● session_unset() + session_destroy()
● Redirect al login

5 Flusso utente
Utente non loggato
|

v
[index_form.php] -- login --&gt; [login.php]
| |
| v
| credenziali OK? ---&gt; [dashboard.php]
| |
| NO
| |
|&lt;---- ritorno a form con errore
|
[register_form.php] -- registra --&gt; [register.php] --&gt; login_form

Array $_SESSION
● $_SESSION è un array associativo speciale di PHP.
● Serve a memorizzare dati tra le pagine per un singolo utente mentre la sessione è
attiva.

✅ Struttura
$_SESSION[&#39;chiave&#39;] = valore;

● chiave → nome identificativo della variabile che vuoi salvare (stringa)
● valore → qualsiasi dato PHP valido (stringhe, numeri, array…)

�� Esempio concreto nel login
$_SESSION[&#39;user_id&#39;] = $user[&#39;id_user&#39;]; // chiave: &#39;user_id&#39;, valore: id numerico utente
$_SESSION[&#39;username&#39;] = $user[&#39;username&#39;]; // chiave: &#39;username&#39;, valore: stringa
username

● Dopo questo, qualsiasi pagina che fa session_start() può leggere:

echo $_SESSION[&#39;username&#39;]; // stampa il nome dell’utente loggato

�� Not
L’array vive finché la sessione è attiva (chiudi il browser o fai
session_destroy() per cancellarlo)

● Può contenere qualsiasi dato che serve tra pagine, ma non dati sensibili in
chiaro se non criptati o hashati quando serve

Schema $_SESSION
+------------------------+
| $_SESSION | ← array associativo speciale
+------------------------+
| chiave | valore |
|------------------------|
| &#39;user_id&#39; | 17 | ← id numerico dell&#39;utente loggato
| &#39;username&#39; | &quot;Mario&quot; | ← nome utente loggato
| &#39;error&#39; | &quot;Credenziali non valide&quot; | ← messaggi di errore tra
pagine
+------------------------+

�� Come funziona
1. Salvare dati:

$_SESSION[&#39;user_id&#39;] = $user[&#39;id_user&#39;];
$_SESSION[&#39;username&#39;] = $user[&#39;username&#39;];

2. Leggere dati:

echo $_SESSION[&#39;username&#39;]; // stampa &quot;Mario&quot;

3. Cancellare dati:

unset($_SESSION[&#39;error&#39;]); // rimuove solo la chiave &#39;error&#39;
session_unset(); // cancella tutte le chiavi
session_destroy(); // termina la sessione

Concetti chiave
● $_SESSION è come un cassetto personale per ogni utente.
● La chiave è il nome dell’oggetto che vuoi salvare.
● Il valore è il contenuto che vuoi ricordare tra le pagine.
● Serve per:
○ riconoscere l’utente loggato
○ passare messaggi di errore
○ conservare temporaneamente altre info durante la sessione

Flusso utente + $_SESSION
[ index_form.php ] ← Form login
|
| POST username + password
v
[ login.php ] ← Logica PHP
|
| 1) Controllo username/email
| 2) Verifica password con password_verify()
| 3) Aggiorna $_SESSION se OK:
| $_SESSION[&#39;user_id&#39;] = id_user
| $_SESSION[&#39;username&#39;] = username
| (oppure $_SESSION[&#39;error&#39;] se login fallito)
v
+--------------------------+
| |
| credenziali valide? |
| |
YES NO
| |
v v
[ dashboard.php ] [ index_form.php ]
Legge $_SESSION[&#39;username&#39;] Mostra $_SESSION[&#39;error&#39;]
Mostra menu + contenuti

�� Altri casi
● Logout (logout.php)

[ dashboard.php ] --clicca logout--&gt; [ logout.php ]
|
v
session_unset() + session_destroy()
|
v
[ index_form.php ] ← $_SESSION ora vuota

● Registrazione

[ register_form.php ] ← Form registrazione

|
| POST username, email, password
v
[ register.php ] ← Logica PHP
|
| Validazione + password_hash()
| Inserimento nel DB
v
[ index_form.php ] ← pronto per login