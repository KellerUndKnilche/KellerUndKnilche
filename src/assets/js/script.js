let currency = 0;
let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet

document.getElementById("click_button").addEventListener("click", increaseCurrency);

function increaseCurrency() {
    const currentTime = new Date().getTime();
    
    // Prüfen, ob der Benutzer sich in einer Strafzeit befindet
    if (currentTime < penaltyEndTime) {
        const remainingSeconds = Math.ceil((penaltyEndTime - currentTime) / 1000);
        alert(`Bitte warte noch ${remainingSeconds} Sekunden.`);
        return;
    }
    
    // Aktuellen Klick zum Verlauf hinzufügen
    clickHistory.push(currentTime);
    
    // Alte Klicks entfernen (außerhalb des Zeitfensters)
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

function updateCurrencyDisplay() {
    document.getElementById("currency").textContent = currency + " ";
}

function handleAutoClickerDetection() {
    // Verschiedene Stufen von Strafen, je nach Häufigkeit
    if (clickHistory.length > MAX_CLICKS_IN_WINDOW * 2) {
        // Schwerer Verstoß - längere Wartezeit
        penaltyEndTime = new Date().getTime() + 30000; // 30 Sekunden Pause
        alert("Extremer Autoclicker erkannt! Du kannst 30 Sekunden nicht klicken.");
    } else {
        // Leichter Verstoß - kürzere Wartezeit
        penaltyEndTime = new Date().getTime() + 5000; // 5 Sekunden Pause
        alert("Autoclicker erkannt! Du musst 5 Sekunden warten.");
    }
    
    // Klickverlauf zurücksetzen
    clickHistory = [];
}

document.getElementById("user-actions").addEventListener("change", function() {
    var selectedValue = this.value;

    if (selectedValue === "login") {
        console.log("Login selected");
        window.location.href = "login.html";
    }
});

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var errorMessage = document.getElementById('errorMessage');

    if (username === '' || password === '') {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
        // Hier können Sie die Daten an den Server senden
        console.log('Benutzername:', username);
        console.log('Passwort:', password);
    }
});