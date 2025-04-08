// Wechseln von Schriftarten
function changeFont(fontClass) {
    const body = document.body;

    // Bestehende Schriftarten entfernen
    body.classList.remove("font-medieval", "font-uncial", "font-jacquard", "font-system");

    // Ausgewählte Schriftart hinzufügen
    body.classList.add(fontClass);

    // Schriftart in localStorage speichern
    localStorage.setItem("selectedFont", fontClass);
}

// Schriftart beim Laden der Seite anwenden
document.addEventListener("DOMContentLoaded", () => {
    const savedFont = localStorage.getItem("selectedFont");
    if (savedFont) {
        document.body.classList.add(savedFont);
    } else {
        document.body.classList.add("font-medieval");
    }
});


