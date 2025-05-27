<?php
/**
 * E-Mail-Verifikations-Funktionen für Keller & Knilche
 * 
 * Prozedurale Funktionen zur Verwaltung der E-Mail-Verifikation
 * Diese Funktionen sollten in dbAccess.php eingefügt werden
 */

/**
 * Generiert einen sicheren Verifikationstoken
 * 
 * @return string Der generierte Token
 */
function generateVerificationToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Erstellt einen Benutzer mit Verifikationstoken
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $username Benutzername
 * @param string $email E-Mail-Adresse
 * @param string $passwordHash Gehashtes Passwort
 * @return array ['success' => bool, 'token' => string, 'user_id' => int, 'message' => string]
 */
function registerUserWithVerification($db, $username, $email, $passwordHash) {
    if (userExists($db, $username, $email)) {
        return ['success' => false, 'message' => 'Benutzername oder E-Mail bereits vergeben.'];
    }
    
    // Verifikationstoken generieren
    $verificationToken = generateVerificationToken();
    $tokenExpires = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    try {
        $stmt = $db->prepare(
            "INSERT INTO users (username, email, password_hash, email_verified, verification_token, verification_token_expires, acceptedTerms) 
             VALUES (?, ?, ?, 0, ?, ?, 1)"
        );
        $stmt->bind_param("sssss", $username, $email, $passwordHash, $verificationToken, $tokenExpires);
        
        if ($stmt->execute()) {
            $userId = $db->insert_id;
            initUserUpgrades($db, $username);
            
            return [
                'success' => true,
                'token' => $verificationToken,
                'user_id' => $userId,
                'message' => 'Registrierung erfolgreich! Bitte prüfen Sie Ihre E-Mails.'
            ];
        } else {
            return ['success' => false, 'message' => 'Fehler bei der Registrierung.'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()];
    }
}

/**
 * Verifiziert eine E-Mail-Adresse mit dem Token
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $token Verifikationstoken
 * @return array ['success' => bool, 'message' => string, 'user' => array|null]
 */
function verifyEmailToken($db, $token) {
    if (empty($token)) {
        return ['success' => false, 'message' => 'Kein Token angegeben.'];
    }
    
    $stmt = $db->prepare("
        SELECT id, username, email, email_verified, verification_token_expires 
        FROM users 
        WHERE verification_token = ? AND verification_token_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Ungültiger oder abgelaufener Verifikationslink.'];
    }
    
    $user = $result->fetch_assoc();
    
    if ($user['email_verified'] == 1) {
        return ['success' => false, 'message' => 'E-Mail-Adresse bereits verifiziert.'];
    }
    
    // E-Mail verifizieren
    $updateStmt = $db->prepare("
        UPDATE users 
        SET email_verified = 1, verification_token = NULL, verification_token_expires = NULL 
        WHERE id = ?
    ");
    $updateStmt->bind_param("i", $user['id']);
    
    if ($updateStmt->execute()) {
        return [
            'success' => true,
            'message' => 'E-Mail-Adresse erfolgreich verifiziert!',
            'user' => $user
        ];
    } else {
        return ['success' => false, 'message' => 'Fehler bei der Verifikation.'];
    }
}

/**
 * Prüft, ob ein Benutzer seine E-Mail verifiziert hat
 * 
 * @param mysqli $db Datenbankverbindung
 * @param int $userId Benutzer-ID
 * @return bool True wenn verifiziert
 */
function isEmailVerified($db, $userId) {
    $stmt = $db->prepare("SELECT email_verified FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return (bool)$row['email_verified'];
    }
    return false;
}

/**
 * Sendet eine neue Verifikations-E-Mail
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $email E-Mail-Adresse
 * @return array ['success' => bool, 'message' => string, 'token' => string|null]
 */
function resendVerificationEmail($db, $email) {
    $stmt = $db->prepare("
        SELECT id, username, email_verified 
        FROM users 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'E-Mail-Adresse nicht gefunden.'];
    }
    
    $user = $result->fetch_assoc();
    
    if ($user['email_verified'] == 1) {
        return ['success' => false, 'message' => 'E-Mail-Adresse bereits verifiziert.'];
    }
    
    // Neuen Token generieren
    $verificationToken = generateVerificationToken();
    $tokenExpires = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    $updateStmt = $db->prepare("
        UPDATE users 
        SET verification_token = ?, verification_token_expires = ? 
        WHERE id = ?
    ");
    $updateStmt->bind_param("ssi", $verificationToken, $tokenExpires, $user['id']);
    
    if ($updateStmt->execute()) {
        return [
            'success' => true,
            'message' => 'Neue Verifikations-E-Mail wurde versendet.',
            'token' => $verificationToken,
            'username' => $user['username']
        ];
    } else {
        return ['success' => false, 'message' => 'Fehler beim Versenden der E-Mail.'];
    }
}

/**
 * Initiiert einen Passwort-Reset
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $email E-Mail-Adresse
 * @return array ['success' => bool, 'message' => string, 'token' => string|null, 'username' => string|null]
 */
function initiatePasswordReset($db, $email) {
    $stmt = $db->prepare("
        SELECT id, username, email_verified 
        FROM users 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Aus Sicherheitsgründen keine Fehlermeldung, dass E-Mail nicht existiert
        return ['success' => true, 'message' => 'Falls die E-Mail-Adresse existiert, wurde ein Reset-Link versendet.'];
    }
    
    $user = $result->fetch_assoc();
    
    if ($user['email_verified'] == 0) {
        return ['success' => false, 'message' => 'E-Mail-Adresse muss zuerst verifiziert werden.'];
    }
    
    // Reset-Token generieren
    $resetToken = generateVerificationToken();
    $tokenExpires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $updateStmt = $db->prepare("
        UPDATE users 
        SET reset_token = ?, reset_token_expires = ? 
        WHERE id = ?
    ");
    $updateStmt->bind_param("ssi", $resetToken, $tokenExpires, $user['id']);
    
    if ($updateStmt->execute()) {
        return [
            'success' => true,
            'message' => 'Reset-Link wurde an Ihre E-Mail-Adresse gesendet.',
            'token' => $resetToken,
            'username' => $user['username']
        ];
    } else {
        return ['success' => false, 'message' => 'Fehler beim Versenden der Reset-E-Mail.'];
    }
}

/**
 * Überprüft einen Passwort-Reset-Token
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $token Reset-Token
 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
 */
function verifyPasswordResetToken($db, $token) {
    if (empty($token)) {
        return ['success' => false, 'message' => 'Kein Token angegeben.'];
    }
    
    $stmt = $db->prepare("
        SELECT id, username 
        FROM users 
        WHERE reset_token = ? AND reset_token_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Ungültiger oder abgelaufener Reset-Link.'];
    }
    
    $user = $result->fetch_assoc();
    return [
        'success' => true,
        'message' => 'Token gültig.',
        'user_id' => $user['id'],
        'username' => $user['username']
    ];
}

/**
 * Setzt ein neues Passwort mit Reset-Token
 * 
 * @param mysqli $db Datenbankverbindung
 * @param string $token Reset-Token
 * @param string $newPassword Neues Passwort (ungehasht)
 * @return array ['success' => bool, 'message' => string]
 */
function resetPasswordWithToken($db, $token, $newPassword) {
    $tokenCheck = verifyPasswordResetToken($db, $token);
    
    if (!$tokenCheck['success']) {
        return $tokenCheck;
    }
    
    if (strlen($newPassword) < 6) {
        return ['success' => false, 'message' => 'Passwort muss mindestens 6 Zeichen lang sein.'];
    }
    
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("
        UPDATE users 
        SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $passwordHash, $tokenCheck['user_id']);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Passwort erfolgreich zurückgesetzt.'];
    } else {
        return ['success' => false, 'message' => 'Fehler beim Zurücksetzen des Passworts.'];
    }
}

/**
 * Bereinigt abgelaufene Tokens (sollte regelmäßig aufgerufen werden)
 * 
 * @param mysqli $db Datenbankverbindung
 * @return int Anzahl bereinigter Einträge
 */
function cleanupExpiredTokens($db) {
    $stmt = $db->prepare("
        UPDATE users 
        SET verification_token = NULL, verification_token_expires = NULL 
        WHERE verification_token_expires < NOW()
    ");
    $stmt->execute();
    $verificationCleaned = $stmt->affected_rows;
    
    $stmt = $db->prepare("
        UPDATE users 
        SET reset_token = NULL, reset_token_expires = NULL 
        WHERE reset_token_expires < NOW()
    ");
    $stmt->execute();
    $resetCleaned = $stmt->affected_rows;
    
    return $verificationCleaned + $resetCleaned;
}
?>
