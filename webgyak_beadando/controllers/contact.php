<?php
require_once 'db/database.php';

$name = $email = $message = '';
$errors = [];
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name)) $errors[] = "Név kötelező.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Érvénytelen email cím.";
    if (empty($message)) $errors[] = "Az üzenet nem lehet üres.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message, user_id) VALUES (?, ?, ?, ?)");
        $user_id = $_SESSION['user']['id'] ?? null;
        $stmt->execute([$name, $email, $message, $user_id]);

        $_SESSION['last_message'] = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];

        header("Location: index.php?page=thankyou");
        exit;
    }
}
?>

<h2>Kapcsolatfelvétel</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
    </ul>
<?php endif; ?>

<form method="post" id="contactForm">
    <input type="text" name="name" placeholder="Név" value="<?= htmlspecialchars($name) ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
    <textarea name="message" placeholder="Üzenet..." required><?= htmlspecialchars($message) ?></textarea>
    <button type="submit">Küldés</button>
</form>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        const name = this.name.value.trim();
        const email = this.email.value.trim();
        const message = this.message.value.trim();

        if (!name || !email || !message) {
            alert("Minden mező kitöltése kötelező!");
            e.preventDefault();
        }
    });
</script>