<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$user = new User($db);

$currentUser = $user->getById($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Valdymo panelė</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Sveiki, <?= htmlspecialchars($currentUser['name']) ?>!</h2>

    <p>
        <a href="new_entry.php" class="btn btn-primary">Naujas įrašas</a>
        <a href="entries.php" class="btn btn-info">Peržiūrėti įrašus</a>
        <a href="change_password.php" class="btn btn-warning">Keisti slaptažodį</a>
        <a href="logout.php" class="btn btn-danger">Atsijungti</a>
    </p>

    <hr>
    <p>Čia galite valdyti savo įrašus, matyti kitų naudotojų įrašus ir tvarkyti paskyrą.</p>
</body>
</html>
