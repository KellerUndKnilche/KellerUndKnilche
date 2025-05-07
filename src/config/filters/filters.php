<?php
// Username-Filter
/*
* Prüft, ob Username gültig
* - Username case-insensitive
* - Username(teil) nicht in bad-words.txt
*/

// LeetSpeak-Varianten generieren
function generateLeetVariants(string $input): array {
    // LeetSpeak‑Mapping für Ziffern
    $leetMap = [
        '0' => ['o'],
        '1' => ['i', 'l'],
        '2' => ['z', 'r'],
        '3' => ['e'],
        '4' => ['a'],
        '5' => ['s'],
        '6' => ['g', 'b'],
        '7' => ['t', 'l'],
        '8' => ['b'],
        '9' => ['g'],
    ];
    $variants = [''];
    $len = mb_strlen($input);
    for ($i = 0; $i < $len; $i++) {
        $char    = $input[$i];
        $current = [];
        if (isset($leetMap[$char])) {
            // jede mögliche Ersatzbuchstabe‑Kombination anhängen
            foreach ($leetMap[$char] as $rep) {
                foreach ($variants as $prefix) {
                    $current[] = $prefix . $rep;
                }
            }
        } else {
            foreach ($variants as $prefix) {
                $current[] = $prefix . $char;
            }
        }
        $variants = $current;
    }
    return array_unique($variants);
}
 
// Returned Wortliste aus bad-words.txt
function getBadWords(): array {
    static $list = null;
    if ($list === null) {
        $raw = @file(__DIR__ . '/bad-words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $list = [];
        if ($raw) {
            foreach ($raw as $bad) {
                $bad = strtolower(trim($bad));
                if ($bad !== '') {
                    $list[] = $bad;
                }
            }
        }
    }
    return $list;
}
    
function isCleanUsername(string $username)
{
    // Normiere und generiere Leet-Varianten
    $usernameLower = strtolower($username);
    $variants = array_unique(
        array_merge(
            [$usernameLower],
            generateLeetVariants($usernameLower)
        )
    ); // erzeugt uniquen Array

    $badWords = getBadWords();
    if (empty($badWords)) {
        return true; // keine Filterung, wenn keine Einträge
    }

    // Prüfe jede Variante auf jedes Bad‑Word
    foreach ($badWords as $bad) {
        foreach ($variants as $variant) {
            if (strpos($variant, $bad) !== false) {
                return false; // Ungültiger Username
            }
        }
    }

    return true; // Gültiger Username
}
?>
