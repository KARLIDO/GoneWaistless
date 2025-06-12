<?php
// session_start(); // REMOVE THIS LINE
require_once 'includes/config.php';
function registerUser($pdo, $username, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}
function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
}
function checkAdmin() {
    if (!isAdmin()) {
        header("Location: ../login.php?reason=admin_access");
        exit;
    }
}
?>