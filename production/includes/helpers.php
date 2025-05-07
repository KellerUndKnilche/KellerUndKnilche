<?php
/*
 * Bestimmt Basis-URL der aktuellen Umgebung
 * prüft ob HTTPS aktiv ist, auch bei Verwendung einer Reverse Proxy.
 *
 * return Basis-URL der aktuellen Umgebung.
 * zB:
 * - Lokal: http://localhost
 * - Web: https://keller-und-knilche.at
 */
function getBaseUrl() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
               || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $protocol = $isHttps ? "https" : "http";
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}
?>
