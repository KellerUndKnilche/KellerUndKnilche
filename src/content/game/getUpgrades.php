<?php
    require_once('../../config/dbAccess.php');
    $upgrades = getUpgrades($db);

    header('Content-Type: application/json');
    echo json_encode($upgrades);
?>