<?php
$upload_dir = 'uploads/';
$upload_success = '';
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image']['name']);
        $target = $upload_dir . uniqid() . "_" . $filename;

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $upload_success = "A kép sikeresen feltöltve!";
            } else {
                $upload_error = "Hiba történt a fájl mentésekor.";
            }
        } else {
            $upload_error = "Csak JPG, PNG és GIF fájlok tölthetők fel.";
        }
    } else {
        $upload_error = "Nem sikerült feltölteni a képet.";
    }
}
?>

<h2>Képgaléria</h2>
<div style="display: flex; flex-wrap: wrap; gap: 10px;">
    <?php
    $images = glob($upload_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    foreach ($images as $img) {
        echo "<div><img src='$img' style='max-width:200px; height:auto; border:1px solid #ccc;'></div>";
    }
    ?>
</div>

<?php if (isset($_SESSION['user'])): ?>
    <h3>Új kép feltöltése</h3>
    <?php if ($upload_success) echo "<p style='color:green;'>$upload_success</p>"; ?>
    <?php if ($upload_error) echo "<p style='color:red;'>$upload_error</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Feltöltés</button>
    </form>
<?php else: ?>
    <p>Kép feltöltéséhez kérlek <a href="index.php?page=login">jelentkezz be</a>.</p>
<?php endif; ?>