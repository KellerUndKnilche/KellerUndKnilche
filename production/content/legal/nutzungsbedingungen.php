<?php
require_once('../../config/dbAccess.php');
$pageTitle       = 'Nutzungsbedingungen';
$pageDescription = 'Nutzungsbedingungen – weil selbst chaotische Keller Regeln brauchen. Lies sie, bevor du Unsinn treibst.';
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
?>

<main id="nutzungsbedingungen" role="main" class="nutzungsbedingungen container py-5">
  <div class="content-box mx-auto">
    <h2 class="visually-hidden">Nutzungsbedingungen</h2>
    <h1 class="mb-4">Nutzungsbedingungen</h1>
    <p>Durch die Nutzung dieser Website stimmen Sie folgenden Bedingungen zu:</p>
    <ul>
        <li>Die Nutzung erfolgt auf eigene Verantwortung.</li>
        <li>Benutzernamen oder Inhalte, die beleidigend, diskriminierend, politisch extrem oder anderweitig unangemessen sind, werden ohne Vorwarnung gelöscht oder gesperrt.</li>
        <li>Wir behalten uns das Recht vor, Benutzerkonten jederzeit zu sperren oder zu löschen, insbesondere bei Verstoß gegen diese Regeln oder missbräuchlicher Nutzung.</li>
    </ul>
  </div>
</main>

<?php require_once('../../includes/footer.php'); ?>
