let clickHistory = []; // Speichert Zeitstempel aller Klicks im Zeitfenster
const TIME_WINDOW_MS = 1000; // 1 Sekunde Zeitfenster
const MAX_CLICKS_IN_WINDOW = 20; // Maximal 20 Klicks pro Sekunde erlaubt
let penaltyEndTime = 0; // Zeitpunkt, zu dem die Strafe endet
let upgrades = [];
let updateInterval; // Variable für die Intervall-ID deklarieren

// Hilfsfunktion zum Formatieren großer Zahlen
function formatNumber(number) {
    number = parseFloat(number);
    
    if (isNaN(number)) {
        return '0.00';
    }
    
    if (number >= 1000) {
        const suffixes = ['', 'K', 'M', 'B', 'T', 'Q', 'Qi', 'Sx', 'Sp', 'Oc', 'No', 'Dc'];
        let suffixIndex = 0;
        
        while (number >= 1000 && suffixIndex < suffixes.length - 1) {
            number /= 1000;
            suffixIndex++;
        }
        
        return number.toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + suffixes[suffixIndex];
    } else {
        return number.toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const clickButton = document.getElementById("click_button");
    if (clickButton) {
        clickButton.addEventListener("click", increaseCurrency);
    }
    ladeUpgrades();

    // Upgrade‑Speicherung (nur wenn eingeloggt)
    setInterval(() => {
        if (window.isUserLoggedIn) {
            saveUpgrades();
        }
    }, 5000);

    // Nur wenn beide Elemente vorhanden sind, Interval starten
    if (currencyElement && productionRateElement) {
        updateInterval = setInterval(() => {
            updateCurrencyDisplay();
        }, 1000);
        
        
        window.addEventListener('unload', () => {
            clearInterval(updateInterval);
        });
    }
});

// Währungs‑Update-Funktion
const apiEndpoint = 'api/update_currency.php';
const currencyElement = document.getElementById('currency');
const productionRateElement = document.getElementById('proSekunde');

async function updateCurrencyDisplay() {
    try {
        const response = await fetch(apiEndpoint);
        if (!response.ok) {
            if (response.status === 401 || response.status === 403 || response.redirected) {
                clearInterval(updateInterval);
                return;
            }
            throw new Error(`HTTP ${response.status}`);
        }
        const data = await response.json();
        if (data.success) {
            currencyElement.textContent = formatNumber(data.newAmount);
            currencyElement.dataset.rawAmount = data.newAmount;
            productionRateElement.textContent = data.productionPerSecond > 0
                ? `(${formatNumber(data.productionPerSecond)} BB/s)`
                : '';
        } else if (data.message === 'Nicht eingeloggt') {
            clearInterval(updateInterval);
        }
    } catch (e) {
        console.error('Fehler beim Abrufen der Währung:', e);
    }
}


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
            // Rohwert speichern und mit formatNumber-Abkürzung anzeigen
            const currencyElement = document.getElementById('currency');
            if (currencyElement) {
                currencyElement.dataset.rawAmount = data.newAmount;
                currencyElement.textContent = formatNumber(data.newAmount);
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

function updateProfileEarningRate() {
    const bbProClickElement = document.getElementById("bb-pro-click");
    const bbProSekundeElement = document.getElementById("bb-pro-sekunde");
    if (bbProClickElement) {
        bbProClickElement.textContent = berechneBBProClick().toFixed(2);
    }
    if (bbProSekundeElement) {
        bbProSekundeElement.textContent = berechnePassivesEinkommen().toFixed(2);
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
        effektText = `+${parseFloat(upg.effektwert)}% für ${upg.ziel_name}`;
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
            effektText = `+${formatNumber(parseFloat(upg.effektwert))}`;
            effektText += '/Klick';
            effektText += upg.level > 0 ? ' ✓' : '';
        } else {
            effektText = `+${formatNumber((parseFloat(upg.effektwert) * getBoostMultiplier(upg.id)).toFixed(2))}`;
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
            div.textContent = `${upg.name} (${effektText}) – ${formatNumber(kalkPreis(upg.basispreis, upg.level))} BB`;
            div.onclick = () => kaufUpgrade(upg.id);
        }

        zielContainer.appendChild(div);
    } else {
        // Aktualisiere den Text, falls das Upgrade bereits existiert
        if (upg.kategorie === 'Produktion') {
            let neuePreisText = `${upg.name} (${effektText}) – ${formatNumber(kalkPreis(upg.basispreis, upg.level))} BB`;
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

    // Container vor Befüllen löschen
    Object.values(kategorien).forEach(container => {
        if (container) container.innerHTML = '';
    });

    upgrades.forEach(upg => {
        const zielContainer = kategorien[upg.kategorie];
        if (!zielContainer) return;
        // Zeige Boost-Upgrades nur, wenn das Basis-Item gekauft wurde
        if (upg.kategorie === 'Boost') {
            const basisItem = upgrades.find(item => item.id === upg.ziel_id);
            if (!basisItem || basisItem.level <= 0) {
                return; // Basis-Item nicht gekauft, Boost nicht anzeigen
            }
        }
        displayChanges(upg, zielContainer);
    });
    updateProfileEarningRate();
}

async function kaufUpgrade(upgradeId) { // Funktion muss async sein für await
    const upgrade = upgrades.find(u => u.id === upgradeId);
    if (!upgrade) {
        console.error("Upgrade nicht im lokalen Array gefunden:", upgradeId);
        return;
    }

    // Preisberechnung clientseitig nur zur Vorabprüfung (optional, aber gut für UX)
    let clientSidePreisCheck = kalkPreis(upgrade.basispreis, upgrade.level);
    const currencyElement = document.getElementById('currency');
    const currentDisplayAmount = parseFloat(currencyElement.dataset.rawAmount) || 0;

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
            updateCurrencyDisplay(); // Entfernt: Wird durch Intervall erledigt
            
            if(upgrade.level == 1 && upgrade.kategorie == 'Produktion') {
                ladeUpgrades();
            }
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

    // Profil-Earning-Tabelle aktualisieren
    updateProfileEarningRate();
}

function kalkPreis(basispreis, level) {
    return Math.round(parseFloat(basispreis) * Math.pow(1.15, level));
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

function berechneBBProClick() {
    let bbProClick = 1;

    upgrades.forEach(upg => {
        if (upg.kategorie === 'Klick' && upg.level > 0) {
            bbProClick += parseFloat(upg.effektwert) * upg.level;
        }
    });

    return bbProClick;
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
