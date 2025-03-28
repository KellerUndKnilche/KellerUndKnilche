<?php
$pageTitle = 'Keller & Knilche Login';
require_once('../../config/dbAccess.php');
require_once('../../includes/header.php');
require_once('../../includes/nav.php');

$usernameErr = $passwordErr = $loginErr = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Benutzername ist erforderlich.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Passwort ist erforderlich.";
    } else {
        $password = $_POST["password"];
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $user = fetchUserByUsername($db, $username);
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['isLocked']) {
                $loginErr = "Ihr Konto ist gesperrt. Bitte wenden Sie sich an den Support.";
            } else {
                session_set_cookie_params([
                    'lifetime' => 2592000, // 30 Tage in Sekunden
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true, // Stellt sicher, dass das Cookie nicht über JavaScript zugänglich ist
                    'samesite' => 'Strict'
                ]);
                session_start();
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
                    setcookie("user_id", $user["id"], time() + 2592000, "/"); // 30 days
                    setcookie("username", $user["username"], time() + 2592000, "/");
                }

                // Update last login timestamp
                $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->bind_param("i", $user["id"]);
                $stmt->execute();

                header("Location: ../../index.php");
                exit();
            }
        } else {
            $loginErr = "Ungültiger Benutzername oder Passwort.";
        }
    }
}
?>
<div class="login-container">
    <h2>Login</h2>
    <form id="login-form" method="post" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername" required>
            <span class="text-danger"><?php echo htmlspecialchars($usernameErr); ?></span>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Passwort" required>
            <span class="text-danger"><?php echo htmlspecialchars($passwordErr); ?></span>
        </div>
        <div class="mb-3">
            <h5 class="form-label">Optionen</h5>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                <label class="form-check-label small" for="remember_me">Angemeldet bleiben</label>
            </div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Einloggen</button>
        </div>
        <p class="text-danger mt-3"><?php echo htmlspecialchars($loginErr); ?></p>
    </form>
    <p class="text-center mt-3">Noch keinen Account? <a href="registration.php">Jetzt registrieren</a></p>
</div>
<?php require_once('../../includes/footer.php'); ?>