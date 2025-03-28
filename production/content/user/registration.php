<?php
$pageTitle = 'Keller & Knilche Registrierung';
require_once('../../config/dbAccess.php');
require_once('../../includes/header.php');
require_once('../../includes/nav.php');


// Initialisierung der Variablen
$username = $email = $password = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $errors = [];

    // Eingaben pruefen
    if (empty($username)) {
        $errors[] = "Benutzername ist erforderlich.";
    } elseif (userExists($db, $username, $email)) {
        $errors[] = "Benutzername oder E-Mail ist bereits vergeben.";
    }

    if (empty($email)) {
        $errors[] = "E-Mail ist erforderlich.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ungültige E-Mail-Adresse.";
    }

    if (empty($password)) {
        $errors[] = "Passwort ist erforderlich.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Das Passwort muss mindestens 6 Zeichen lang sein.";
    }

    if (empty($confirmPassword)) {
        $errors[] = "Passwortbestätigung ist erforderlich.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwörter stimmen nicht überein.";
    }

    if (empty($errors)) {
        // Falls keine Fehler vorhanden sind, Benutzer registrieren
        $message = registerUser($db, $username, $email, $password);
    } else {
        // Fehler anzeigen
        echo "<div class='alert alert-danger'><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul></div>";
    }
}
?>
<div class="login-container">
    <h2>Registrierung</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername" value="<?php echo $username?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" value="<?php echo $email?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Passwort" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Passwort bestätigen</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Passwort bestätigen" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Registrieren</button>
        </div>
    </form>
    <p class="text-center mt-3">Schon registriert? <a href="login.php">Hier einloggen</a></p>
</div>
<?php require_once('../../includes/footer.php'); ?>
</body>
</html>
