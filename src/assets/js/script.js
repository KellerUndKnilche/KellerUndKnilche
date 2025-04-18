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

    console.log(upgrades);

    const kategorien = {
        'Produktion': document.getElementById('produktion-upgrades'),
        'Boost': document.getElementById('boost-upgrades'),
        'Klick': document.getElementById('klick-upgrades')
    };
    
    upgrades.forEach(upg => {
        const zielContainer = kategorien[upg.kategorie];
        if (!zielContainer) return;

        const div = document.createElement('div');

        let effektText = "";
        if (upg.effektart === 'prozent') {
            effektText = `+${parseFloat(upg.effektwert)}%`;
        } else {
            effektText = `+${parseFloat(upg.effektwert)}`;
            if (upg.kategorie === 'Klick') {
                effektText += '/Klick';
                effektText += upg.level = 1 ? ' ✓' : '';
            } else {
                effektText += ` BB/s Level ${upg.level}`;
            }
        }

        div.textContent = `${upg.name} (${effektText}) – ${upg.basispreis} BB`;
        div.dataset.upgradeId = upg.id;
        div.onclick = () => kaufUpgrade(upg.id, 0);
        zielContainer.appendChild(div);
    });
}

function kaufUpgrade(upgradeId, upgradeLevel) {
    const upgradeDiv = document.querySelector(`[data-upgrade-id="${upgradeId}"]`);
    const upgradeArrayId = upgradeId - 1; // IDs in der DB beginnen bei 1, Arrays bei 0
    let upgradePreisLevel = kalkPreis(upgrades[upgradeArrayId].basispreis, upgradeLevel, upgradeId);

    if (upgradePreisLevel > currency) {
        alert("Nicht genug BB für dieses Upgrade!");
        return;
    }

    currency -= upgradePreisLevel;
    updateCurrencyDisplay();

    if (upgradeDiv && !upgradeDiv.classList.contains('gekauft') && upgrades[upgradeArrayId].kategorie != 'Produktion') {
        upgradeDiv.classList.add('gekauft');
    } else if (upgradeDiv && upgrades[upgradeArrayId].kategorie == 'Produktion') {
        let effektText = `+${parseFloat(upgrades[upgradeArrayId].effektwert)} BB/s`;
        // Preis-Update
        upgradeDiv.textContent = `${upgrades[upgradeArrayId].name} (${effektText}) – ${kalkPreis(upgrades[upgradeArrayId].basispreis, upgradeLevel+1, upgradeId)} BB`;
    } 
}

function kalkPreis(basispreis, level, id) {
    return parseFloat(basispreis) + parseFloat(Math.pow(id, 3) * level);
}