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

// Wenn keine Aktion übergeben wurde, einfach nichts tun
if (!$action) {
    echo json_encode([]);
    exit;
}

switch ($action) {
    case 'levelup':
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Kein Benutzer eingeloggt']);
            break;
        }

        $upgradeId = $data['upgradeId'] ?? null;
        $newLevel = $data['newLevel'] ?? null;

        if ($upgradeId === null || $newLevel === null) {
            echo json_encode(['success' => false, 'message' => 'Fehlende Parameter']);
            break;
        }

        $success = updateUserUpgrade($db, $userId, $upgradeId, $newLevel);
        echo json_encode(['success' => $success]);
        break;

    case 'getUpgrades':
        if (!$userId) {
            // Keine Fehlermeldung, aber auch keine Daten
            echo json_encode([]);
            break;
        }

        $levels = getUserUpgrades($db, $userId);
        
        echo json_encode($levels);
        break;

    default:
        echo json_encode(['error' => 'Ungültige Aktion']);
}
?>
