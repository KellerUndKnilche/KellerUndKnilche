let currency = 0;
let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet

document.addEventListener("DOMContentLoaded", () => {
    const clickButton = document.getElementById("click_button");
    if (clickButton) {
        clickButton.addEventListener("click", increaseCurrency);
    }
});

// Währung erhöhen
function increaseCurrency() {
    const currentTime = new Date().getTime();

    if (currentTime < penaltyEndTime) {
        const remainingSeconds = Math.ceil((penaltyEndTime - currentTime) / 1000);
        alert(`Bitte warte noch ${remainingSeconds} Sekunden.`);
        return;
    }

    clickHistory.push(currentTime);

    while (clickHistory.length > 0 && clickHistory[0] < currentTime - TIME_WINDOW_MS) {
        clickHistory.shift();
    }

    // Prüfen, ob zu viele Klicks im Zeitfenster
    if (clickHistory.length > MAX_CLICKS_IN_WINDOW) {
        handleAutoClickerDetection();
        return;
    }

    // Währung erhöhen, wenn kein Autoclicker erkannt wurde
    currency += 10;
    updateCurrencyDisplay();
}

// Währung im HTML aktualisieren
function updateCurrencyDisplay() {
    const currencyElement = document.getElementById("currency");
    if (currencyElement) {
        currencyElement.textContent = currency + " ";
    }
}

// Autoclicker-Erkennung behandeln
function handleAutoClickerDetection() {
    // Verschiedene Stufen von Strafen, je nach Häufigkeit
    if (clickHistory.length > MAX_CLICKS_IN_WINDOW * 2) {
        // Schwerer Verstoß - längere Wartezeit
        penaltyEndTime = new Date().getTime() + 30000; // 30 Sekunden Pause
        alert("Extremer Autoclicker erkannt! Du kannst 30 Sekunden nicht klicken.");
    } else {
        // Normaler Verstoß - kurze Wartezeit
        penaltyEndTime = new Date().getTime() + 5000; // 5 Sekunden Pause
        alert("Autoclicker erkannt! Du musst 5 Sekunden warten.");
    }
    // Klick-Historie zurücksetzen
    clickHistory = [];
}
