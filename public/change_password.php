<?php
require_once '../config/config.php';

// Tikrinam ar vartotojas prisijungęs
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        $message = 'Nauji slaptažodžiai nesutampa.';
    } else {
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($oldPassword, $user['password_hash'])) {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
            $update->execute(['hash' => $newHash, 'id' => $_SESSION['user_id']]);
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
        <input type="password" name="old_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Naujas slaptažodis</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Pakartokite naują slaptažodį</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Keisti slaptažodį</button>
    <a href="dashboard.php" class="btn btn-link">Grįžti į valdymo skydelį</a>
</form>

</body>
</html>
