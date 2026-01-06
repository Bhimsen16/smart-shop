<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../user/index.php");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

/* Basic validation */
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    header("Location: ../user/index.php?login_error=1&tab=login");
    exit;
}

$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        /* Merge guest cart into user cart */
        $session_id = session_id();
        $user_id    = $user['id'];

        $updateCart = $conn->prepare(
            "UPDATE cart SET user_id = ?, session_id = NULL WHERE session_id = ?"
        );
        $updateCart->bind_param("is", $user_id, $session_id);
        $updateCart->execute();

        if ($user['role'] === 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/index.php");
        }
        exit;
    }
}

/* Login failed */
header("Location: ../user/index.php?login_error=1&tab=login");
exit;
