<?php
session_start();
require_once 'db/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register_submit'])) {
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->fetch()) {
            $error = "Ez a felhasználónév már foglalt!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (lastname, firstname, login, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$lastname, $firstname, $login, $password]);
            $success = "Sikeres regisztráció! Most már bejelentkezhet.";
        }
    }

    if (isset($_POST['login_submit'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = "Hibás felhasználónév vagy jelszó!";
        }
    }
}
?>

<h2>Bejelentkezés</h2>
<form method="post">
    <input type="text" name="login" placeholder="Felhasználónév" required>
    <input type="password" name="password" placeholder="Jelszó" required>
    <button type="submit" name="login_submit">Belépés</button>
</form>

<h2>Regisztráció</h2>
<form method="post">
    <input type="text" name="lastname" placeholder="Vezetéknév" required>
    <input type="text" name="firstname" placeholder="Keresztnév" required>
    <input type="text" name="login" placeholder="Felhasználónév" required>
    <input type="password" name="password" placeholder="Jelszó" required>
    <button type="submit" name="register_submit">Regisztráció</button>
</form>

<?php
if ($error) echo "<p style='color: red;'>$error</p>";
if ($success) echo "<p style='color: green;'>$success</p>";
?>