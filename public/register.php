<?php
require_once '../config/config.php';

$db = new Database();
$user = new User($db);

$generatedPassword = PasswordGenerator::generate();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->register($username, $name, $surname, $email, $password)) {
        header('Location: index.php?registered=1');
        exit;
    } else {
        $message = 'Registracija nepavyko. Galbūt vartotojo vardas jau naudojamas.';
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Registracija</h2>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Vartotojo vardas</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Vardas</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pavardė</label>
            <input type="text" name="surname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>El. paštas</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Slaptažodis</label>
            <input type="text" name="password" class="form-control" value="<?= $generatedPassword ?>" required>
            <small class="text-muted">Galite naudoti sugeneruotą arba įvesti savo</small>
        </div>
        <button type="submit" class="btn btn-primary">Registruotis</button>
        <a href="index.php" class="btn btn-secondary">Atgal</a>
    </form>
</body>
</html>
