<?php
require_once '../includes/init.php';
header('Content-Type: application/json');

$product_id = (int)($_POST['product_id'] ?? 0);
if (!$product_id) {
    echo json_encode(["status" => "error", "message" => "Invalid product ID"]);
    exit;
}

$user_id    = $_SESSION['user_id'] ?? null;
$session_id = session_id();

$where = $user_id ? "user_id = $user_id" : "session_id = '$session_id'";

/* Check if product exists in cart */
$check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE product_id = $product_id AND $where");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE product_id = $product_id AND $where");
} else {
    mysqli_query($conn, "INSERT INTO cart (product_id, quantity, user_id, session_id) VALUES ($product_id, 1, " . ($user_id ?? "NULL") . ", '$session_id')");
}

/* Get updated cart count */
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE $where"));
$cart_count = $row['total'] ?? 0;

/* Return JSON for AJAX */
echo json_encode([
    "status" => "success",
    "cart_count" => $cart_count
]);
exit;