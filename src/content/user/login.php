<?php
require_once('../../config/dbAccess.php');
require_once('../../includes/helpers.php');

// Prüfen, ob der Benutzer bereits eingeloggt ist
if (isset($_SESSION["user"])) {
    header("Location: " . getBaseUrl() . "/content/user/profile.php");
    exit();
}

$pageTitle = 'Keller & Knilche Login';

$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    
    if (empty($_POST["username"])) {
        $errors[] = "Benutzername ist erforderlich.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty($_POST["password"])) {
        $errors[] = "Passwort ist erforderlich.";
    } else {
        $password = $_POST["password"];
    }
    
    if (empty($errors)) {
        $user = fetchUserByUsername($db, $username);
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['isLocked']) {
                $errors[] = "Ihr Konto ist gesperrt. Bitte wenden Sie sich an den Support.";
            } else {
                session_set_cookie_params([
                    'lifetime' => 2592000, // 30 Tage in Sekunden
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true, // Stellt sicher, dass das Cookie nicht über JavaScript zugänglich ist
                    'samesite' => 'Strict'
                ]);
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                // User Information in Session speichern
                $_SESSION["user"] = [
                    "id" => $user["id"],
                    "username" => $user["username"],
                    "email" => $user["email"],
                    "isAdmin" => $user["isAdmin"],
                    "isLocked" => $user["isLocked"],
                    "last_login" => $user["last_login"]
                ];
                
                // Cookie setzen falls remember_me geklickt wurde
                if (!empty($_POST["remember_me"])) {
                    setcookie("user_id", $user["id"], time() + 2592000, "/"); // 30 Tage in Sekunden
                    setcookie("username", $user["username"], time() + 2592000, "/");
                }
                
                // Update last login timestamp
                $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->bind_param("i", $user["id"]);
                $stmt->execute();
                
                header("Location: " . getBaseUrl() . "/index.php");
                exit();
            }
        } else {
            $errors[] = "Ungültiger Benutzername oder Passwort.";
        }
    }
    // Fehler anzeigen
    echo "<div class='error' style='display: block;'><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
}
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>
<div class="loginRegister-container">
    <h2>Login</h2>
    <form id="login-form" method="post" action="">
        <div> <label for="username">Benutzername</label> <input type="text" id="username" name="username" placeholder="Benutzername" value="<?php echo $username ?? ''; ?>" required> </div>

        <div> <label for="password">Passwort</label> <input type="password" id="password" name="password" placeholder="Passwort" required> </div>

        <div class="remember-me"> <input type="checkbox" id="remember_me" name="remember_me"> <label for="remember_me">Angemeldet bleiben</label> </div>

        <div> <button type="submit">Einloggen</button> </div>
    </form>

    <p>Noch keinen Account? <a href="registration.php">Jetzt registrieren</a></p> </div>
</div>
<?php require_once('../../includes/footer.php'); ?>