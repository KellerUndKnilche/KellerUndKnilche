<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Setzt den Cookie-Pfad explizit auf '/', um sicherzustellen, dass das Cookie gelesen werden kann
session_set_cookie_params(['path' => '/']); 
session_start();

// Konfiguration und Datenbankzugriff einbinden
require_once __DIR__ . '/../config/dbAccess.php'; // Pfad anpassen falls nötig
require_once(__DIR__ . '../config/hmac.php');

header('Content-Type: application/json');

// Prüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user']['id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';
$decoded = base64_decode($token);
list($uid, $timestamp, $hmac) = explode('|', $decoded);
$data = "$uid|$timestamp";
if (
    !hash_equals(generateHmac($data), $hmac) ||
    abs(time() - $timestamp) > 30 ||
    $uid != $_SESSION['user']['id']
) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Ungültiger oder abgelaufener Token.']);
    exit;
}

$userId = $_SESSION['user']['id'];

// Globale $db-Variable prüfen
if (!isset($db) || !$db) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Datenbankverbindung nicht verfügbar.']);
    error_log("API Register Click: Globale DB-Verbindung nicht gefunden.");
    exit;
}

// --- Klick verarbeiten ---

// 1. Klickwert bestimmen (dynamisch durch Upgrades)
$clickValue = calculateUserClickValue($db, $userId); // Verwende die neue Funktion

// 2. Währung in der Datenbank erhöhen
// Verwende INSERT ... ON DUPLICATE KEY UPDATE, falls der Benutzer noch keinen Eintrag hat
$stmtUpdate = $db->prepare("
    INSERT INTO beute_batzen (user_id, amount)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE amount = amount + ?
");

if (!$stmtUpdate) {
    http_response_code(500);
    error_log("API Register Click: Prepare failed (stmtUpdate): " . $db->error);
    echo json_encode(['success' => false, 'message' => 'Datenbankfehler beim Vorbereiten des Klick-Updates.']);
    exit;
}

// 'd' für double/float verwenden, da 'amount' BIGINT ist und Dezimalwerte haben kann
$stmtUpdate->bind_param("idd", $userId, $clickValue, $clickValue);
$success = $stmtUpdate->execute();
$stmtUpdate->close();

if (!$success) {
    http_response_code(500);
    error_log("API Register Click: Execute failed (stmtUpdate): " . $stmtUpdate->error); // Vorsicht: $stmtUpdate->error ist nach close() evtl. nicht mehr verfügbar
    echo json_encode(['success' => false, 'message' => 'Datenbankfehler beim Ausführen des Klick-Updates.']);
    exit;
}

// 3. Neuen Gesamtbetrag abrufen
$newTotalAmount = getCurrentCurrency($db, $userId); // Diese Funktion existiert bereits in dbAccess.php

// 4. Erfolgsantwort mit neuem Betrag und neuem Token senden
$newTimestamp = time();
$newData = "$userId|$newTimestamp";
$newHmac = generateHmac($newData);
$newToken = base64_encode("$newData|$newHmac");
echo json_encode([
    'success' => true,
    'newAmount' => round($newTotalAmount, 2),
    'newToken' => $newToken
]);

?>
