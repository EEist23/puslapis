<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();  // Sukuriame Database klasės objektą
$userId = $_SESSION['user_id'];
$message = '';

// Įrašymo veiksmas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $comment = $_POST['comment'] ?? '';  // Naudojame $comment, kuris dabar bus įrašytas į "content" stulpelį
    $location = $_POST['location'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!empty($title) && !empty($comment)) {
        // Atliksime įrašymą į duomenų bazę, naudodami teisingą stulpelį "content"
        $sql = "INSERT INTO entries (user_id, title, content, location, created_at, ip_address) VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute([$userId, $title, $comment, $location, $ip]);  // Naudojame "content" stulpelį
        $message = 'Įrašas sėkmingai išsaugotas.';
    } else {
        $message = 'Prašome užpildyti privalomus laukus.';
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Naujas įrašas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Naujas įrašas</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Antraštė *</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Komentaras / Gedimo aprašymas *</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Vieta (neprivaloma)</label>
            <input type="text" name="location" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Išsaugoti įrašą</button>
        <a href="dashboard.php" class="btn btn-secondary">Grįžti</a>
    </form>
</body>
</html>
