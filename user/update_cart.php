<?php
require_once '../includes/init.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'] ?? 0;
$action  = $data['action'] ?? '';

$user_id    = $_SESSION['user_id'] ?? null;
$session_id = session_id();

// Step 1: Validate cart item
$cartQuery = mysqli_query($conn, "
    SELECT c.quantity, c.product_id, p.price 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.id = $cart_id
");
if (!$cartQuery || mysqli_num_rows($cartQuery) == 0) {
    echo json_encode(["success" => false, "message" => "Item not found"]);
    exit;
}

$cartRow = mysqli_fetch_assoc($cartQuery);
$quantity = $cartRow['quantity'];
$price    = $cartRow['price'];

// Step 2: Handle actions
if ($action === "increase") {
    $quantity += 1;

    mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id");

} elseif ($action === "decrease") {
    $quantity = max(1, $quantity - 1);

    mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id");

} elseif ($action === "remove") {

    mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

    $quantity = 0;  // used only for response
}

// Step 3: Recalculate user's total and cart count
$where = $user_id
    ? "user_id = $user_id"
    : "session_id = '$session_id'";

$totalQuery = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(c.quantity * p.price) AS total 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE $where
"));
$total = $totalQuery['total'] ?? 0;

$countQuery = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(quantity) AS count FROM cart WHERE $where
"));
$cart_count = $countQuery['count'] ?? 0;

echo json_encode([
    "success"      => true,
    "new_quantity" => $quantity,
    "subtotal"     => $quantity * $price,
    "total"        => $total,
    "cart_count"   => $cart_count,
    "removed"      => ($action === "remove")
]);
?>
