<?php
// Setzt den Cookie-Pfad explizit auf '/', um sicherzustellen, dass das Cookie gelesen werden kann
session_set_cookie_params(['path' => '/']); 
session_start();

// Konfiguration und Datenbankzugriff einbinden
require_once __DIR__ . '/../config/dbAccess.php'; // Passe den Pfad ggf. an

header('Content-Type: application/json');

// Prüfen, ob der Benutzer eingeloggt ist (prüft jetzt auf den korrekten Array-Schlüssel)
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$userId = $_SESSION['user']['id']; // Holt die ID aus dem User-Array

// Globale $db-Variable aus dbAccess.php verwenden
if (!isset($db) || !$db) {
    // Versuche die Verbindung neu aufzubauen, falls sie nicht global verfügbar ist
    // (Hängt davon ab, wie dbAccess.php $db initialisiert)
     // Lade .env-Variablen (benötigt eine .env Ladebibliothek wie vlucas/phpdotenv)
     // Beispiel: require_once __DIR__ . '/../../vendor/autoload.php';
     // $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Passe Pfad an
     // $dotenv->load();
     // $host = $_ENV['DB_HOST'] ?? 'localhost';
     // $user = $_ENV['DB_USER'] ?? null;
     // $password = $_ENV['DB_PASSWORD'] ?? null;
     // $database = $_ENV['DB_NAME'] ?? null;
    // $db = new mysqli($host, $user, $password, $database);
     // if ($db->connect_error) {
          echo json_encode(['success' => false, 'message' => 'Datenbankverbindung fehlgeschlagen.']);
          error_log("API Update Currency: DB connection failed: " . $db->connect_error);
          exit;
     // }
     // --- Wenn $db nicht global ist, muss hier die Verbindung aufgebaut werden ---
     // Annahme für jetzt: $db ist global durch require_once dbAccess.php verfügbar.
     if (!isset($db)) { // Prüfen ob $db nach include existiert
          echo json_encode(['success' => false, 'message' => 'Globale Datenbankverbindung nicht gefunden.']);
          error_log("API Update Currency: Global DB connection variable not found after include.");
          exit;
     }

}

// Währung aktualisieren und Produktionsrate holen
$productionRate = updateUserCurrency($db, $userId);

if ($productionRate === false) {
    echo json_encode(['success' => false, 'message' => 'Fehler beim Aktualisieren der Währung.']);
    // Fehler wurde bereits in updateUserCurrency geloggt
    exit;
}

// Aktuellen Währungsstand holen (nach dem Update)
$currentAmount = getCurrentCurrency($db, $userId);

// Erfolg und Daten zurückgeben
echo json_encode([
    'success' => true,
    'newAmount' => round($currentAmount, 2), // Runde auf 2 Dezimalstellen für die Anzeige
    'productionPerSecond' => round($productionRate, 2) // Runde auf 2 Dezimalstellen für die Anzeige
]);

?>
