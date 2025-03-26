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
          if ($_SESSION['user']['admin'] == 1) { ?>
            <li class="nav-item">
              <a class="nav-link" href="/content/user/adminDashboard.php">Admin-Dashboard</a>
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
      </ul>
    </div>
  </div>
</nav>
