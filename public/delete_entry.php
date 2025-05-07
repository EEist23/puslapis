<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->conn;

$userId = $_SESSION['user_id'];
$entryId = $_GET['id'] ?? null;

if ($entryId) {
    // Tikriname, ar įrašas priklauso prisijungusiam vartotojui
    $stmt = $conn->prepare("SELECT * FROM entries WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $entryId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Vartotojui priklauso – trinam
        $deleteStmt = $conn->prepare("DELETE FROM entries WHERE id = ?");
        $deleteStmt->bind_param("i", $entryId);
        $deleteStmt->execute();
    }
}

// Po veiksmo – grįžtam į sąrašą
header("Location: entries.php");
exit;
