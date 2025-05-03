<?php
if (!isset($_SESSION['last_message'])) {
    header("Location: index.php?page=contact");
    exit;
}
$msg = $_SESSION['last_message'];
unset($_SESSION['last_message']);
?>

<h2>Köszönjük az üzenetet!</h2>
<p><strong>Név:</strong> <?= htmlspecialchars($msg['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
<p><strong>Üzenet:</strong> <?= nl2br(htmlspecialchars($msg['message'])) ?></p>