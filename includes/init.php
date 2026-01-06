<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

$currentPage = basename($_SERVER['PHP_SELF']);

// Common globals
$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();
$role = $_SESSION['role'] ?? null;

// User info for navbar
$username = null;
$email    = null;
$avatar_letter = null;

if ($user_id) {
    if (!isset($_SESSION['username'], $_SESSION['email'])) {
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($username, $email);
        $stmt->fetch();
        $stmt->close();

        $_SESSION['username'] = $username;
        $_SESSION['email']    = $email;
    } else {
        $username = $_SESSION['username'];
        $email    = $_SESSION['email'];
    }

    $avatar_letter = strtoupper(substr($username ?? $email, 0, 1));
}

// Cart count (for navbar)
$cart_count = 0;

$current_page = basename($_SERVER['PHP_SELF']);

if (!in_array($current_page, ['login.php', 'register.php'])) {
    if ($user_id) {
        $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE session_id = ?");
        $stmt->bind_param("s", $session_id);
    }

    $stmt->execute();
    $stmt->bind_result($cart_count);
    $stmt->fetch();
    $stmt->close();

    $cart_count = $cart_count ?? 0;
}
