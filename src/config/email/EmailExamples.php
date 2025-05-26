<?php
/*
 * Beispiel für die Verwendung der E-Mail-Funktionen
 * 
 * Dieses Beispiel zeigt, wie die prozeduralen E-Mail-Funktionen verwendet werden
 */

// Alle notwendigen Dateien einbinden
require_once __DIR__ . '/EmailConfig.php';
require_once __DIR__ . '/EmailSender.php';
require_once __DIR__ . '/EmailTemplates.php';

/*
 * Beispiel: Verifikations-E-Mail senden
 */
function beispielVerifikationsEmail() {
    try {
        $erfolg = sendVerificationEmail(
            'user@example.com',          // Empfänger
            'MusterUser',                // Benutzername
            'abc123def456',              // Verifikationstoken
            'https://ihre-domain.de'     // Basis-URL
        );
        
        if ($erfolg) {
            echo "Verifikations-E-Mail erfolgreich versendet!\n";
        } else {
            echo "Fehler beim Versenden der Verifikations-E-Mail.\n";
        }
        
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage() . "\n";
    }
}

/*
 * Beispiel: Passwort-Reset-E-Mail senden
 */
function beispielPasswordResetEmail() {
    try {
        $erfolg = sendPasswordResetEmail(
            'user@example.com',          // Empfänger
            'MusterUser',                // Benutzername
            'reset123token456',          // Reset-Token
            'https://ihre-domain.de'     // Basis-URL
        );
        
        if ($erfolg) {
            echo "Passwort-Reset-E-Mail erfolgreich versendet!\n";
        } else {
            echo "Fehler beim Versenden der Passwort-Reset-E-Mail.\n";
        }
        
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage() . "\n";
    }
}

/*
 * Beispiel: Allgemeine E-Mail senden
 */
function beispielAllgemeineEmail() {
    try {
        $betreff = 'Wichtige Mitteilung';
        $inhalt = getGeneralEmailTemplate(
            'Wartungsarbeiten',
            '<p>Wir führen am kommenden Wochenende Wartungsarbeiten durch.</p>
             <p>Das Spiel wird voraussichtlich von Samstag 20:00 bis Sonntag 08:00 nicht verfügbar sein.</p>
             <p>Vielen Dank für Ihr Verständnis!</p>',
            'MusterUser'
        );
        
        $erfolg = sendEmail(
            'user@example.com',
            $betreff,
            $inhalt,
            true,                        // HTML-E-Mail
            'MusterUser'
        );
        
        if ($erfolg) {
            echo "Allgemeine E-Mail erfolgreich versendet!\n";
        } else {
            echo "Fehler beim Versenden der allgemeinen E-Mail.\n";
        }
        
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage() . "\n";
    }
}

/*
 * Beispiel: E-Mail-Konfiguration testen
 */
function beispielKonfigurationTest() {
    try {
        if (isEmailConfigured()) {
            echo "E-Mail-Konfiguration ist vollständig.\n";
            
            // Test-E-Mail senden
            $erfolg = testEmailConfiguration('test@example.com');
            
            if ($erfolg) {
                echo "Test-E-Mail erfolgreich versendet!\n";
            } else {
                echo "Test-E-Mail konnte nicht versendet werden.\n";
            }
            
        } else {
            echo "E-Mail-Konfiguration ist unvollständig.\n";
        }
        
    } catch (Exception $e) {
        echo "Fehler bei der Konfigurationsprüfung: " . $e->getMessage() . "\n";
    }
}

/*
 * Beispiel: Konfigurationswerte abrufen
 */
function beispielKonfigurationAbrufen() {
    try {
        echo "SMTP Host: " . getEmailConfigValue('smtp_host') . "\n";
        echo "SMTP Port: " . getEmailConfigValue('smtp_port') . "\n";
        echo "Von E-Mail: " . getEmailConfigValue('from_email') . "\n";
        echo "Von Name: " . getEmailConfigValue('from_name') . "\n";
        
    } catch (Exception $e) {
        echo "Fehler beim Abrufen der Konfiguration: " . $e->getMessage() . "\n";
    }
}

// Beispiel-Aufrufe (auskommentiert für normale Verwendung)
/*
echo "=== E-Mail System Beispiele ===\n\n";

echo "1. Konfiguration prüfen:\n";
beispielKonfigurationTest();

echo "\n2. Konfigurationswerte anzeigen:\n";
beispielKonfigurationAbrufen();

echo "\n3. Verifikations-E-Mail senden:\n";
beispielVerifikationsEmail();

echo "\n4. Passwort-Reset-E-Mail senden:\n";
beispielPasswordResetEmail();

echo "\n5. Allgemeine E-Mail senden:\n";
beispielAllgemeineEmail();
*/
