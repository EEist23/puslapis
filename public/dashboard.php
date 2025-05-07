<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Valdymo panelė</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Pagrindinis</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="new_entry.php">➕ Naujas įrašas</a></li>
        <li class="nav-item"><a class="nav-link" href="entries.php">📋 Visi įrašai</a></li>
        <li class="nav-item"><a class="nav-link" href="change_password.php">🔐 Keisti slaptažodį</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">🚪 Atsijungti</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2>Sveiki, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p>Pasirinkite veiksmą naudodami meniu viršuje.</p>
</div>

</body>
</html>
