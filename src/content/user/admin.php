<?php
session_start();
require_once('../../config/dbAccess.php');
$pageTitle = 'Admin Armaturenbrett';

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

                            <!-- Formular: Suche nach Benutzername und Sperren/Entsperren -->
                            <form method="POST">
                                <label for="username">Benutzername:</label>
                                <input type="text" id="username" name="username" class="form-control mb-2" autocomplete="off" placeholder="Benutzername suchen..." onkeyup="searchUser(this.value)">
                                <div id="user-suggestions" class="list-group mb-3"></div>

                                <button type="submit" name="action" value="lock" class="btn btn-danger">Sperren</button>
                                <button type="submit" name="action" value="unlock" class="btn btn-success">Entsperren</button>
                            </form>

                            <?php
                            // Formularverarbeitung: Benutzer sperren oder entsperren
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $username = trim($_POST['username']);
                                $action = $_POST['action'];

                                if (!empty($username)) {
                                    $stmt = $db->prepare("SELECT id, isAdmin FROM users WHERE username = ?");
                                    $stmt->bind_param("s", $username);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result && $result->num_rows === 1) {
                                        $user = $result->fetch_assoc();

                                        if ($user['isAdmin'] == 1) {
                                            echo "<p class='text-warning mt-2'>Admins können nicht gesperrt werden.</p>";
                                        } else {
                                            $lockValue = $action === 'lock' ? 1 : 0;
                                            $updateStmt = $db->prepare("UPDATE users SET isLocked = ? WHERE id = ?");
                                            $updateStmt->bind_param("ii", $lockValue, $user['id']);
                                            $updateStmt->execute();

                                            if ($updateStmt->affected_rows > 0) {
                                                $msg = $lockValue ? "gesperrt" : "entsperrt";
                                                echo "<p class='text-success mt-2'>User <strong>$username</strong> wurde erfolgreich $msg.</p>";
                                            } else {
                                                echo "<p class='text-danger mt-2'>Aktion fehlgeschlagen.</p>";
                                            }
                                        }
                                    } else {
                                        echo "<p class='text-danger mt-2'>Benutzer nicht gefunden.</p>";
                                    }
                                }
                            }
                            ?>

                            <!-- Live-Suche im Eingabefeld -->
                            <script>
                                function searchUser(query) {
                                    const suggestions = document.getElementById("user-suggestions");
                                    if (query.length < 2) {
                                        suggestions.innerHTML = '';
                                        return;
                                    }

                                    fetch('/src/content/user/user_search.php?q=' + encodeURIComponent(query))
                                    .then(response => response.json())
                                    .then(data => {
                                        suggestions.innerHTML = '';
                                        data.forEach(user => {
                                            const item = document.createElement("button");
                                            item.className = "list-group-item list-group-item-action";
                                            item.textContent = `${user.username}${user.locked ? ' [gesperrt]' : ''}`;
                                            item.onclick = () => {
                                                document.getElementById("username").value = user.username;
                                                suggestions.innerHTML = '';
                                            };
                                            suggestions.appendChild(item);
                                        });
                                    });
                                }
                            </script>

                            <!-- Tabelle: Alle Benutzer (nicht-Admins) -->
                            <h3 class="mt-4">Alle Benutzer (nicht-Admins)</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Benutzername</th>
                                        <th>Status</th>
                                        <th>Aktion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $users = fetchAllNonAdminUsers($db);
                                    foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= $user['isLocked'] ? 'Gesperrt' : 'Aktiv' ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                                                    <?php if ($user['isLocked']): ?>
                                                        <button type="submit" name="action" value="unlock" class="btn btn-success btn-sm">Entsperren</button>
                                                    <?php else: ?>
                                                        <button type="submit" name="action" value="lock" class="btn btn-danger btn-sm">Sperren</button>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Tabelle: Neu registrierte Benutzer -->
                            <h3 class="mt-5">Neu registrierte Benutzer</h3>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Benutzername</th>
                                        <th>Registriert am</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $db->prepare("SELECT username, registrationDate, isLocked FROM users WHERE isAdmin = 0 ORDER BY registrationDate DESC LIMIT 20");
                                    $stmt->execute();
                                    $newUsers = $stmt->get_result();
                                    while ($user = $newUsers->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= date("d.m.Y H:i", strtotime($user['registrationDate'])) ?></td>
                                            <td><?= $user['isLocked'] ? 'Gesperrt' : 'Aktiv' ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- System-Status-Karte mit Spieleranzahl -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h2>System Status</h2>
                        </div>
                        <div class="card-body">
                            <?php
                            $result = $db->query("SELECT COUNT(*) as total FROM users WHERE isLocked = 0");
                            $count = $result->fetch_assoc()['total'] ?? 0;
                            ?>
                            <p>Aktive Spieler: <?= $count ?></p>
                            <p>Server Status: Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</section>

<?php require_once('../../includes/footer.php'); ?>
