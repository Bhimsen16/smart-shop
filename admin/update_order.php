<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $status = $_GET['status'];

    $query = "UPDATE orders SET status='$status' WHERE id=$order_id";
    mysqli_query($conn, $query);
}

header("Location: orders.php");
exit();