<?php
session_start();
$pageTitle = 'Admin Armaturenbrett';
$pageDescription = 'Admin‑Dashboard von Keller & Knilche: Spieler‑Verwaltung und System‑Status.';
require_once('../../config/dbAccess.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['isAdmin'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<section class="admin-section">
  <h2 class="visually-hidden">Admin Armaturenbrett</h2>
  <main class="container mt-4">
      <div class="admin-dashboard">
          <h1 class="mb-4">Admin Armaturenbrett</h1>
          
          <div class="row">
              <div class="col-md-6 mb-4">
                  <div class="card">
                      <div class="card-header">
                          <h2>Spieler Verwaltung</h2>
                      </div>
                      <div class="card-body">
                          <!-- Player management controls here -->
                      </div>
                  </div>
              </div>
              
              <div class="col-md-6 mb-4">
                  <div class="card">
                      <div class="card-header">
                          <h2>System Status</h2>
                      </div>
                      <div class="card-body">
                          <!--<p>Aktive Spieler: <?php //echo count($users); ?></p>
                          <p>Server Status: Online</p>-->
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>
</section>

<?php require_once('../../includes/footer.php'); ?>