<?php
require_once '../config/config.php';

$db = new Database();
$user = new User($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'Neteisingi prisijungimo duomenys.';
    }
}

$registered = isset($_GET['registered']);
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
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($registered): ?>
        <div class="alert alert-success">Registracija sėkminga! Prisijunkite.</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Vartotojo vardas</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Slaptažodis</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Prisijungti</button>
        <a href="register.php" class="btn btn-link">Registruotis</a>
    </form>
</body>
</html>
