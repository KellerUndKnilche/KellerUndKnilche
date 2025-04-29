let currency = 0.00;
let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet
let upgrades = [];
let klickValue = 1; // Wert pro Klick

document.addEventListener("DOMContentLoaded", () => {
    const clickButton = document.getElementById("click_button");
    if (clickButton) {
        clickButton.addEventListener("click", increaseCurrency);
    }
    ladeUpgrades();
    setInterval(() => {
        if (window.isUserLoggedIn) {
            saveUpgrades();
        }
    }, 5000);
    setInterval(() => {
        currency = Number(currency) + parseFloat(berechnePassivesEinkommen());
        currency = Number(currency).toFixed(2);  // Runden auf 2 Dezimalstellen
        updateCurrencyDisplay();
    }, 1000); // Alle 1 Sekunde aktualisieren

});

// Speichert die Upgrades bevor die Seite geschlossen wird
window.addEventListener("beforeunload", () => {
    if (!window.isUserLoggedIn) return;
    const payload = JSON.stringify({
        action: 'saveUpgrades',
        upgrades: upgrades.map(u => ({ id: u.id, level: u.level }))
    });
    navigator.sendBeacon('../../content/game/upgrades.php', payload);  // statt fetch
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
    currency = Number(currency); // Sicherstellen, dass currency als Zahl behandelt wird.
    currency += klickValue;
    updateCurrencyDisplay();
}

// Währung im HTML aktualisieren
function updateCurrencyDisplay() {
    const currencyElement = document.getElementById("currency");
    if (currencyElement) {
        // Umwandlung in den numerischen Wert und Formatierung auf zwei Nachkommastellen.
        currencyElement.textContent = Number(currency).toFixed(2) + " ";
    }
}

function updateProSekundeDisplay() {
    const proSekundeElement = document.getElementById("proSekunde");
    if (proSekundeElement) {
        const passivesEinkommen = berechnePassivesEinkommen();
        proSekundeElement.textContent = ` (+${passivesEinkommen.toFixed(2)} BB/s)`;
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

        // Rekursiv auf das Ziel-Upgrade anwenden, falls vorhanden
        if (upg.ziel_id) {
            const targetUpgrade = upgrades.find(u => u.id === upg.ziel_id);
            if (targetUpgrade) {
                // Rufe displayChanges für das Ziel-Upgrade auf
                displayChanges(targetUpgrade, zielContainer);
            }
        }
    } else {
        if (upg.kategorie === 'Klick') {
            effektText = `+${parseFloat(upg.effektwert)}`;
            effektText += '/Klick';
            effektText += upg.level > 0 ? ' ✓' : '';
        } else {
            effektText = `+${(parseFloat(upg.effektwert) * getBoostMultiplier(upg.id)).toFixed(2)}`;
            effektText += ` BB/s Level ${upg.level}`;
            updateProSekundeDisplay();
        }
    }

    const upgradeDiv = document.querySelector(`[data-upgrade-id="${upg.id}"]`);

    if (!upgradeDiv) {
        const div = document.createElement('div');
        div.dataset.upgradeId = upg.id;

        // Inhalt je nach Kaufstatus
        if ((upg.kategorie == 'Klick' || upg.kategorie == 'Boost') && upg.level > 0) {
            div.classList.add('gekauft');
            div.textContent = `${upg.name} (${effektText})`;
        } else {
            div.textContent = `${upg.name} (${effektText}) – ${kalkPreis(upg.basispreis, upg.level, upg.id)} BB`;
            div.onclick = () => kaufUpgrade(upg.id);
        }

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
        displayChanges(upg, zielContainer);
        if (upg.kategorie === 'Klick' && upg.level > 0) {
            klickValue += parseFloat(upg.effektwert) * upg.level;
        }
    });
}

function kaufUpgrade(upgradeId) {
    const upgradeArrayId = upgradeId - 1; // IDs in der DB beginnen bei 1, Arrays bei 0
    let upgradePreisLevel = kalkPreis(upgrades[upgradeArrayId].basispreis, upgrades[upgradeArrayId].level, upgradeId);
    
    if (upgradePreisLevel > currency) {
        console.log(upgradePreisLevel, currency);
        alert("Nicht genug BB für dieses Upgrade!");
        return;
    }
    
    currency -= upgradePreisLevel;
    currency = parseFloat(currency).toFixed(2); // Runden auf 2 Dezimalstellen
    updateCurrencyDisplay();
    
    upgrades[upgradeArrayId].level += 1;

    if(upgrades[upgradeArrayId].kategorie === 'Klick') {
        klickValue += parseFloat(upgrades[upgradeArrayId].effektwert);
    }

    // Stelle sicher, dass beim Kauf die Anzeige des Upgrades aktualisiert wird
    displayChanges(upgrades[upgradeArrayId], document.getElementById(`${upgrades[upgradeArrayId].kategorie.toLowerCase()}-upgrades`));
}

function kalkPreis(basispreis, level, id) {
    return parseFloat(basispreis) + parseFloat(Math.pow(id, 3) * level);
}

async function saveUpgrades() {
    if (!window.isUserLoggedIn) return;
    try {
        const res = await fetch('../../content/game/upgrades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'saveUpgrades',
                upgrades: upgrades.map(u => ({ 
                    id: u.id, 
                    level: u.level,
                    isActive: u.isActive === undefined ? 1 : u.isActive 
                }))
            })
        });
        const data = await res.json();
        if (!data.success) {
            console.error("Fehler beim Speichern der Upgrades:", data.error);
        }
    } catch (error) {
        // Abgebrochene Requests (NS_BINDING_ABORTED) oder andere Fehler ignorieren
        console.warn('saveUpgrades abgebrochen oder fehlerhaft:', error);
    }
}

function berechnePassivesEinkommen() {
    let bbProSekunde = 0;

    upgrades.forEach(upg => {
        if (upg.kategorie === 'Produktion' && upg.level > 0) {
            const basisWert = parseFloat(upg.effektwert);
            const boost = getBoostMultiplier(upg.id); // Boosts beziehen sich auf dieses Upgrade
            const einkommen = basisWert * upg.level * boost;

            bbProSekunde += einkommen;
        }
    });

    return bbProSekunde; // auf 2 Dezimalstellen runden
}

function getBoostMultiplier(produktId) {
    let multiplier = 1;

    upgrades.forEach(upg => {
        if (
            upg.kategorie === 'Boost' &&
            upg.level > 0 &&
            upg.effektart === 'prozent' &&
            upg.ziel_id == produktId
        ) {
            multiplier *= 1 + (parseFloat(upg.effektwert) / 100);
        }
    });

    return multiplier;
}