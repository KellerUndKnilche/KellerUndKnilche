<?php 
$pageTitle = 'Keller & Knilche Homepage';
require_once('config/dbAccess.php');
require_once('includes/header.php'); 
require_once('includes/nav.php'); 
?>
<header class="d-flex flex-column justify-content-center align-items-center text-center">
    <h1 class="title display-1">Keller & Knilche</h1>
</header>
<main class="container">
    
    <stion class="game-area">
        <img id="click_button" src="/assets/img/gamearea_platzhalter.png" alt="Dungeon">
    </stion>
    
    <!-- Toggle Button fuer Side Panels -->
    <button id="toggle-side-panels" class="btn btn-primary d-lg-none">☰</button>
    
    <div id="side-panels" class="d-lg-flex flex-lg-column mt-4">
        <div id="currency-display" class="currency-display">
            <span id="currency-label">Beute Batzen: </span>
            <span id="currency">0</span>
            <span id="currency-label"> BB</span>
        </div>
        <div class="side-panel mb-3">
            <h2>Stats</h2>
            <div class="stat-display mt-3 mb-3">
                <div class="card mb-2">
                    <div class="card-body">
                        <span id="stat-label">Helden besiegt: </span>
                        <span id="stat">0</span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <span id="stat-label">Fallen ausgelöst: </span>
                        <span id="stat">0</span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <span id="stat-label">Schaden: </span>
                        <span id="stat">0</span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <span id="stat-label">Monster rekrutiert: </span>
                        <span id="stat">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upgrades Panel -->
        <div class="side-panel">
            <h2>Aufrüstungen</h2>

            <h3>Passive Produktion</h3>
            <div class="upgrades-list">
                <div>🦴 Gerippe (1 BB/s) – 15 BB</div>
                <div>🧟 Untoter (2 BB/s) – 50 BB</div>
                <div>🦇 Fledermausschwarm (5 BB/s) – 150 BB</div>
                <div>👻 Geistererscheinung (10 BB/s) – 400 BB</div>
                <div>😈 Dämon aus der Mittagspause (20 BB/s) – 1000 BB</div>
            </div>

            <h3>Verstärker</h3>
            <div class="upgrades-list">
                <div>🏋️‍♂️ Knochentraining +10% – 100 BB</div>
                <div>🏋️‍♂️ Knochentraining +15% – 250 BB</div>
                <div>🏋️‍♂️ Knochentraining +20% – 500 BB</div>

                <div>🧠 Untoten-Schreitherapie +10% – 150 BB</div>
                <div>🧠 Untoten-Schreitherapie +15% – 300 BB</div>
                <div>🧠 Untoten-Schreitherapie +20% – 600 BB</div>

                <div>🔊 Fledermaus-Chorprobe +10% – 200 BB</div>
                <div>🔊 Fledermaus-Chorprobe +15% – 400 BB</div>
                <div>🔊 Fledermaus-Chorprobe +20% – 800 BB</div>

                <div>🌀 Geisterrausch +10% – 300 BB</div>
                <div>🌀 Geisterrausch +15% – 600 BB</div>
                <div>🌀 Geisterrausch +20% – 1000 BB</div>

                <div>📜 Dämonenvertrag (nicht kleingedruckt lesen) +10% – 500 BB</div>
                <div>📜 Dämonenvertrag +15% – 1000 BB</div>
                <div>📜 Dämonenvertrag +20% – 2000 BB</div>
            </div>

            <h3>Klick-Verstärker</h3>
            <div class="upgrades-list">
                <div>☝️ Muskelkater-Finger (+5/Klick) – 50 BB</div>
                <div>🦾 Nekro-Handschuh (+5/Klick) – 150 BB</div>
                <div>🧤 Greifarm aus dem Jenseits (+5/Klick) – 300 BB</div>
                <div>🔥 Finger des Verderbens (+5/Klick) – 600 BB</div>
                <div>✨ Magischer Doppelklick (+5/Klick) – 1200 BB</div>
            </div>
        </div>
</main>
<?php require_once('includes/footer.php'); ?>
<script src="./assets/js/sidePanels.js"></script>
<script src="./assets/js/script.js"></script>