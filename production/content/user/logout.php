<?php
session_start();

// Session zerstören
session_unset();
session_destroy();

// Session-Cookie löschen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Custom-Cookies löschen
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/'); // Cookie ablaufen lassen
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Cookie ablaufen lassen
}

// Zurück zur Startseite
header("Location: /index.php");
exit();
?>
