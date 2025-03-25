<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            <input type="text" id="username" name="username" placeholder="Benutzername" required>
            <input type="password" id="password" name="password" placeholder="Passwort" required>
            <button type="submit">Einloggen</button>
            <p class="error" id="errorMessage">Bitte füllen Sie alle Felder aus.</p>
        </form>
        <!-- Link für Registrierung -->
        <p>Noch keinen Account? <a href="registration.php">Jetzt registrieren</a></p>
    </div>
    <script src="./assets/js/script.js"></script>
</body>
</html>