<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: users.php");
    exit();
}

switch ($action) {

    case 'make_admin':
        $conn->query("UPDATE users SET role='admin' WHERE id=$id");
        break;

    case 'make_user':
        $conn->query("UPDATE users SET role='user' WHERE id=$id");
        break;

    case 'deactivate':
        $conn->query("UPDATE users SET status='inactive' WHERE id=$id");
        break;

    case 'activate':
        $conn->query("UPDATE users SET status='active' WHERE id=$id");
        break;

    case 'delete':
        $conn->query("DELETE FROM users WHERE id=$id");
        break;
}

header("Location: users.php");
exit();
