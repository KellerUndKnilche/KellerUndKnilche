<?php 
$pageTitle       = 'Keller & Knilche - Gewinne maximieren, Helden minimieren';
$pageDescription = 'Werde zum finsteren Verliesboss, trotze lästigen Helden (den Knilchen) und sammle Batzen so viel, dass Drachen neidisch werden.';

require_once('config/dbAccess.php');
require_once('includes/header.php'); 
require_once('includes/nav.php'); 
?>
<header class="d-flex flex-column justify-content-center align-items-center text-center">
    <h1 class="title display-1">Keller & Knilche</h1>
</header>
<main class="container">
    
    <?php if (isset($_SESSION['user'])): ?>
    <section class="game-area">
        <h2 class="visually-hidden">Spielbereich</h2>
        <!-- Währungsanzeige direkt in der game-area -->
        <!--
        <div id="currency-display" class="currency-display">
            <span id="currency-label">Beute Batzen: </span>
            <span id="currency">0.00</span>
            <span id="proSekunde"></span>
        </div>
        -->
        <img id="click_button" class="gameButton" src="/assets/img/dungeon.png" alt="Keller-Knopf" draggable="false" ondragstart="return false;"/>
    </section>
    <?php endif; ?>

    <!-- Toggle Button fuer Side Panels -->
    <button id="toggle-side-panels" class="btn btn-primary d-lg-none">☰</button>
    
    <?php if (!isset($_SESSION['user'])): ?>
    <!-- Landing Page / Instructions falls nicht eingeloggt - außerhalb der Side Panels -->
    <div class="welcome-panel mt-4">
        <h2>Willkommen, Möchtegern - Kellermeister!</h2>
        <p>In <strong>Keller & Knilche</strong> verwandelst du ein stinkendes Verlies in eine goldsprudelnde Monsterfabrik. Klicke auf den dicken Knopf, verdiene Batzen und zeig diesen selbstgerechten Knilchen, wo der Gruftstaub liegt!</p>
        <p>Erstelle dir einen Account, um deinen düsteren Fortschritt zu sichern und geheime Schattenfunktionen freizuschalten!</p>
        <p><em>Neu hier? Klicke auf den <strong>❓ Hilfe</strong> Button in der Navigation oder <a href="javascript:void(0);" onclick="toggleHelpOverlay()">hier</a> für eine erste Geisterführung.</em></p>
        <a href="/registrierung" class="btn btn-primary welcomeButton">Zum Kellerpakt – Jetzt registrieren</a>
        <a href="/login" class="btn btn-secondary welcomeButton">Rückkehr in den Keller – Anmelden</a>
    </div>
    <?php endif; ?>
    
    <div id="side-panels" class="d-lg-flex flex-lg-column mt-4">
        <!--
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
    -->
        <?php if (isset($_SESSION['user'])): ?>
        <!-- Upgrades Panel -->
        <div class="side-panel">
            <h2>Aufrüstungen</h2>

            <!-- Toggle Button für gekaufte Ein-Level-Upgrades -->
            <div class="filter-controls mb-3">
                <button id="toggle-purchased-upgrades" class="btn btn-sm btn-outline-secondary">
                    Gekaufte ausblenden
                </button>
            </div>

            <h3>Passive Produktion</h3>
            <div id="produktion-upgrades" class="upgrades-list"></div>

            <h3>Verstärker</h3>
            <div id="boost-upgrades" class="upgrades-list"></div>

            <h3>Klick-Verstärker</h3>
            <div id="klick-upgrades" class="upgrades-list"></div>
        </div>
        <?php endif; ?>
    </div>
</main>
<?php require_once('includes/footer.php'); ?>