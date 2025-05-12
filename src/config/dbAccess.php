<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Helper-Funktionen laden !!! nicht verändern
    require_once (__DIR__ . '/../includes/helpers.php');
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

    // Falls User eingeloggt, aber Terms nicht akzeptiert, dann weiterleiten
    if (isset($_SESSION['user']) && !userAcceptedTerms($db, $_SESSION['user']['id']) && $_SERVER['REQUEST_URI'] != '/akzeptieren' && $_SERVER['REQUEST_URI'] != '/logout') {
        header("Location: " . getBaseUrl() . "/akzeptieren");
        exit();
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
        $stmt = $db->prepare(
            "INSERT INTO users (username, email, password_hash, acceptedTerms) VALUES (?, ?, ?, 1)" //acceptedTerms wird direkt gesetzt, da bei Registrierung geprüft
        );
        $stmt->bind_param("sss", $username, $email, $passwordHash); // Parameter binden
        $success = $stmt->execute(); // Führt die Abfrage aus und gibt true/false zurück
        initUserUpgrades($db, $username); // Upgrades initialisieren
        return $success; // Gibt true zurück, wenn erfolgreich
    }

    // Funktion, um Upgrades für einen neuen Benutzer zu initialisieren
    function initUserUpgrades($db, $username) {
        // Hole die user_id anhand des Benutzernamens
        $userStmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $userStmt->bind_param("s", $username);
        $userStmt->execute();
        $result = $userStmt->get_result();
    
        if ($result->num_rows === 0) {
            // Benutzer existiert nicht
            return false;
        }
    
        $userId = $result->fetch_assoc()['id'];
    
        // Hole alle Upgrade-IDs
        $upgradeQuery = $db->query("SELECT id FROM upgrades");
    
        $stmt = $db->prepare("INSERT INTO user_upgrades (user_id, upgrade_id, level) VALUES (?, ?, 0)");
        foreach ($upgradeQuery as $upgrade) {
            $stmt->bind_param("ii", $userId, $upgrade['id']);
            $stmt->execute();
        }
    
        return true;
    }

    // Funktion, um einen Benutzer anhand des Benutzernamens abzurufen
    function fetchUserByUsername($db, $username) {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Parameter binden
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); // Gibt den Benutzer als assoziatives Array zurück
    }

    // Funktion, um zu prüfen ob User Nutzungsbedingungen akzeptiert hat
    function userAcceptedTerms($db, $userId) {
        $stmt = $db->prepare("SELECT acceptedTerms FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['acceptedTerms'] ?? 0; // Gibt 1 zurück, wenn akzeptiert, sonst 0
    }

    function setAcceptedTerms($db, $userId) {
        $stmt = $db->prepare("UPDATE users SET acceptedTerms = 1 WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Funktion, um Benutzerstatistiken abzurufen
    function fetchUserStatistics($db) {
        $sql = "SELECT u.username, b.amount AS geld, COALESCE(SUM(up.level), 0) AS upgrades
                FROM users u
                LEFT JOIN beute_batzen b ON u.id = b.user_id
                LEFT JOIN user_upgrades up ON u.id = up.user_id AND up.level > 0
                WHERE u.isLocked = 0 AND b.amount > 0 OR up.level > 0
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
        $value = $stmt->get_result()->fetch_assoc()['amount'] ?? 0;
        return round((float)$value, 2);
    }

    // NEUE FUNKTION: Speichert oder aktualisiert den Währungsstand eines Benutzers
    function saveUserCurrency($db, $userId, $amount) {
        // Stelle sicher, dass der Betrag numerisch ist
        if (!is_numeric($amount)) {
            error_log("saveUserCurrency: Ungültiger Betrag '$amount' für User ID $userId.");
            return false;
        }
        // Konvertiere zu float, um Kompatibilität mit DB zu gewährleisten
        $amountFloat = round(floatval($amount), 2); // auf 2 Dezimalstellen runden

        $stmt = $db->prepare("
            INSERT INTO beute_batzen (user_id, amount)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE amount = ?
        ");
        if (!$stmt) {
            error_log("saveUserCurrency: Prepare failed: " . $db->error);
            return false;
        }
        // 'd' für double/float verwenden
        $stmt->bind_param("idd", $userId, $amountFloat, $amountFloat);
        $success = $stmt->execute();
        if (!$success) {
            error_log("saveUserCurrency: Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $success;
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

    // Funktion, um alle Upgrades eines Benutzers mit Level abzurufen
    function getUserUpgrades($db, $userId) {
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.basispreis,
                u.effektart,
                u.effektwert,
                u.kategorie,
                u.ziel_id,
                t.name AS ziel_name,
                t.typ AS target_typ,
                COALESCE(uu.level, 0) AS level
            FROM upgrades u
            JOIN targets t ON u.ziel_id = t.id
            LEFT JOIN user_upgrades uu ON u.id = uu.upgrade_id AND uu.user_id = ?
            ORDER BY u.id ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $upgrades = [];

        while ($row = $result->fetch_assoc()) {
            $upgrades[] = $row;
        }

        return $upgrades;
    }

    // Funktion, um die Levels eines Upgrades zu speichern
    function saveUserUpgrades($db, $userId, $upgrades) {
        // Insert oder Update, falls Zeile schon existiert
        $stmt = $db->prepare("
            INSERT INTO user_upgrades (user_id, upgrade_id, level)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE level = VALUES(level)
        ");
        foreach ($upgrades as $upgrade) {
            $stmt->bind_param("iii", $userId, $upgrade['id'], $upgrade['level']);
            $stmt->execute();
        }
        $stmt->close();
        return true;
    }

    /**
     * Berechnet die aktuelle Einkommensrate pro Sekunde für einen Benutzer
     * und aktualisiert dessen Währungsguthaben in der Tabelle beute_batzen.
     * Annahme: Diese Funktion wird ca. jede Sekunde aufgerufen.
     *
     * @param mysqli $db Die Datenbankverbindung.
     * @param int $userId Die ID des Benutzers.
     * @return float|false Die aktualisierte Produktionsrate pro Sekunde bei Erfolg, oder false bei Fehler.
     */
    function updateUserCurrency($db, $userId) {
        if (!$db || $userId <= 0) {
            error_log("updateUserCurrency: Ungültige Parameter (userId: $userId).");
            return false;
        }

        // 1. Berechne die aktuelle Produktion pro Sekunde
        $productionPerSecond = 0;
        // Diese Abfrage holt alle relevanten Produktions- und Boost-Upgrades für den Benutzer
        // und die Basis-Produktionswerte der entsprechenden Ziele.
        $stmtProduction = $db->prepare("
            SELECT
                u.level,                                -- Level des gekauften Upgrades
                up.effektwert AS upgrade_effektwert,    -- Effektwert des gekauften Upgrades
                up.effektart AS upgrade_effektart,      -- Effektart des gekauften Upgrades ('prozent', 'absolut')
                up.kategorie AS upgrade_kategorie,      -- Kategorie des gekauften Upgrades ('Produktion', 'Boost', 'Klick')
                up.ziel_id,                             -- ID des Ziels, das beeinflusst wird
                t_prod.effektwert AS base_production    -- Basisproduktion/Sek des Ziels (aus dem 'Produktion' Upgrade für dieses Ziel)
            FROM user_upgrades u
            JOIN upgrades up ON u.upgrade_id = up.id
            -- Finde das zugehörige Basis-Produktionsupgrade für das Ziel
            LEFT JOIN upgrades t_prod ON up.ziel_id = t_prod.ziel_id AND t_prod.kategorie = 'Produktion'
            WHERE u.user_id = ? AND (up.kategorie = 'Produktion' OR up.kategorie = 'Boost')
            ORDER BY up.ziel_id, up.kategorie -- Wichtig für korrekte Berechnung
        ");

        if (!$stmtProduction) {
            error_log("updateUserCurrency: Prepare failed (stmtProduction): " . $db->error);
            return false;
        }

        $stmtProduction->bind_param("i", $userId);
        $stmtProduction->execute();
        $resultProduction = $stmtProduction->get_result();

        $productionTargets = []; // Speichert die Produktion pro Ziel-ID { ziel_id => base_production * level }
        $boosts = [];          // Speichert Boosts pro Ziel-ID { ziel_id => [ {level, effektart, effektwert}, ... ] }

        while ($upgrade = $resultProduction->fetch_assoc()) {
            $zielId = $upgrade['ziel_id'];

            if ($upgrade['upgrade_kategorie'] === 'Produktion') {
                // Dies ist das Upgrade, das die Einheit selbst repräsentiert (z.B. "Gerippe")
                // Die Basisproduktion kommt aus t_prod.effektwert
                // Die Gesamtproduktion dieses Targets ist Basisproduktion * Level
                if (!isset($productionTargets[$zielId])) {
                     $productionTargets[$zielId] = 0;
                }
                // Korrektur: Nicht addieren, sondern setzen, da wir alle user_upgrades holen.
                $productionTargets[$zielId] = ($upgrade['base_production'] ?? 0) * $upgrade['level'];

            } elseif ($upgrade['upgrade_kategorie'] === 'Boost') {
                // Dies ist ein Boost-Upgrade (z.B. "Knochentraining")
                if (!isset($boosts[$zielId])) {
                    $boosts[$zielId] = [];
                }
                $boosts[$zielId][] = [
                    'level' => $upgrade['level'],
                    'effektart' => $upgrade['upgrade_effektart'],
                    'effektwert' => $upgrade['upgrade_effektwert'] // Korrigierte Zeile
                ];
            }
        }
        $stmtProduction->close();

        // Wende Boosts auf die Produktion an
        $totalProductionPerSecond = 0;
        foreach ($productionTargets as $targetId => $baseProductionForTarget) {
            $currentProduction = $baseProductionForTarget; // Start mit Basisproduktion * Level

            if (isset($boosts[$targetId])) {
                foreach ($boosts[$targetId] as $boost) {
                     // Annahme: Jeder Boost-Level gibt den vollen Effektwert.
                     // Beispiel: Knochentraining Lvl 2 gibt 2 * 15% = 30% Boost
                     $boostMultiplierTotal = $boost['effektwert'] * $boost['level'];

                    if ($boost['effektart'] === 'prozent') {
                        // Erhöhe die *aktuelle* Produktion dieses Targets um den Prozentsatz des Boosts
                        $currentProduction *= (1 + ($boostMultiplierTotal / 100.0));
                    }
                    // 'absolut' Boosts auf Produktion könnten hier addiert werden, falls implementiert
                }
            }
            $totalProductionPerSecond += $currentProduction; // Addiere die geboostete Produktion des Targets zur Gesamtsumme
        }

        // 2. Aktualisiere beute_batzen mit der Produktion der letzten Sekunde
        // Wir gehen davon aus, dass diese Funktion ca. jede Sekunde aufgerufen wird.
        $amountToAdd = round($totalProductionPerSecond, 2); // auf 2 Dezimalstellen runden

        if ($amountToAdd > 0) {
            // Verwende INSERT ... ON DUPLICATE KEY UPDATE für Effizienz
            $stmtUpdate = $db->prepare("
                INSERT INTO beute_batzen (user_id, amount)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE amount = amount + ?
            ");
            if (!$stmtUpdate) {
                error_log("updateUserCurrency: Prepare failed (stmtUpdate): " . $db->error);
                return false;
            }
            // amount ist BIGINT, daher 'd' für double verwenden, um Genauigkeit zu gewährleisten
            $stmtUpdate->bind_param("idd", $userId, $amountToAdd, $amountToAdd);
            $success = $stmtUpdate->execute();
            $stmtUpdate->close();

            if (!$success) {
                error_log("updateUserCurrency: Execute failed (stmtUpdate): " . $stmtUpdate->error);
                return false;
            }
        } else {
            // Keine Produktion, nichts zu addieren. Kein Fehler.
        }
         // Gib die berechnete Rate zurück, damit das Frontend sie anzeigen kann
        return $totalProductionPerSecond;
    }

    // Funktion, um den aktuellen Währungsstand eines Benutzers abzurufen
    function getCurrentCurrency($db, $userId) {
        if (!$db || $userId <= 0) {
            return 0; // Oder null oder false, je nach Fehlerbehandlung
        }
        $stmt = $db->prepare("SELECT amount FROM beute_batzen WHERE user_id = ?");
        if (!$stmt) {
            error_log("getCurrentCurrency: Prepare failed: " . $db->error);
            return 0;
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return (float)$row['amount']; // Stelle sicher, dass es eine Zahl ist
        }
        $stmt->close();
        return 0; // Benutzer hat noch keinen Eintrag oder 0
    }

    /**
     * Berechnet den aktuellen Wert eines Klicks für einen Benutzer basierend auf seinen Klick-Upgrades.
     *
     * @param mysqli $db Die Datenbankverbindung.
     * @param int $userId Die ID des Benutzers.
     * @return float Der berechnete Wert pro Klick.
     */
    function calculateUserClickValue($db, $userId) {
        $baseClickValue = 1.0; // Grundwert pro Klick
        $totalClickValue = $baseClickValue;

        // Hole alle aktiven Klick-Upgrades des Benutzers
        $stmt = $db->prepare("
            SELECT up.effektwert, uu.level
            FROM user_upgrades uu
            JOIN upgrades up ON uu.upgrade_id = up.id
            WHERE uu.user_id = ? AND up.kategorie = 'Klick' AND uu.level > 0
        ");

        if (!$stmt) {
            error_log("calculateUserClickValue: Prepare failed: " . $db->error);
            return $baseClickValue; // Im Fehlerfall nur den Basiswert zurückgeben
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($upgrade = $result->fetch_assoc()) {
            // Annahme: Jeder Level des Upgrades addiert den Effektwert zum Klickwert hinzu.
            // Beispiel: Upgrade mit effektwert 0.5, Level 3 -> addiert 3 * 0.5 = 1.5 zum Klickwert.
            $totalClickValue += (float)$upgrade['effektwert'] * (int)$upgrade['level'];
        }
        $stmt->close();

        return $totalClickValue;
    }

    /**
     * Berechnet die Kosten für das nächste Level eines Upgrades.
     * (PHP-Version von kalkPreis aus script.js)
     *
     * @param mysqli $db Die Datenbankverbindung.
     * @param int $upgradeId Die ID des Upgrades.
     * @param int $currentLevel Das aktuelle Level des Upgrades.
     * @return float|false Die Kosten für das nächste Level oder false bei Fehler.
     */
    function calculateUpgradeCostPHP($db, $upgradeId, $currentLevel) {
        $stmt = $db->prepare("SELECT basispreis FROM upgrades WHERE id = ?");
        if (!$stmt) {
            error_log("calculateUpgradeCostPHP: Prepare failed: " . $db->error);
            return false;
        }
        $stmt->bind_param("i", $upgradeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $basispreis = (float)$row['basispreis'];
            $stmt->close();
            // Preisformel: basispreis * 1,15^level
            return $basispreis * pow(1.15, $currentLevel);
        } else {
            $stmt->close();
            error_log("calculateUpgradeCostPHP: Upgrade mit ID $upgradeId nicht gefunden.");
            return false;
        }
    }

    /**
     * Versucht, ein Upgrade für einen Benutzer zu kaufen.
     * Führt dies in einer Transaktion aus.
     *
     * @param mysqli $db Die Datenbankverbindung.
     * @param int $userId Die ID des Benutzers.
     * @param int $upgradeId Die ID des zu kaufenden Upgrades.
     * @return array ['success' => bool, 'message' => string]
     */
    function purchaseUpgradeTransaction($db, $userId, $upgradeId) {
        $db->begin_transaction();

        try {
            // 1. Aktuelles Level des Upgrades holen
            $stmtLevel = $db->prepare("SELECT level FROM user_upgrades WHERE user_id = ? AND upgrade_id = ? FOR UPDATE"); // Sperren für Transaktion
            if (!$stmtLevel) throw new Exception("DB-Fehler (Level Prepare): " . $db->error);
            $stmtLevel->bind_param("ii", $userId, $upgradeId);
            $stmtLevel->execute();
            $resultLevel = $stmtLevel->get_result();
            $currentLevel = 0;
            if ($rowLevel = $resultLevel->fetch_assoc()) {
                $currentLevel = (int)$rowLevel['level'];
            }
            $stmtLevel->close();
            $nextLevel = $currentLevel + 1;

            // 2. Kosten für das nächste Level berechnen
            $cost = calculateUpgradeCostPHP($db, $upgradeId, $currentLevel);
            if ($cost === false) {
                throw new Exception("Upgrade nicht gefunden oder Kostenberechnung fehlgeschlagen.");
            }

            // 3. Aktuellen Kontostand holen und prüfen (mit Sperre)
            $stmtCurrency = $db->prepare("SELECT amount FROM beute_batzen WHERE user_id = ? FOR UPDATE");
            if (!$stmtCurrency) throw new Exception("DB-Fehler (Currency Prepare): " . $db->error);
            $stmtCurrency->bind_param("i", $userId);
            $stmtCurrency->execute();
            $resultCurrency = $stmtCurrency->get_result();
            $currentAmount = 0;
            if ($rowCurrency = $resultCurrency->fetch_assoc()) {
                $currentAmount = (float)$rowCurrency['amount'];
            }
            $stmtCurrency->close();

            if ($currentAmount < $cost) {
                throw new Exception("Nicht genug Beute-Batzen.");
            }

            // 4. Kosten abziehen
            $stmtDeduct = $db->prepare("UPDATE beute_batzen SET amount = amount - ? WHERE user_id = ?");
            if (!$stmtDeduct) throw new Exception("DB-Fehler (Deduct Prepare): " . $db->error);
            $stmtDeduct->bind_param("di", $cost, $userId);
            if (!$stmtDeduct->execute()) throw new Exception("DB-Fehler (Deduct Execute): " . $stmtDeduct->error);
            $stmtDeduct->close();

            // 5. Upgrade-Level erhöhen
            // Verwende INSERT ... ON DUPLICATE KEY UPDATE, falls der Eintrag noch nicht existiert (sollte aber durch init)
            $stmtUpgrade = $db->prepare("INSERT INTO user_upgrades (user_id, upgrade_id, level) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE level = ?");
            if (!$stmtUpgrade) throw new Exception("DB-Fehler (Upgrade Prepare): " . $db->error);
            $stmtUpgrade->bind_param("iiii", $userId, $upgradeId, $nextLevel, $nextLevel);
            if (!$stmtUpgrade->execute()) throw new Exception("DB-Fehler (Upgrade Execute): " . $stmtUpgrade->error);
            $stmtUpgrade->close();

            // Wenn alles gut ging, Transaktion committen
            $db->commit();
            return ['success' => true, 'message' => 'Upgrade erfolgreich gekauft.', 'newLevel' => $nextLevel];

        } catch (Exception $e) {
            // Bei Fehlern Rollback durchführen
            $db->rollback();
            error_log("purchaseUpgradeTransaction Error: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Funktion um Admin und Nicht-Admins zu unterscheiden
    function fetchAllNonAdminUsers($db) {
        $stmt = $db->prepare("SELECT id, username, isLocked FROM users WHERE isAdmin = 0 ORDER BY username ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
?>