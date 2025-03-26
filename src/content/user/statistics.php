<?php
$pageTitle = 'Keller & Knilche Statistiken';
require_once('../../includes/header.php');
require_once('../../includes/nav.php');
require_once('../../config/dbAccess.php');

$statistics = fetchUserStatistics($db);
?>
<div class="container">
    <h1>Statistiken</h1>
    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th scope="col">Benutzername</th>
                <th scope="col">Geld</th>
                <th scope="col">Upgrades</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statistics as $stat): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($stat['username']); ?></th>
                    <td><?php echo htmlspecialchars($stat['geld']); ?></td>
                    <td><?php echo htmlspecialchars($stat['upgrades']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once('../../includes/footer.php'); ?>
<script src="../../../../assets/js/script.js"></script>
