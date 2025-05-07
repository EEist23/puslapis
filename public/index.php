<?php
require_once '../config/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        // Sėkmingas prisijungimas
        $logStmt = $conn->prepare("INSERT INTO login_logs (username, success, ip_address) VALUES (:username, 1, :ip)");
        $logStmt->execute(['username' => $username, 'ip' => $ip]);

        header('Location: dashboard.php');
        exit;
    } else {
        // Neteisingas prisijungimas
        $message = 'Neteisingas vartotojo vardas arba slaptažodis.';

        $logStmt = $conn->prepare("INSERT INTO login_logs (username, success, ip_address) VALUES (:username, 0, :ip)");
        $logStmt->execute(['username' => $username, 'ip' => $ip]);
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Prisijungimas</h2>

<?php if ($message): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Vartotojo vardas</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Slaptažodis</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Prisijungti</button>
    <a href="register.php" class="btn btn-link">Registruotis</a>
</form>

</body>
</html>
