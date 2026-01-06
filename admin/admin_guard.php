<?php
require_once __DIR__ . '/../includes/init.php';

if (!$user_id) {
    header("Location: ../user/index.php");
    exit;
}

if ($role !== 'admin') {
    header("Location: ../user/index.php");
    exit;
}
