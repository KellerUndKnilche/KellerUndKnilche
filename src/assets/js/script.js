let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet
let upgrades = [];
let updateInterval; // Variable für die Intervall-ID deklarieren

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
    updateInterval = setInterval(() => {
        updateCurrencyDisplay();
    }, 1000); // Alle 1 Sekunde aktualisieren und ID speichern

    const currencyElement = document.getElementById('currency');
    const productionRateElement = document.getElementById('proSekunde');
    const apiEndpoint = 'api/update_currency.php'; // Pfad zum API-Skript RELATIV zu index.php

    if (!currencyElement || !productionRateElement) {
        console.error('Fehler: Währungs- oder Ratenanzeige-Element nicht gefunden.');
        return;
    }

    // Funktion zum Abrufen und Aktualisieren der Währung und Rate
    async function updateCurrencyDisplay() {
        try {
            const response = await fetch(apiEndpoint);
            if (!response.ok) {
                // Wenn Benutzer nicht eingeloggt ist (z.B. 401 oder Redirect), stoppe das Intervall
                if (response.status === 401 || response.status === 403 || response.redirected) {
                    console.warn('Benutzer nicht eingeloggt oder Zugriff verweigert. Stoppe Währungsupdate.');
                    clearInterval(updateInterval);
                    return; // Beende die Funktion hier
                }
                throw new Error(`HTTP Fehler! Status: ${response.status}`);
            }
            const data = await response.json();

            if (data.success) {
                // Formatiere die Zahlen für die Anzeige (z.B. mit 2 Dezimalstellen)
                const formattedAmount = parseFloat(data.newAmount).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const formattedRate = parseFloat(data.productionPerSecond).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                currencyElement.textContent = formattedAmount;
                // Füge BB/s hinzu, wenn die Rate größer als 0 ist
                productionRateElement.textContent = data.productionPerSecond > 0 ? `(${formattedRate} BB/s)` : '';
            } else {
                console.error('API Fehler:', data.message);
                 // Stoppe bei bestimmten Fehlern, z.B. wenn nicht eingeloggt
                 if (data.message === 'Nicht eingeloggt') {
                     console.warn('Nicht eingeloggt laut API. Stoppe Währungsupdate.');
                     clearInterval(updateInterval);
                 }
            }
        } catch (error) {
            console.error('Fehler beim Abrufen der Währungsdaten:', error);
            // Stoppe das Intervall bei Netzwerkfehlern o.ä., um die Konsole nicht zu fluten
            // clearInterval(updateInterval);
        }
    }

    // Rufe die Funktion nicht sofort auf, da PHP den initialen Stand rendert.
    // Der erste API-Call nach 1 Sekunde reicht.

    // Optional: Intervall stoppen, wenn die Seite verlassen wird (good practice)
     window.addEventListener('unload', () => {
         clearInterval(updateInterval);
     });
});

// Speichert die Upgrades bevor die Seite geschlossen wird
window.addEventListener("beforeunload", () => {
    if (window.isUserLoggedIn) {
        const payload = JSON.stringify({
            action: 'saveUpgrades',
            upgrades: upgrades.map(u => ({ id: u.id, level: u.level }))
        });
        navigator.sendBeacon('../../content/game/upgrades.php', payload);  // sendBeacon für asynchrone Übertragung
    }
});

// Währung erhöhen
async function increaseCurrency() { 
    const now = new Date().getTime();

    // 1. Prüfen, ob eine Strafe aktiv ist
    if (now < penaltyEndTime) {
        const remainingTime = Math.ceil((penaltyEndTime - now) / 1000);
        alert(`Du musst noch ${remainingTime} Sekunden warten.`);
        return; // Klick verhindern
    }

    // 2. Alte Zeitstempel entfernen (älter als TIME_WINDOW_MS)
    clickHistory = clickHistory.filter(timestamp => now - timestamp < TIME_WINDOW_MS);

    // 3. Aktuellen Zeitstempel hinzufügen
    clickHistory.push(now);

    // 4. Prüfen, ob das Klicklimit überschritten wurde
    if (clickHistory.length > MAX_CLICKS_IN_WINDOW) {
        // Strafe auslösen
        penaltyEndTime = now + 5000; // 5 Sekunden Pause
        alert("Autoclicker erkannt! Du musst 5 Sekunden warten.");
        clickHistory = []; // Historie zurücksetzen nach Erkennung
        return; // Klick verhindern
    }

    // 5. Wenn keine Strafe und Limit nicht überschritten, Klick registrieren
    try {
        const response = await fetch('api/register_click.php', {
            method: 'POST' // POST ist besser, da es den Serverstatus ändert
        });
        if (!response.ok) {
            // Fehlerbehandlung, wenn Benutzer nicht eingeloggt ist o.ä.
            if (response.status === 401 || response.status === 403) {
                 console.warn('Klick nicht registriert: Nicht eingeloggt oder Zugriff verweigert.');
                 // Hier könnte man ggf. das Update-Intervall stoppen, falls noch nicht geschehen
                 // clearInterval(updateInterval);
            } else {
                throw new Error(`HTTP Fehler beim Klicken! Status: ${response.status}`);
            }
            return; // Beende Funktion bei Fehler
        }
        const data = await response.json();

        if (data.success) {
            // Anzeige direkt mit dem neuen Betrag vom Server aktualisieren
            const formattedAmount = parseFloat(data.newAmount).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const currencyElement = document.getElementById('currency');
            if (currencyElement) {
                currencyElement.textContent = formattedAmount;
            }
        } else {
            console.error('API Fehler beim Klicken:', data.message);
        }
    } catch (error) {
        console.error('Fehler beim Senden des Klicks:', error);
    }
}

// Funktion zum Berechnen des Boost-Multiplikators für ein bestimmtes Upgrade
function getBoostMultiplier(targetUpgradeId) {
    let multiplier = 1.0; // Startmultiplikator

    upgrades.forEach(boostUpg => {
        // Prüfe, ob es ein Boost-Upgrade ist, das das Ziel-Upgrade beeinflusst und gekauft wurde (Level > 0)
        if (boostUpg.kategorie === 'Boost' && boostUpg.ziel_id === targetUpgradeId && boostUpg.level > 0) {
            if (boostUpg.effektart === 'prozent') {
                // Addiere den prozentualen Boost zum Multiplikator
                // Annahme: Jeder Level gibt den vollen Effektwert
                const totalBoostPercent = parseFloat(boostUpg.effektwert) * boostUpg.level;
                multiplier *= (1 + (totalBoostPercent / 100.0));
            }
            // Hier könnten 'absolut' Boosts behandelt werden, falls nötig
        }
    });

    return multiplier;
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
    // Korrigierter Pfad: relativ zu src/index.php
    const res = await fetch('content/game/upgrades.php', {
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
    });
}

async function kaufUpgrade(upgradeId) { // Funktion muss async sein für await
    const upgrade = upgrades.find(u => u.id === upgradeId);
    if (!upgrade) {
        console.error("Upgrade nicht im lokalen Array gefunden:", upgradeId);
        return;
    }

    // Preisberechnung clientseitig nur zur Vorabprüfung (optional, aber gut für UX)
    let clientSidePreisCheck = kalkPreis(upgrade.basispreis, upgrade.level, upgrade.id);
    const currencyElement = document.getElementById('currency');
    const currentDisplayAmount = parseFloat(currencyElement.textContent.replace(/\./g, '').replace(/,/, '.')) || 0;

    if (clientSidePreisCheck > currentDisplayAmount) { 
        alert("Nicht genug BB für dieses Upgrade! (Client-Check)");
        return;
    }
    
    // Entferne die lokale Level-Erhöhung - das macht der Server
    // upgrades[upgradeArrayId].level += 1;

    // Sende Kaufanfrage an den Server
    try {
        const res = await fetch('content/game/upgrades.php', { // Korrekter Pfad relativ zu index.php
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'buyUpgrade',
                upgradeId: upgradeId
            })
        });
        const result = await res.json();

        if (result.success) {
            // Upgrade war erfolgreich!
            // 1. Aktualisiere das Level im lokalen upgrades-Array
            upgrade.level = result.newLevel; 

            // 2. Aktualisiere die Anzeige für dieses Upgrade
            const containerId = `${upgrade.kategorie.toLowerCase()}-upgrades`;
            const containerElement = document.getElementById(containerId);
            if (containerElement) {
                displayChanges(upgrade, containerElement);
            }

            // 3. Aktualisiere die Währungsanzeige sofort (optional, Intervall macht es auch)
            // updateCurrencyDisplay(); // Entfernt: Wird durch Intervall erledigt
            
            // Optional: Erfolgsmeldung
            // console.log("Upgrade gekauft:", upgrade.name, "Neues Level:", result.newLevel);

        } else {
            // Kauf fehlgeschlagen (z.B. nicht genug Geld serverseitig, DB-Fehler)
            alert(`Kauf fehlgeschlagen: ${result.message}`);
        }

    } catch (error) {
        console.error("Fehler beim Senden der Kaufanfrage:", error);
        alert("Ein Netzwerkfehler ist beim Kauf aufgetreten.");
    }
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
                upgrades: upgrades.map(u => ({ id: u.id, level: u.level }))
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