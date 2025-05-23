<?php
require_once('../../config/dbAccess.php');
$pageTitle = 'Statistiken';
$pageDescription = 'Beobachte deine Keller-Rivalen: Wer hortet die meiste Beute und wer verflucht sein Verbesserungs-Glück? Perfekt für deine miese Schadenfreude!';
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
$statistics = fetchUserStatistics($db);
?>
<section class="stats-section">
  <h2 class="visually-hidden">Statistiken</h2>
  <div class="container">
      <h1>Statistiken</h1>
      <table class="table table-dark table-hover">
          <thead>
              <tr>
                  <th scope="col">Benutzername</th>
                  <th scope="col">Geld</th>
                  <th scope="col">Upgrades</th>
                  <th scope="col">Rang</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($statistics as $stat): ?>
                  <tr>
                      <th scope="row"><?php echo htmlspecialchars($stat['username']); ?></th>
                      <td><span class="format-number" data-value="<?php echo round($stat['geld'], 2); ?>"></span> Batzen</td>
                      <td><?php echo htmlspecialchars($stat['upgrades']); ?></td>
                      <td><?php echo berechneRang($stat['upgrades']); ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>
</section>
<?php require_once('../../includes/footer.php'); ?>
<script>
// Formatiert alle Elemente mit der Klasse "format-number"
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.formatNumber === 'function') {
        document.querySelectorAll('.format-number').forEach(function(element) {
            const value = parseFloat(element.dataset.value);
            element.textContent = window.formatNumber(value);
        });
    } else {
        document.querySelectorAll('.format-number').forEach(function(element) {
            const value = parseFloat(element.dataset.value);
            element.textContent = value.toLocaleString('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }
});
</script>
