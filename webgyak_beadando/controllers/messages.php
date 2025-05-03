<?php
session_start();
require_once 'db/database.php';

// Csak bejelentkezett felhasználó láthatja az üzeneteket
if (!isset($_SESSION['user'])) {
    echo "<p>Az üzenetek megtekintéséhez jelentkezz be.</p>";
    return;
}

// Lekérdezzük az összes üzenetet és a küldő adatait (ha van)
$stmt = $pdo->query("
    SELECT m.*, u.lastname, u.firstname, u.login
    FROM messages m
    LEFT JOIN users u ON m.user_id = u.id
    ORDER BY m.created_at DESC
");
$messages = $stmt->fetchAll();
?>

<h2>Kapott üzenetek</h2>

<?php if (empty($messages)): ?>
    <p>Jelenleg nincs megjeleníthető üzenet.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Feladó</th>
                <th>Email</th>
                <th>Üzenet</th>
                <th>Dátum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
                <tr>
                    <td>
                        <?php
                        if (!empty($msg['user_id']) && !empty($msg['lastname'])) {
                            echo htmlspecialchars($msg['lastname']) . " " . htmlspecialchars($msg['firstname']) .
                                " (" . htmlspecialchars($msg['login']) . ")";
                        } else {
                            echo htmlspecialchars($msg['name']) . " (vendég)";
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                    <td><?= htmlspecialchars($msg['created_at'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>