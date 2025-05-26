<?php
/*
 * E-Mail Versendung für Keller & Knilche
 * 
 * Prozedurale Funktionen zum Versenden von E-Mails
 */

require_once __DIR__ . '/EmailConfig.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
 * Sendet eine E-Mail
 * 
 * @param string $to Empfänger E-Mail-Adresse
 * @param string $subject E-Mail Betreff
 * @param string $body E-Mail Inhalt (HTML oder Text)
 * @param bool $isHTML True für HTML-E-Mail, false für Text
 * @param string|null $toName Name des Empfängers (optional)
 * @return bool True bei erfolgreichem Versand
 * @throws Exception Bei Versandfehlern
 */
function sendEmail($to, $subject, $body, $isHTML = true, $toName = null) {
    if (!isEmailConfigured()) {
        throw new Exception('E-Mail-Konfiguration ist nicht vollständig');
    }
    
    $config = getEmailConfig();
    $mail = createMailer();
    
    try {
        // Server-Einstellungen
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = $config['smtp_auth'];
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = $config['smtp_encryption'];
        $mail->Port = $config['smtp_port'];
        
        // Empfänger
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($to, $toName);
        
        // Inhalt
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->CharSet = 'UTF-8';
        
        return $mail->send();
        
    } catch (Exception $e) {
        throw new Exception('E-Mail konnte nicht versendet werden: ' . $mail->ErrorInfo);
    }
}

/*
 * Erstellt eine PHPMailer-Instanz mit den Standardeinstellungen
 * 
 * @return PHPMailer Die konfigurierte PHPMailer-Instanz
 */
function createMailer() {
    $mail = new PHPMailer(true);
    
    // Debug-Modus deaktivieren für Production
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    
    return $mail;
}

/*
 * Sendet eine Verifikations-E-Mail
 * 
 * @param string $to Empfänger E-Mail-Adresse
 * @param string $username Benutzername
 * @param string $verificationToken Verifikationstoken
 * @param string $baseUrl Basis-URL der Anwendung
 * @return bool True bei erfolgreichem Versand
 */
function sendVerificationEmail($to, $username, $verificationToken, $baseUrl) {
    $subject = 'E-Mail-Adresse verifizieren - Keller & Knilche';
    $verificationUrl = $baseUrl . '/verify.php?token=' . urlencode($verificationToken);
    
    $body = getVerificationEmailTemplate($username, $verificationUrl);
    
    return sendEmail($to, $subject, $body, true, $username);
}

/*
 * Sendet eine Passwort-Reset-E-Mail
 * 
 * @param string $to Empfänger E-Mail-Adresse
 * @param string $username Benutzername
 * @param string $resetToken Reset-Token
 * @param string $baseUrl Basis-URL der Anwendung
 * @return bool True bei erfolgreichem Versand
 */
function sendPasswordResetEmail($to, $username, $resetToken, $baseUrl) {
    $subject = 'Passwort zurücksetzen - Keller & Knilche';
    $resetUrl = $baseUrl . '/reset-password.php?token=' . urlencode($resetToken);
    
    $body = getPasswordResetEmailTemplate($username, $resetUrl);
    
    return sendEmail($to, $subject, $body, true, $username);
}

/*
 * Testet die E-Mail-Konfiguration durch Versendung einer Test-E-Mail
 * 
 * @param string $testEmail E-Mail-Adresse für den Test
 * @return bool True wenn der Test erfolgreich war
 */
function testEmailConfiguration($testEmail) {
    $subject = 'Test E-Mail - Keller & Knilche';
    $body = '<h2>Test E-Mail</h2><p>Wenn Sie diese E-Mail erhalten, funktioniert die E-Mail-Konfiguration korrekt.</p>';
    
    try {
        return sendEmail($testEmail, $subject, $body);
    } catch (Exception $e) {
        error_log('E-Mail-Test fehlgeschlagen: ' . $e->getMessage());
        return false;
    }
}
