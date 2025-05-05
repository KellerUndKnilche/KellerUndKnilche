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
        <li>
          <a class="nav-link" href="/impressum">Impressum</a>
        </li>
        <li>
          <a class="nav-link" href="/datenschutz">Datenschutz</a>
        </li>
        <li class="nav-item">
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
<script>
  // HttpOnly-Cookies in JS nicht lesbar –> serverseitig Login‑Status injizieren
  window.isUserLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
