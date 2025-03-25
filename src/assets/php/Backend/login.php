<?php
// Fehler- und Eingabevariablen initialisieren
$usernameErr = $passwortErr = $anmeldeErr = "";
$username = $passwort = "";

// Verbindung zur DB
require_once('dbAccess.php');
$db = new mysqli($host, $user, $password, $database);
if($db->connect_error)
{
    echo "Connection Error";
    exit();
}

$users = [];

$sql = "SELECT username, password_hash FROM user";
$result = $db->query($sql);

if ($result->num_rows > 0) 
{
    // Benutzerdaten in ein Array speichern
    while ($row = $result->fetch_assoc()) 
    {
        // Benutzername, Passwort, Status und Benutzerrechte speichern
        $users[] = [
            'username' => $row['username'],
            'password' => $row['password'],
        ];
    }
} 
else 
{
    echo "Keine Benutzer gefunden.";
}

if (!function_exists('reg_input')) 
{
    function reg_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

    // Validierung der Benutzereingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    if (empty($_POST["Username"])) 
    {
        $usernameErr = "Benutzername ist notwendig!";
    } 
    else 
    {
        $username = reg_input($_POST["Username"]);
    }

    if (empty($_POST["Passwort"]))
    {
        $passwortErr = "Passwort ist notwendig!";
    }
    else
    {
        $passwort = reg_input($_POST["Passwort"]);
    }

    foreach ($users as $user)
    {
        // Überprüfen, ob der eingegebene Benutzername übereinstimmt
        if ($user['username'] === $username)
        {
            // Passwort überprüfen
            if (password_verify($passwort, $user['password']))
            {
                //$_SESSION["loggedin"] = true; // Benutzer ist eingeloggt
                //$_SESSION["username"] = $username; // Benutzername speichern

                header("Location: index.html");
                exit();
            }
            else if (!empty($username) && !empty($passwort)) 
            {
                $anmeldeErr = "Benutzername und Passwort stimmen nicht überein!";
                break;
            }
        }
        else
        {
            $anmeldeErr = "Unbekannter Benutzername!";
        }
    }    
}
?>