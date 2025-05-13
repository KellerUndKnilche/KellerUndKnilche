<?php
require_once('../../config/dbAccess.php');
require_once('../../config/hmac.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// JSON-Daten einlesen
$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';
$decoded = base64_decode($token);

// Sicherstellen, dass der Token korrekt dekodiert und aufgeteilt werden kann
$parts = explode('|', $decoded);
if (count($parts) !== 3) {
    echo json_encode(['success' => false, 'message' => 'Ungültiger Token-Format.']);
    exit;
}
list($uid, $timestamp, $hmac) = $parts;
$data = "$uid|$timestamp";
if (!is_string($hmac) || !hash_equals(generateHmac($data), $hmac) || abs(time() - $timestamp) > 30 || $uid != $_SESSION['user']['id']) {
    echo json_encode(['success' => false, 'message' => 'Ungültiger oder abgelaufener Token.']);
    exit;
}

// Standardwerte setzen
$action = $input['action'] ?? null;
$userId = $_SESSION['user']['id'] ?? null;
session_write_close();

// Wenn keine Aktion übergeben wurde, einfach nichts tun
if (!$action) {
    echo json_encode([]);
    exit;
}

if(!$userId) {
    echo json_encode([]);
    exit;
}

switch ($action) {
    case 'saveUpgrades':
        $upgrades = $input['upgrades'] ?? null;
        if (!$upgrades) {
            echo json_encode(['success' => false]);
            exit;
        }

        if (!saveUserUpgrades($db, $userId, $upgrades)) {
            echo json_encode(['success' => false]);
            exit;
        }
        $newTimestamp = time();
        $newData = "$userId|$newTimestamp";
        $newHmac = generateHmac($newData);
        $newToken = base64_encode("$newData|$newHmac");
        echo json_encode([
            'success' => true,
            'newAmount' => $newAmount,
            'newToken' => $newToken
        ]);
        exit;

    case 'getUpgrades':
        $upgrades = getUserUpgrades($db, $userId);
        
        echo json_encode($upgrades);
        exit;

    case 'buyUpgrade':
        $upgradeId = $input['upgradeId'] ?? null;
        if (!$upgradeId || !is_numeric($upgradeId)) {
            echo json_encode(['success' => false, 'message' => 'Ungültige Upgrade-ID.']);
            exit;
        }
        $result = purchaseUpgradeTransaction($db, $userId, (int)$upgradeId);
        $newTimestamp = time();
        $newData = "$userId|$newTimestamp";
        $newHmac = generateHmac($newData);
        $newToken = base64_encode("$newData|$newHmac");
        echo json_encode([
            'success' => true,
            'newAmount' => $newAmount, 
            'newToken' => $newToken
        ]);
        exit;

    default:
        echo json_encode(['error' => 'Ungültige Aktion']);
}
?>
