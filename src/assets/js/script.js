let currency = 0;
let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet
let upgrades = [];

document.addEventListener("DOMContentLoaded", () => {
    const clickButton = document.getElementById("click_button");
    if (clickButton) {
        clickButton.addEventListener("click", increaseCurrency);
    }
    ladeUpgrades();
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
    currency += 2;
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


// Funktion zum Anzeigen der Änderungen der Upgrades
function displayChanges(upg, zielContainer) {
    let effektText = "";
    if (upg.effektart === 'prozent') {
        effektText = `+${parseFloat(upg.effektwert)}%`;
        effektText += upg.level === 1 ? ' ✓' : '';
    } else {
        effektText = `+${parseFloat(upg.effektwert)}`;
        if (upg.kategorie === 'Klick') {
            effektText += '/Klick';
            effektText += upg.level > 0 ? ' ✓' : '';
        } else {
            effektText += ` BB/s Level ${upg.level}`;
        }
    }

    const upgradeDiv = document.querySelector(`[data-upgrade-id="${upg.id}"]`);

    if (!upgradeDiv) {
        // Erstelle das div für das Upgrade, falls es noch nicht existiert
        const div = document.createElement('div');
        div.textContent = `${upg.name} (${effektText}) – ${upg.basispreis} BB`;
        div.dataset.upgradeId = upg.id;
        div.onclick = () => kaufUpgrade(upg.id);  // Kein Level-Parameter notwendig
        zielContainer.appendChild(div);
    } else {
        // Aktualisiere den Text, falls das Upgrade bereits existiert
        if (upg.kategorie === 'Produktion') {
            let neuePreisText = `${upg.name} (${effektText}) – ${kalkPreis(upg.basispreis, upg.level, upg.id)} BB`;
            upgradeDiv.textContent = neuePreisText;
        } else if (!upgradeDiv.classList.contains('gekauft')) {
            upgradeDiv.classList.add('gekauft');
            upgradeDiv.textContent = `${upg.name} (${effektText})`;
        }
    }
}

async function ladeUpgrades() {
    const res = await fetch('../../content/game/upgrades.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'getUpgrades'
        })
    });
    upgrades = await res.json();

    const kategorien = {
        'Produktion': document.getElementById('produktion-upgrades'),
        'Boost': document.getElementById('boost-upgrades'),
        'Klick': document.getElementById('klick-upgrades')
    };
    
    upgrades.forEach(upg => {
        const zielContainer = kategorien[upg.kategorie];
        if (!zielContainer) return;

        // Rufe die displayChanges-Funktion auf, um das Upgrade anzuzeigen
        displayChanges(upg, zielContainer);
    });
}

function kaufUpgrade(upgradeId) {
    const upgradeArrayId = upgradeId - 1; // IDs in der DB beginnen bei 1, Arrays bei 0
    let upgradePreisLevel = kalkPreis(upgrades[upgradeArrayId].basispreis, upgrades[upgradeArrayId].level, upgradeId);
    
    if (upgradePreisLevel > currency) {
        alert("Nicht genug BB für dieses Upgrade!");
        return;
    }
    
    currency -= upgradePreisLevel;
    updateCurrencyDisplay();
    
    upgrades[upgradeArrayId].level += 1;

    // Stelle sicher, dass beim Kauf die Anzeige des Upgrades aktualisiert wird
    displayChanges(upgrades[upgradeArrayId], document.getElementById(`${upgrades[upgradeArrayId].kategorie.toLowerCase()}-upgrades`));
}


function kalkPreis(basispreis, level, id) {
    if (level == 0) {
        return parseFloat(basispreis);
    }
    return parseFloat(basispreis) + parseFloat(Math.pow(id, 3) * level);
}