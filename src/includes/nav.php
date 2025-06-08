<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Primary navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Keller &amp; Knilche</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Hauptseiten -->
        <li class="nav-item">
          <a class="nav-link" href="/">Spiel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/statistiken">Statistiken</a>
        </li>
        <?php if (isset($_SESSION['user'])) { 
          if ($_SESSION['user']['isAdmin'] == 1) { ?>
            <li class="nav-item">
              <a class="nav-link" href="/admin">Admin-Armaturenbrett</a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="/profil">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="/registrierung">Registrierung</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/login">Login</a>
          </li>
        <?php } ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item flex-grow-0 flex-shrink-0">
          <button class="btn btn-outline-info" type="button" id="helpButton">
            ❓ Hilfe
          </button>
        </li>
                <li class="nav-item flex-grow-0 flex-shrink-0">
          <a class="nav-link" href="https://github.com/KellerUndKnilche/KellerUndKnilche" target="_blank" rel="noopener noreferrer">
            <img src="../assets/img/github-light.webp" 
                 alt="GitHub Repo" 
                 width="24" 
                 height="24" 
                 class="align-text-bottom me-1">
            Weitere Infos oder Probleme?
          </a>
        </li>
        <li class="nav-item ms-lg-auto flex-grow-0 flex-shrink-0">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="fontDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Schriftart ändern
              </button>
              <ul class="dropdown-menu" aria-labelledby="fontDropdown">
                <li><a class="dropdown-item" href="#" onclick="changeFont('font-system')">System Standard</a></li>
                <li><a class="dropdown-item" href="#" onclick="changeFont('font-medieval')">Medieval Sharp</a></li>
                <li><a class="dropdown-item" href="#" onclick="changeFont('font-uncial')">Uncial Antiqua</a></li>
                <li><a class="dropdown-item" href="#" onclick="changeFont('font-jacquard')">Jacquard 12</a></li>
              </ul>
            </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hilfe Popup -->
<div id="helpOverlay" class="help-overlay" style="display: none;">
  <div class="help-popup">
    <div class="help-header">
      <h3>Willkommen in den Tiefen von Keller & Knilche!</h3>
      <button id="closeHelp" class="help-close">&times;</button>
    </div>
    <div class="help-content">
      <div class="help-section">
        <h4>🔴 Klick dich reich:</h4>
        <p><strong>Klicke auf das Kellerbild</strong>, um Batzen (unsere Währung) zu scheffeln. Jeder beherzte Klick bringt dir Beute – ein echter Held würd’s nicht tun, aber du bist ja der Kellermeister!</p>
      </div>
      
      <div class="help-section">
        <h4>📈 Untote investieren:</h4>
        <p><strong>Automatische Produktion:</strong> Stelle düstere Kreaturen wie Gerippe oder Dämonen an – sie verdienen auch Batzen, wenn du schläfst (solange diese Keller & Knilche offen ist).</p>
        <p><strong>Verflucht guter Klick:</strong> Kauf dir Knochenverstärker und Nekro-Finger, um mit jedem Klick mehr Gold aus deinem Kellerboden zu stampfen.</p>
      </div>

      <div class="help-section">
        <h4>💀 Verbessere dich zum Obermotz:</h4>
        <p><strong>Passive Produktion:</strong> Deine Grundknechte wie Gerippe & Co schuften brav im Hintergrund – einmal kaufen, dauerhafte Beute.</p>
        <p><strong>Verstärker:</strong> Sobald du eine Basis-Aufrüstung hast, kannst du es mit schaurigen Boni verstärken. Die tauchen magisch erst auf, wenn die Basis-Aufrüstung gekauft wurde (außerdem im passenden Farbgewand).</p>
        <p><strong>Klick-Verstärker:</strong> Für alle, die ihre Maus ruinieren wollen – jeder Klick bringt mehr Batzen, je besser dein Fingerwerkzeug.</p>
        <p>Nutze die Knöpfe über der Liste, um z. B. nur leistbare Upgrades zu zeigen oder bereits gekaufte aus dem Sichtfeld zu verbannen. Wer braucht schon Altlasten im Keller?</p>
      </div>
      
      <div class="help-section">
        <h4>👑 Fortschritt & Aufstieg:</h4>
        <p><strong>Statistiken:</strong> Verfolge deine Dominanz über die Heldenwelt in den Statistiken. Wer hat Angst vor deinem Totentanz?</p>
        <p><strong>Ränge & Profil:</strong> Jedes Upgrade bringt dich einem neuen Rang näher – vom „Kellerwäscher“ bis zum „CEO des Kellers“.</p>
      </div>
      
      <div class="help-section">
        <h4>🕯️ Keller-Meister-Tipp:</h4>
        <p>Investiere zuerst in günstige automatische Upgrades – damit du auch Gold verdienst, wenn du gerade nicht malst, zockst oder die Menschheit ignorierst.</p>
      </div>
      
      <div class="help-section">
        <h4>📜 Noch Fragen?</h4>
        <p>Ein echter Keller fragt nicht, aber falls du doch welche hast: Beschwöre uns per <a href="mailto:mail@kellerundknilche.at">Runenpost</a>.</p>
      </div>
    </div>
  </div>
</div>

<script>
  // HttpOnly-Cookies in JS nicht lesbar –> serverseitig Login‑Status injizieren
  window.isUserLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
