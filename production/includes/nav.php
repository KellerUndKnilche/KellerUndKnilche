<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Primary navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">Keller &amp; Knilche</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Hauptseiten -->
        <li class="nav-item">
          <a class="nav-link" href="/index.php">Spiel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/content/user/statistics.php">Statistiken</a>
        </li>
        <?php if (isset($_SESSION['user'])) { 
          if ($_SESSION['user']['isAdmin'] == 1) { ?>
            <li class="nav-item">
              <a class="nav-link" href="/content/user/admin.php">Admin-Dashboard</a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="/content/user/profile.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/content/user/logout.php">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="/content/user/registration.php">Registrierung</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/content/user/login.php">Login</a>
          </li>
        <?php } ?>
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
