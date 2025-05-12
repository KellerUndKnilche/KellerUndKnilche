<?php
require_once('../../config/dbAccess.php');

header('Content-Type: application/json');

if (!isset($_GET['q']) || strlen($_GET['q']) < 2) {
    echo json_encode([]);
    exit;
}

$query = '%' . $_GET['q'] . '%';

$stmt = $db->prepare("SELECT username, isLocked FROM users WHERE username LIKE ? AND isAdmin = 0 ORDER BY username LIMIT 10");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

$userList = [];
while ($row = $result->fetch_assoc()) {
    $userList[] = [
        'username' => $row['username'],
        'locked' => (bool)$row['isLocked']
    ];
}

echo json_encode($userList);
