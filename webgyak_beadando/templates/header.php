<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= $config['site_title'] ?? 'Weboldal' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/webgyak_beadando/css/style.css">
</head>

<body>
    <header>
        <h1><?= $config['site_title'] ?? 'Webalkalmazás' ?></h1>
        <?php if (isset($_SESSION['user'])): ?>
            <p style="text-align: center;">
                Bejelentkezett: <?= htmlspecialchars($_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['firstname']) ?>
                (<?= htmlspecialchars($_SESSION['user']['login']) ?>)
            </p>
        <?php endif; ?>
    </header>

    <nav style="text-align: center; margin-bottom: 20px;">
        <ul style="list-style: none; padding: 0; display: flex; flex-wrap: wrap; justify-content: center; gap: 15px;">
            <?php
            foreach ($config['menu'] as $key => $value) {
                echo "<li><a href='index.php?page=$key'>$value</a></li>";
            }

            if (!isset($_SESSION['user'])) {
                echo "<li><a href='index.php?page=login'>Belépés</a></li>";
            } else {
                echo "<li><a href='controllers/logout.php'>Kilépés</a></li>";
            }
            ?>
        </ul>
    </nav>

    <main>
