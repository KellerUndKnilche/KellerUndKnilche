// Hilfe-Popup Funktionalität
document.addEventListener("DOMContentLoaded", () => {
    const helpButton = document.getElementById("helpButton");
    const helpOverlay = document.getElementById("helpOverlay");
    const closeHelp = document.getElementById("closeHelp");

    if (helpButton && helpOverlay && closeHelp) {
        // Popup öffnen
        helpButton.addEventListener("click", () => {
            helpOverlay.style.display = "flex";
            document.body.style.overflow = "hidden"; // Scrollen der Seite verhindern
        });

        // Popup schließen über X-Button
        closeHelp.addEventListener("click", () => {
            helpOverlay.style.display = "none";
            document.body.style.overflow = "auto"; // Scrollen wieder erlauben
        });

        // Popup schließen beim Klick auf Overlay (außerhalb des Popups)
        helpOverlay.addEventListener("click", (e) => {
            if (e.target === helpOverlay) {
                helpOverlay.style.display = "none";
                document.body.style.overflow = "auto";
            }
        });

        // Popup schließen mit ESC-Taste
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && helpOverlay.style.display === "flex") {
                helpOverlay.style.display = "none";
                document.body.style.overflow = "auto";
            }
        });
    }
});

// Globale Funktion für Text-Links und andere Auslöser
function toggleHelpOverlay() {
    const helpOverlay = document.getElementById("helpOverlay");
    
    if (helpOverlay) {
        if (helpOverlay.style.display === "flex") {
            // Popup schließen
            helpOverlay.style.display = "none";
            document.body.style.overflow = "auto";
        } else {
            // Popup öffnen
            helpOverlay.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
    }
}
