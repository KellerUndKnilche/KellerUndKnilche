<?php 
require_once('../../config/dbAccess.php');
require_once('../../config/filters/filters.php');

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

$pageTitle = 'Profil';
$pageDescription = 'Dein epischer Steckbrief: Zeig allen, dass du der unangefochtene Kellerboss bist – inkl. Rang, Beute und Monster-Upgrades.';

$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];
$email = $_SESSION['user']['email'];
$last_login = $_SESSION['user']['last_login'];
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

    // Check auf unique Username und E-Mail, aber nur wenn sie geändert wurden
    if ($newUsername !== $username && isUsernameTakenByOther($db, $newUsername, $userId)) {
      $errors[] = "Der Benutzername ist bereits vergeben.";
    }
    if ($newEmail !== $email && isEmailTakenByOther($db, $newEmail, $userId)) {
      $errors[] = "Die E-Mail-Adresse ist bereits vergeben.";
    }

    // Eingaben pruefen
    if (empty($newUsername)) {
        $errors[] = "Benutzername ist erforderlich.";
    } // Nur alphanumerisch a-z 0-9, case insensitive
    elseif (strlen($newUsername) < 3 || strlen($newUsername) > 20) {
        $errors[] = "Benutzername muss zwischen 3 und 20 Zeichen lang sein.";
    }
    elseif (preg_match('/[^a-zA-Z0-9]/', $newUsername)) {
        $errors[] = "Benutzername darf nur Buchstaben (keine Umlaute) und Zahlen enthalten.";
    }
    elseif (!isCleanUsername($newUsername)) {
        $errors[] = "Benutzername ist verboten. Die Behörden wurden verständigt.";
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
$userId = $_SESSION['user']['id'];
$statistics = fetchSingleUserStatistics($db, $userId);
$anzahlUpgrades = $statistics['upgrades'] ?? 0;
?>

<section class="profile-section">
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
      <div class="rank-badge"><?php echo berechneRang($anzahlUpgrades); ?></div>
    </div>

    <?php if (!empty($last_login) && strtotime($last_login)): ?>
      <p class="last-login">Letzter Login: <?php echo date("d.m.Y H:i", strtotime($last_login)); ?></p>
    <?php else: ?>
      <p class="last-login">Letzter Login: -</p>
    <?php endif; ?>


    <!-- Profil-Stats -->
    <div class="profile-stats">
      <div class="stat-item">
        <p class="stat-label">Vermöbelte Helden</p>
        <p class="stat-value"> <?php echo (round($statistics['geld'] ?? 0)/100000); ?></p>
      </div>
      <div class="stat-item">
        <p class="stat-label">Allzeit-Batzen</p>
        <p class="stat-value"> <?php echo round($statistics['geld'] ?? 0, 2); ?></p>
      </div>
      <div class="stat-item">
        <p class="stat-label ">BB/Click</p>
        <p class="stat-value" id="bb-pro-click"></p>
      </div>
      <div class="stat-item">
        <p class="stat-label">BB/Sekunde</p>
        <p class="stat-value" id="bb-pro-sekunde"></p>
      </div>
    </div>

    <!-- Aktive Upgrades -->
    <div class="profile-upgrade-dropdown-wrapper">
      <button id="toggle-upgrade-dropdown" class="upgrade-dropdown-btn">Upgrades anzeigen</button>
      <div id="profile-upgrade-dropdown" class="profile-upgrade-dropdown">
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
                <td>
                  <?php if ($upgrade['kategorie'] === 'Klick'): ?>
                    &#10003;  <!-- Check für Klick Upgrades -->
                  <?php else: ?>
                    <?= $upgrade['level'] ?> 
                  <?php endif; ?>
                </td>
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
          </div>
        </table>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('toggle-upgrade-dropdown');
    const dropdown = document.getElementById('profile-upgrade-dropdown');
    btn.addEventListener('click', function() {
      dropdown.classList.toggle('active');
      btn.textContent = dropdown.classList.contains('active') ? 'Upgrades verbergen' : 'Upgrades anzeigen';
    });
  });
</script>

<?php require_once('../../includes/footer.php'); ?>