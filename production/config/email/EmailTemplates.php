<?php
/*
 * E-Mail-Templates für Keller & Knilche
 * 
 * Prozedurale Funktionen zur Generierung von E-Mail-Templates
 */

/*
 * Generiert das HTML-Template für Verifikations-E-Mails
 * 
 * @param string $username Benutzername
 * @param string $verificationUrl Verifikations-URL
 * @return string HTML-Template
 */
function getVerificationEmailTemplate($username, $verificationUrl) {
    return '
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Mail verifizieren</title>
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #2c1810, #5d4037); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffd700; margin: 0; font-size: 2.5em; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                Keller & Knilche
            </h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
            <h2 style="color: #5d4037; margin-top: 0;">Willkommen im Verlies, ' . htmlspecialchars($username) . '!</h2>
            
            <p>Schön, dass du zu Keller & Knilche gefunden hast.</p>
            
            <p>Bevor du jedoch dein untotes Unwesen treiben darfst, musst du dein Konto aktivieren. Folge dem Pfad durch die Dunkelheit (oder klick einfach hier):</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($verificationUrl) . '" 
                   style="background: linear-gradient(135deg, #ffd700, #ffb300); 
                          color: #2c1810; 
                          text-decoration: none; 
                          padding: 15px 30px; 
                          border-radius: 25px; 
                          font-weight: bold; 
                          display: inline-block;
                          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                          transition: all 0.3s ease;">
                    Konto aktivieren
                </a>
            </div>
            
            <p><strong>Wichtig:</strong> Der Zauber ist nur 24 Stunden gültig – danach musst du wieder durch die Schatten wandeln.</p>
            
            <p>Falls du dich nicht registriert hast, ignoriere diese Botschaft einfach. Wahrscheinlich war es nur ein besonders verwirrter Geist.</p>
            
            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
            
            <p style="font-size: 0.9em; color: #666;">
                Sollte der Button nicht funktionieren, kopiere diesen Link in deinen Browser:<br>
                <a href="' . htmlspecialchars($verificationUrl) . '" style="color: #5d4037;">' . htmlspecialchars($verificationUrl) . '</a>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 0.8em;">
            <p>Gruftige Grüße!</p>
            <p>-  dein Keller & Knilche Team</p>
        </div>
    </body>
    </html>';
}

/*
 * Generiert das HTML-Template für Passwort-Reset-E-Mails
 * 
 * @param string $username Benutzername
 * @param string $resetUrl Passwort-Reset-URL
 * @return string HTML-Template
 */
function getPasswordResetEmailTemplate($username, $resetUrl) {
    return '
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Passwort zurücksetzen</title>
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #2c1810, #5d4037); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffd700; margin: 0; font-size: 2.5em; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                Keller & Knilche
            </h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
            <h2 style="color: #5d4037; margin-top: 0;">Passwort zurücksetzen</h2>
            
            <p>Hallo ' . htmlspecialchars($username) . ',</p>
            
            <p>Der Ruf aus dem Kerker hat uns erreicht – du willst dein Passwort neu beschwören.</p>
            
            <p>Nutze diesen uralten Link, um dein Passwort wiederherzustellen:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($resetUrl) . '" 
                   style="background: linear-gradient(135deg, #d32f2f, #f44336); 
                          color: white; 
                          text-decoration: none; 
                          padding: 15px 30px; 
                          border-radius: 25px; 
                          font-weight: bold; 
                          display: inline-block;
                          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                          transition: all 0.3s ease;">
                    Neues Passwort erstellen
                </a>
            </div>
            
            <p>Doch beeile dich! Der Zauber verflüchtigt sich nach 1 Stunde.</p>
            
            <p>Falls du keine solche Beschwörung in Auftrag gegeben hast, dann ignoriere diesen Brief – vielleicht war es nur ein vergesslicher Lich.</p>
            
            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
            
            <p style="font-size: 0.9em; color: #666;">
                Sollte der Button nicht funktionieren, kopiere diesen Link in deinen Browser:<br>
                <a href="' . htmlspecialchars($resetUrl) . '" style="color: #d32f2f;">' . htmlspecialchars($resetUrl) . '</a>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 0.8em;">
            <p>Möge dein neues Passwort stärker sein als Silber und Eisen!</p>
            <p>-  dein Keller & Knilche Team</p>
        </div>
    </body>
    </html>';
}

/*
 * Generiert ein einfaches Text-Template für Verifikations-E-Mails
 * 
 * @param string $username Benutzername
 * @param string $verificationUrl Verifikations-URL
 * @return string Text-Template
 */
function getVerificationEmailTextTemplate($username, $verificationUrl) {
    return "
Willkommen im Verlies, {$username}!

Schön, dass du zu Keller & Knilche gefunden hast.

Bevor du jedoch dein untotes Unwesen treiben darfst, musst du dein Konto aktivieren. Folge dem Pfad durch die Dunkelheit (oder klick einfach hier):

 {$verificationUrl}

Der Zauber ist nur 24 Stunden gültig – danach musst du wieder durch die Schatten wandeln.

Falls du dich nicht registriert hast, ignoriere diese Botschaft einfach. Wahrscheinlich war es nur ein besonders verwirrter Geist.

 Gruftige Grüße
- dein Keller & Knilche Team
";
}

/*
 * Generiert ein einfaches Text-Template für Passwort-Reset-E-Mails
 * 
 * @param string $username Benutzername
 * @param string $resetUrl Passwort-Reset-URL
 * @return string Text-Template
 */
function getPasswordResetEmailTextTemplate($username, $resetUrl) {
    return "
Hallo {$username},

Der Ruf aus dem Kerker hat uns erreicht - du willst dein Passwort neu beschwören.

Nutze diesen uralten Link, um dein Passwort wiederherzustellen:

{$resetUrl}

Doch beeile dich! Der Zauber verflüchtigt sich nach 1 Stunde.

Falls du keine solche Beschwörung in Auftrag gegeben hast, dann ignoriere diesen Brief – vielleicht war es nur ein vergesslicher Lich.

Möge dein neues Passwort stärker sein als Silber und Eisen!
- dein Keller & Knilche Team
";
}

/*
 * Generiert ein allgemeines E-Mail-Template
 * 
 * @param string $title Titel der E-Mail
 * @param string $content Hauptinhalt
 * @param string $username Benutzername (optional)
 * @return string HTML-Template
 */
function getGeneralEmailTemplate($title, $content, $username = null) {
    $greeting = $username ? "Hallo " . htmlspecialchars($username) . "," : "Hallo,";
    
    return '
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($title) . '</title>
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #2c1810, #5d4037); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffd700; margin: 0; font-size: 2.5em; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                Keller & Knilche
            </h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
            <h2 style="color: #5d4037; margin-top: 0;">' . htmlspecialchars($title) . '</h2>
            
            <p>' . $greeting . '</p>
            
            ' . $content . '
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 0.8em;">
            <p>© ' . date('Y') . ' Keller & Knilche - Alle Rechte vorbehalten</p>
        </div>
    </body>
    </html>';
}
