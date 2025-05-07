<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->conn;
$userId = $_SESSION['user_id'];
$message = '';

// Slaptažodžio keitimas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $repeat = $_POST['repeat_password'] ?? '';

    if ($new !== $repeat) {
        $message = 'Nauji slaptažodžiai nesutampa.';
    } else {
        // Patikriname dabartinį slaptažodį
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current, $hashedPassword)) {
            // Atnaujiname slaptažodį
            $newHashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newHashed, $userId);
            $update->execute();

            $message = 'Slaptažodis sėkmingai pakeistas.';
        } else {
            $message = 'Neteisingas dabartinis slaptažodis.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Keisti slaptažodį</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Keisti slaptažodį</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Dabartinis slaptažodis</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Naujas slaptažodis</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pakartokite naują slaptažodį</label>
            <input type="password" name="repeat_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning">Keisti slaptažodį</button>
        <a href="dashboard.php" class="btn btn-secondary">Grįžti</a>
    </form>
</body>
</html>
