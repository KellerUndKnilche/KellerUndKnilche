<?php
require_once('../../config/dbAccess.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// JSON-Daten einlesen
$data = json_decode(file_get_contents("php://input"), true);

// Standardwerte setzen
$action = $data['action'] ?? null;
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
        $upgrades = $data['upgrades'] ?? null;
        if (!$upgrades) {
            echo json_encode(['success' => false]);
            exit;
        }

        if (!saveUserUpgrades($db, $userId, $upgrades)) {
            echo json_encode(['success' => false]);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;

    case 'getUpgrades':
        $upgrades = getUserUpgrades($db, $userId);
        
        echo json_encode($upgrades);
        exit;

    case 'buyUpgrade':
        $upgradeId = $data['upgradeId'] ?? null;
        if (!$upgradeId || !is_numeric($upgradeId)) {
            echo json_encode(['success' => false, 'message' => 'Ungültige Upgrade-ID.']);
            exit;
        }
        $result = purchaseUpgradeTransaction($db, $userId, (int)$upgradeId);
        echo json_encode($result);
        exit;

    default:
        echo json_encode(['error' => 'Ungültige Aktion']);
}
?>
