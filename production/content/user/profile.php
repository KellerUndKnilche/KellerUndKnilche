<?php 
  require_once('../../config/dbAccess.php');
  require_once('../../includes/helpers.php');
  
  if (!isset($_SESSION['user'])) {
    // Session nicht gesetzt
    header("Location: " . getBaseUrl() . "/content/user/login.php");
    exit();
  }
  
  if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != $_SESSION['user']['id']) {
    // Cookie ist gesetzt aber stimmt nicht mit Session überein
    session_destroy();
    setcookie('user_id', '', time() - 3600, '/', '', true, true); // Cookie löschen (eine Stunde in der Vergangenheit)
    setcookie('username', '', time() - 3600, '/', '', true, true); 
    header("Location: " . getBaseUrl() . "/content/user/login.php");
    exit();
  }
  
  $pageTitle = 'Keller & Knilche Profile';
  $username = $_SESSION['user']['username'];
  $email = $_SESSION['user']['email'];
  $last_login = $_SESSION['user']['last_login'];

  require_once('../../includes/header.php');
  require_once('../../includes/nav.php');
  ?>
<div class="profile-container">
  <h1><?php echo $username?></h1>
  <div class="card mb-3">
    <p>Email: <?php echo $email?></p>
  </div>
  <div class="card mb-3">
    <p>Letzter Login: <?php echo $last_login?></p>
  </div>
  <div class="card mb-3">
    <p>Rang: <span class="badge bg-primary">Kellermeister</span></p>
  </div>
  <div class="profile-stats">
    <div class="stat-item">
      <p class="stat-label">Heroes Vanquished</p>
      <p class="stat-value">1,342</p>
    </div>
    <div class="stat-item">
      <p class="stat-label">Batzen earned</p>
      <p class="stat-value">8,675</p>
    </div>
    <div class="stat-item">
      <p class="stat-label">BBPC</p>
      <p class="stat-value">45</p>
    </div>
    <div class="stat-item">
      <p class="stat-label">BBPS</p>
      <p class="stat-value">120</p>
    </div>
  </div>
  
  <div class="upgrade-info">
    <h3>Active Upgrades</h3>
    <div class="upgrade-list">
      <div class="upgrade">
        <span class="upgrade-name">Poison Traps</span>
        <span class="upgrade-level">Lvl 3</span>
      </div>
      <div class="upgrade">
        <span class="upgrade-name">Minion Horde</span>
        <span class="upgrade-level">Lvl 5</span>
      </div>
      <div class="upgrade">
        <span class="upgrade-name">Spike Pits</span>
        <span class="upgrade-level">Lvl 2</span>
      </div>
    </div>
  </div>
</div>

<?php require_once('../../includes/footer.php'); ?>