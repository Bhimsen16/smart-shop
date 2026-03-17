<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

mysqli_query($conn, "UPDATE orders SET status='rejected' WHERE id=$order_id");

header("Location: orders.php");
exit();