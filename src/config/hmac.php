<?php
define('HMAC_SECRET', 'a3f7c8e2d4b9f6a1c5e8b3d7f2a9c6e4b1f5c9d2a6e8f1b4c7e3d9f2a8c6e1b5'); 

function generateHmac($data, $algorithm = 'sha256') {
    return hash_hmac($algorithm, $data, HMAC_SECRET);
}

function verifyHmac($data, $expectedHmac, $algorithm = 'sha256') {
    $calculatedHmac = generateHmac($data, $algorithm);
    return hash_equals($expectedHmac, $calculatedHmac);
}

function generateTimestampedHmac($data) {
    $timestamp = time();
    $message = $data . ':' . $timestamp;
    $hmac = generateHmac($message);
    return [
        'data' => $data,
        'timestamp' => $timestamp,
        'hmac' => $hmac,
        'full' => $message . ':' . $hmac
    ];
}

function verifyTimestampedHmac($data, $timestamp, $receivedHmac, $maxAge = 300) {
    if (time() - $timestamp > $maxAge) {
        return false;
    }
    $message = $data . ':' . $timestamp;
    return verifyHmac($message, $receivedHmac);
}
?>