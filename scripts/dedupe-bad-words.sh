#!/bin/bash
set -euo pipefail

# Pfad zur Bad‑Words‑Liste
FILE="../src/config/filters/bad-words.txt"

# entferne alle Whitespaces pro Zeile
sed -i 's/[[:space:]]//g' "$FILE"

# entferne Zeilen mit Sonderzeichen (alles außer A–Z und a–z)
sed -i '/[^a-zA-Z]/d' "$FILE"

# konvertiere Großbuchstaben zu Kleinbuchstaben
sed -i 'y/ABCDEFGHIJKLMNOPQRSTUVWXYZ/abcdefghijklmnopqrstuvwxyz/' "$FILE"

# sortiert alphabetisch, entfernt Duplikate und schreibt zurück
sort --unique "$FILE" -o "$FILE"

# verwenden mit `chmod +x dedupe-bad-words.sh` dann `./dedupe-bad-words.sh`