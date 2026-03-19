<?php
session_start();
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validations
    // Username
    if (strlen($username) < 3) {
        die("Username must be at least 3 characters");
    }

    if (!preg_match('/^[a-zA-Z](?=(?:.*[a-zA-Z]){2,})[a-zA-Z0-9_]*$/', $username)) {
        die("Username must start with a letter and contain at least 3 letters");
    }

    // Email
    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/\.[a-zA-Z]{2,}$/', $email)
    ) {
        header("Location: ../user/index.php?register_error=email");
        exit;
    }

    // Password
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters");
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email already registered");
    }
    $check->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: ../user/index.php?registered=1&tab=login");
        exit;
    } else {
        die("Registration failed");
    }
}
