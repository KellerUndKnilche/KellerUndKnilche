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
        <img id="click_button" class="gameButton" src="/assets/img/gamearea_platzhalter.png" alt="Keller-Knopf"/>
    </section>
    
    
    <!-- Toggle Button fuer Side Panels -->
    <button id="toggle-side-panels" class="btn btn-primary d-lg-none">☰</button>
    
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

        <!-- Upgrades Panel -->
        <div class="side-panel">
            <h2>Aufrüstungen</h2>

            <h3>Passive Produktion</h3>
            <div id="produktion-upgrades" class="upgrades-list"></div>

            <h3>Verstärker</h3>
            <div id="boost-upgrades" class="upgrades-list"></div>

            <h3>Klick-Verstärker</h3>
            <div id="klick-upgrades" class="upgrades-list"></div>
        </div>
</main>
<?php require_once('includes/footer.php'); ?>