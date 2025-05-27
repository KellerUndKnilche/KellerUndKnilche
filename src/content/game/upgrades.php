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
    // Lock-Verzeichnis im Projekt erstellen falls es nicht existiert
    $lockDir = __DIR__ . '/../../locks';
    if (!is_dir($lockDir)) {
        if (!mkdir($lockDir, 0755, true) && !is_dir($lockDir)) {
            // Fallback: System-Temp-Verzeichnis verwenden
            $lockDir = sys_get_temp_dir();
        }
    }
    
    // User-spezifischer Lock-File Pfad
    $lockFile = $lockDir . "/upgrade_lock_" . $userId;
    $handle = fopen($lockFile, 'w');
    
    // Non-blocking Lock mit Timeout
    if ($handle && flock($handle, LOCK_EX | LOCK_NB)) {
        // Lock erfolgreich erworben
        fwrite($handle, "Locked by user $userId at " . date('Y-m-d H:i:s'));
        return $handle;
    }
    
    // Lock fehlgeschlagen
    if ($handle) {
        fclose($handle);
    }
    return false;
}

function releaseUpgradeLock($handle, $userId = null) {
    if ($handle) {
        flock($handle, LOCK_UN);
        fclose($handle);
        
        // Lock-File automatisch löschen wenn userId verfügbar
        if ($userId) {
            $lockDir = __DIR__ . '/../../locks';
            // Fallback für System-Temp wenn eigenes Verzeichnis nicht verfügbar
            if (!is_dir($lockDir)) {
                $lockDir = sys_get_temp_dir();
            }
            $lockFile = $lockDir . "/upgrade_lock_" . $userId;
            if (file_exists($lockFile)) {
                unlink($lockFile);
            }
        }
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
            releaseUpgradeLock($lockHandle, $userId);
            echo json_encode(['success' => false]);
            exit;
        }

        releaseUpgradeLock($lockHandle, $userId);
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
            releaseUpgradeLock($lock, $userId);
        }
        exit;

    default:
        echo json_encode(['error' => 'Ungültige Aktion']);
}
?>
