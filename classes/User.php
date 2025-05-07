<?php

class User {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function register($username, $name, $surname, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, name, surname, email, password_hash) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$username, $name, $surname, $email, $hash]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        $ip = $_SERVER['REMOTE_ADDR'];
        $success = false;

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            $success = true;
        }

        $log = $this->db->prepare("INSERT INTO login_logs (username, success, ip_address) VALUES (?, ?, ?)");
        $log->execute([$username, $success, $ip]);

        return $success;
    }

    public function changePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $userId]);
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function logout() {
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
    }

    public function getUserId() {
        return $_SESSION['user']['id'] ?? null;
    }
}
