<?php 
  $pageTitle = 'Keller & Knilche Profile';
  require_once('../../includes/header.php');
  require_once('../../includes/nav.php');
  require_once('../../config/dbAccess.php');

  if(!isset($_SESSION['user']) || !isset($_COOKIE['user_id']) || $_COOKIE['user_id'] != $_SESSION['user']['id']) {
    // Session und Cookies sind nicht gesetzt oder stimmen nicht überein
    header("Location: /content/user/login.php");
    exit();
  }
?>
<div class="profile-container">
  <h2>DungeonMaster_666</h2>
  
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