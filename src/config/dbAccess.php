<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
    // Lade Datenbank-Zugangsdaten aus der .env-Datei
    $envPath = __DIR__ . '/../config/.env';
    if (!file_exists($envPath)) {
        die("Fehler: Die .env-Datei wurde nicht gefunden. Bitte stellen Sie sicher, dass sie im Projektverzeichnis vorhanden ist.");
    }

    $env = parse_ini_file($envPath);
    if ($env === false) {
        die("Fehler: Die .env-Datei konnte nicht gelesen werden. Bitte überprüfen Sie die Datei auf Fehler.");
    }

    $host = $env['DB_HOST'] ?? null;
    $user = $env['DB_USER'] ?? null;
    $password = $env['DB_PASSWORD'] ?? null;
    $database = $env['DB_NAME'] ?? null;

    if (!$host || !$user || !$password || !$database) {
        die("Fehler: Ungültige oder fehlende Datenbankkonfigurationswerte in der .env-Datei.");
    }

    // Stelle eine Verbindung zur Datenbank her
    $db = new mysqli($host, $user, $password, $database);
    if ($db->connect_error) {
        die("Verbindung fehlgeschlagen: " . $db->connect_error);
    }

    // Funktion, um alle Benutzer aus der Datenbank abzurufen
    function fetchAllUsers($db) {
        $sql = "SELECT * FROM users"; // SQL-Abfrage
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); // Rückgabe als assoziatives Array
    }

    // Funktion, um zu prüfen, ob ein Benutzername oder eine E-Mail bereits existiert
    function userExists($db, $username, $email) {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email); // Parameter binden
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Gibt true zurück, wenn ein Eintrag gefunden wurde
    }

    // Funktion, um einen neuen Benutzer in die Datenbank einzufügen
    function insertUser($db, $username, $email, $passwordHash) {
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash); // Parameter binden
        return $stmt->execute(); // Führt die Abfrage aus und gibt true/false zurück
    }

    // Funktion, um einen Benutzer anhand des Benutzernamens abzurufen
    function fetchUserByUsername($db, $username) {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Parameter binden
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); // Gibt den Benutzer als assoziatives Array zurück
    }

    // Funktion, um Benutzerstatistiken abzurufen
    function fetchUserStatistics($db) {
        $sql = "SELECT u.username, b.amount AS geld, COUNT(up.upgrade_id) AS upgrades 
                FROM users u
                LEFT JOIN beute_batzen b ON u.id = b.user_id
                LEFT JOIN user_upgrades up ON u.id = up.user_id
                GROUP BY u.id";
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Funktion, um einen neuen Benutzer zu registrieren
    function registerUser($db, $username, $email, $password) {
        if (userExists($db, $username, $email)) {
            return "Benutzername oder Email bereits vergeben.";
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if (insertUser($db, $username, $email, $passwordHash)) {
            return "Registrierung erfolgreich!";
        }
        return "Fehler bei der Registrierung.";
    }

    
    // Funktion, um Adminklasse abzurufen
    function fetchIsAdmin($db, $userId) {
        $stmt = $db->prepare("SELECT isAdmin FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['isAdmin'] ?? 0; // Gibt 1 zurück, wenn Admin, sonst 0
    }

    // Funktion, um Lockstatus/Ban abzurufen
    function fetchUserLocked($db, $userId) {
        $stmt = $db->prepare("SELECT isLocked FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['isLocked'] ?? 0; // Gibt 1 zurück, wenn gesperrt, sonst 0
    }

    // Prüft, ob ein anderer User denselben Benutzernamen hat
    function isUsernameTakenByOther($db, $username, $userId) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $userId);
        $stmt->execute();
        $stmt->store_result();
        $taken = $stmt->num_rows > 0;
        $stmt->close();
        return $taken;
    }

    // Prüft, ob eine andere E-Mail bereits vergeben ist
    function isEmailTakenByOther($db, $email, $userId) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $stmt->store_result();
        $taken = $stmt->num_rows > 0;
        $stmt->close();
        return $taken;
    }

    // Aktualisiert das Benutzerprofil (inkl. optionalem Passwort)
    function updateUserProfile($db, $userId, $username, $email, $password = null) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET username=?, password_hash=?, email=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $hashedPassword, $email, $userId);
        } else {
            $stmt = $db->prepare("UPDATE users SET username=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $email, $userId);
        }

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    // Funktion, um Benutzer zu sperren oder entsperren



    // Funktion, um Beute-Batzen eines Benutzers abzurufen
    function fetchUserCurrency($db, $userId) {
        $stmt = $db->prepare("SELECT amount FROM beute_batzen WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['amount'] ?? 0;
    }

    // Funktion, um Beute-Batzen eines Benutzers zu aktualisieren
    function updateUserCurrency($db, $userId, $amount) {
        $stmt = $db->prepare("INSERT INTO beute_batzen (user_id, amount) VALUES (?, ?) 
                              ON DUPLICATE KEY UPDATE amount = ?");
        $stmt->bind_param("iii", $userId, $amount, $amount);
        return $stmt->execute();
    }

    // Funktion, um Upgrades eines Benutzers abzurufen
    function fetchUserUpgrades($db, $userId) {
        $stmt = $db->prepare("SELECT upgrade_id, level FROM user_upgrades WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Funktion, um ein Upgrade-Level eines Benutzers zu aktualisieren
    function updateUserUpgrade($db, $userId, $upgradeId, $level) {
        $stmt = $db->prepare("INSERT INTO user_upgrades (user_id, upgrade_id, level) VALUES (?, ?, ?) 
                              ON DUPLICATE KEY UPDATE level = ?");
        $stmt->bind_param("isii", $userId, $upgradeId, $level, $level);
        return $stmt->execute();
    }



    // Game Funktionen

    function getUpgrades($db) {
        $sql = "SELECT * FROM upgrades";
        $result = $db->query($sql);
        $upgrades = [];
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $upgrades[] = $row;
            }
        }
    
        return $upgrades;
    }
?>