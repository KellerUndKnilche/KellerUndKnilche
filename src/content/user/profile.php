<?php 
require_once('../../config/dbAccess.php');
require_once('../../includes/helpers.php');

if (!isset($_SESSION['user'])) {
    header("Location: " . getBaseUrl() . "/login");
    exit();
}

if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != $_SESSION['user']['id']) {
    session_destroy();
    setcookie('user_id', '', time() - 3600, '/', '', true, true);
    setcookie('username', '', time() - 3600, '/', '', true, true); 
    header("Location: " . getBaseUrl() . "/content/user/login.php");
    exit();
}

$pageTitle = 'Keller & Knilche Profil';
$pageDescription = 'Dein Profil bei Keller & Knilche: Account‑Daten, Rang und Spiel‑Statistiken.';
$pageKeywords    = 'Profil, Keller Knilche, Benutzerkonto, Idle Game';

$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];
$email = $_SESSION['user']['email'];
$last_login = $_SESSION['user']['last_login'];
$rank = $_SESSION['user']['rank'] ?? 'Kellermeister';
$userUpgrades = getUserUpgrades($db, $userId);

$successMsg = "";
$errors = [];

// Formulardaten verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);
    $newPassword = trim($_POST['password']);
    $passwordConfirm = trim($_POST['password_confirm'] ?? '');

    // Passwort Validierung
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = "Das Passwort muss mindestens 6 Zeichen lang sein.";
        }
        if ($newPassword !== $passwordConfirm) {
            $errors[] = "Die Passwörter stimmen nicht überein.";
        }
    }

    // Check auf unique Username
    if(isUsernameTakenByOther($db, $newUsername, $userId)) {
        $errors[] = "Der Benutzername ist bereits vergeben.";
    }

    // Check auf unique Username
    if(isEmailTakenByOther($db, $newEmail, $userId)) {
        $errors[] = "Die E-Mail-Adresse ist bereits vergeben.";
    }

    // Wenn keine Fehler, dann updaten
    if (empty($errors)) {
        $updateSuccess = updateUserProfile($db, $userId, $newUsername, $newEmail, $newPassword);

        if ($updateSuccess)  {
            $_SESSION['user']['username'] = $newUsername;
            $_SESSION['user']['email'] = $newEmail;
            $username = $newUsername;
            $email = $newEmail;
            $successMsg = "Profil erfolgreich aktualisiert.";
        } else {
            $errors[] = "Fehler beim Aktualisieren des Profils.";
        }
    }
}

require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<section class="profile-section">
  <script src = ../../assets/js/script.js></script>

  <h2 class="visually-hidden">Mein Profil</h2>
  <div class="profile-wrapper">
    <h1 class="profile-title">Mein Profil</h1>

    <?php if (!empty($successMsg)): ?>
      <div class="success-box"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $error): ?>
            <ul><?php echo $error; ?></ul>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" class="profile-form">
      <label class="profile-input-label" for="username">Benutzername</label>
      <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>

      <label class="profile-input-label" for="email">E-Mail-Adresse</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

      <label class="profile-input-label" for="password">Neues Passwort <small>(leer für keine Änderung)</small></label>
      <input type="password" name="password" id="password">

      <label class="profile-input-label" for="password_confirm">Passwort wiederholen</label>
      <input type="password" name="password_confirm" id="password_confirm">


      <button type="submit">Profil speichern</button>
    </form>

    <div class="rank-display">
      <p><strong>Rang:</strong></p>
      <div class="rank-badge"><?php echo $rank; ?> 🏆</div>
    </div>

    <?php if (!empty($last_login) && strtotime($last_login)): ?>
      <p class="last-login">Letzter Login: <?php echo date("d.m.Y H:i", strtotime($last_login)); ?></p>
    <?php else: ?>
      <p class="last-login">Letzter Login: -</p>
    <?php endif; ?>


    <!-- Profil-Stats -->
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

    <!-- Aktive Upgrades -->
    <div class="upgrade-info">
      <h3>Aktive Upgrades</h3>

      <div class="profile-tables-row">
        <table class="profile-upgrade-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Level</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $bought = false;
              foreach ($userUpgrades as $upgrade):
                if ($upgrade['level'] > 0):
                  $bought = true;
            ?>
              <tr>
                <td><?= htmlspecialchars($upgrade['name']) ?></td>
                <td><?= $upgrade['level'] ?></td>
              </tr>
            <?php
                endif;
              endforeach;
              if (!$bought):
            ?>
              <tr>
                <td colspan="2">Du hast noch keine Upgrades gekauft.</td>
              </tr>
            <?php endif; ?>
          </tbody>

        </table>
        <table class="profile-earning-table">
          <thead>
            <tr>
              <th>BB/Click</th>
              <th>BB/Sekunde</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="bb-pro-click"></td>
              <td id="bb-pro-sekunde"></td>
            </tr>
          </tbody>
        </table>
      </div>

      </div>
    </div>
  </div>
</section>

<?php require_once('../../includes/footer.php'); ?>