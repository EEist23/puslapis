<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection(); // ✅ teisingai

// Gaunam visus įrašus kartu su vartotojo vardu
$sql = "SELECT e.*, u.username 
        FROM entries e 
        JOIN users u ON e.user_id = u.id 
        ORDER BY e.created_at DESC";

$stmt = $conn->query($sql);

// Naudojame PDO metodo fetch, kad gautume asociatyvinį masyvą
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Visi įrašai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Visi naudotojų įrašai</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Grįžti į valdymo panelę</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Naudotojas</th>
                <th>Antraštė</th>
                <th>Komentaras</th>
                <th>Vieta</th>
                <th>Data</th>
                <th>IP</th>
                <th>Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['content'])) ?></td> <!-- Pakeistas 'comment' į 'content' -->
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['ip_address'] ?></td>
                    <td>
                        <?php if ($row['user_id'] == $_SESSION['user_id']): ?>
                            <a href="delete_entry.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ar tikrai norite ištrinti?')">Trinti</a>
                        <?php else: ?>
                            <em>-</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
