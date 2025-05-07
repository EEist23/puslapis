<?php
session_start();

// Išvalome visus sesijos duomenis
$_SESSION = [];

// Ištrinam sesijos slapuką (jei yra)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Naikiname sesiją
session_destroy();

// Peradresuojam į prisijungimą
header("Location: index.php");
exit;
