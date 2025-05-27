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

// Server-side Mutex für Race Condition Prevention
function acquireUpgradeLock($userId) {
    $lockFile = sys_get_temp_dir() . "/upgrade_lock_" . $userId;
    $handle = fopen($lockFile, 'w');
    if ($handle && flock($handle, LOCK_EX | LOCK_NB)) {
        return $handle;
    }
    return false;
}

function releaseUpgradeLock($handle) {
    if ($handle) {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

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

        // Mutex erwerben
        $lockHandle = acquireUpgradeLock($userId);
        if (!$lockHandle) {
            echo json_encode(['success' => false, 'message' => 'Konnte das Upgrade nicht speichern. Bitte versuchen Sie es später erneut.']);
            exit;
        }

        if (!saveUserUpgrades($db, $userId, $upgrades)) {
            releaseUpgradeLock($lockHandle);
            echo json_encode(['success' => false]);
            exit;
        }

        releaseUpgradeLock($lockHandle);
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

        // Lock um Race Conditions während des Kaufs zu verhindern
        $lock = acquireUpgradeLock($userId);
        if (!$lock) {
            echo json_encode(['success' => false, 'message' => 'Upgrade-Operation läuft bereits']);
            exit;
        }

        try {
            $result = purchaseUpgradeTransaction($db, $userId, (int)$upgradeId);
            echo json_encode($result);
        } finally {
            releaseUpgradeLock($lock);
        }
        exit;

    default:
        echo json_encode(['error' => 'Ungültige Aktion']);
}
?>
