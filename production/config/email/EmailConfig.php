<?php
/*
 * E-Mail Konfiguration für Keller & Knilche
 * 
 * Prozedurale Funktionen zur Verwaltung der E-Mail-Konfiguration basierend auf den .env Einstellungen
 */

// Globale Variable für die Konfiguration
$GLOBALS['email_config'] = null;

/*
 * Lädt die E-Mail-Konfiguration aus der .env Datei
 * 
 * @return array Die E-Mail-Konfiguration
 */
function getEmailConfig() {
    if ($GLOBALS['email_config'] === null) {
        loadEmailConfig();
    }
    return $GLOBALS['email_config'];
}

/*
 * Lädt die Konfiguration aus der .env Datei
 * 
 * @throws Exception Wenn die .env Datei nicht gefunden wird
 */
function loadEmailConfig() {
    $envPath = __DIR__ . '/../.env';
    
    if (!file_exists($envPath)) {
        throw new Exception('E-Mail Konfigurationsdatei (.env) nicht gefunden');
    }
    
    $envVars = parseEnvFile($envPath);
    
    $GLOBALS['email_config'] = [
        'smtp_host' => $envVars['SMTP_HOST'] ?? '',
        'smtp_port' => intval($envVars['SMTP_PORT'] ?? 587),
        'smtp_username' => $envVars['SMTP_USERNAME'] ?? '',
        'smtp_password' => $envVars['SMTP_PASSWORD'] ?? '',
        'smtp_encryption' => $envVars['SMTP_ENCRYPTION'] ?? 'tls',
        'smtp_auth' => filter_var($envVars['SMTP_AUTH'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'from_email' => $envVars['SMTP_FROM_EMAIL'] ?? '',
        'from_name' => $envVars['SMTP_FROM_NAME'] ?? 'Keller & Knilche'
    ];
    
    validateEmailConfig();
}

/*
 * Parst eine .env Datei und gibt die Variablen als Array zurück
 * 
 * @param string $filePath Pfad zur .env Datei
 * @return array Assoziatives Array mit den Umgebungsvariablen
 */
function parseEnvFile($filePath) {
    $vars = [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Kommentare überspringen
        if (strpos($line, '#') === 0 || strpos($line, '//') === 0) {
            continue;
        }
        
        // Variable parsen
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $vars[trim($key)] = trim($value);
        }
    }
    
    return $vars;
}

/*
 * Validiert die geladene E-Mail-Konfiguration
 * 
 * @throws Exception Wenn erforderliche Konfigurationswerte fehlen
 */
function validateEmailConfig() {
    $required = ['smtp_host', 'smtp_username', 'smtp_password', 'from_email'];
    
    foreach ($required as $key) {
        if (empty($GLOBALS['email_config'][$key])) {
            throw new Exception("E-Mail Konfiguration unvollständig: {$key} fehlt");
        }
    }
    
    // E-Mail-Format validieren
    if (!filter_var($GLOBALS['email_config']['from_email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Ungültige Absender-E-Mail-Adresse in der Konfiguration');
    }
}

/*
 * Gibt einen spezifischen Konfigurationswert zurück
 * @param string $key Der Konfigurationsschlüssel
 * @return mixed Der Konfigurationswert oder null
 */
function getEmailConfigValue($key) {
    $config = getEmailConfig();
    return $config[$key] ?? null;
}

/*
 * Prüft ob die E-Mail-Konfiguration vollständig ist
 * 
 * @return bool True wenn die Konfiguration vollständig ist
 */
function isEmailConfigured() {
    try {
        getEmailConfig();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
