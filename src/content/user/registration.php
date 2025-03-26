<?php
$pageTitle = 'Keller & Knilche Registrierung';
require_once('../../config/dbAccess.php');

$message = "";
$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Eingaben pruefen
    if (empty($username)) {
        $usernameErr = "Benutzername ist erforderlich.";
    } elseif (userExists($db, $username, $email)) {
        $usernameErr = "Benutzername oder E-Mail ist bereits vergeben.";
    }

    if (empty($email)) {
        $emailErr = "E-Mail ist erforderlich.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Ungültige E-Mail-Adresse.";
    }

    if (empty($password)) {
        $passwordErr = "Passwort ist erforderlich.";
    } elseif (strlen($password) < 6) {
        $passwordErr = "Das Passwort muss mindestens 6 Zeichen lang sein.";
    }

    if (empty($confirmPassword)) {
        $confirmPasswordErr = "Passwortbestätigung ist erforderlich.";
    } elseif ($password !== $confirmPassword) {
        $confirmPasswordErr = "Passwörter stimmen nicht überein.";
    }

    // Falls keine Fehler vorhanden sind, Benutzer registrieren
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        $message = registerUser($db, $username, $email, $password);
    }
}
?>
<?php require_once('../../includes/header.php'); ?>
<?php require_once('../../includes/nav.php'); ?>
<div class="login-container">
    <h2>Registrierung</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername" required>
            <span class="text-danger"><?php echo htmlspecialchars($usernameErr); ?></span>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" required>
            <span class="text-danger"><?php echo htmlspecialchars($emailErr); ?></span>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Passwort" required>
            <span class="text-danger"><?php echo htmlspecialchars($passwordErr); ?></span>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Passwort bestätigen</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Passwort bestätigen" required>
            <span class="text-danger"><?php echo htmlspecialchars($confirmPasswordErr); ?></span>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Registrieren</button>
        </div>
        <p class="text-danger mt-3"><?php echo htmlspecialchars($message); ?></p>
    </form>
    <p class="text-center mt-3">Schon registriert? <a href="login.php">Hier einloggen</a></p>
</div>
<?php require_once('../../includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
