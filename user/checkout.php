<?php
require_once '../includes/init.php';

// 1. Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Get cart items
$query = "SELECT cart.*, products.price 
          FROM cart 
          JOIN products ON cart.product_id = products.id
          WHERE cart.user_id = $user_id";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Cart is empty!";
    exit();
}

// 3. Calculate total
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['price'] * $row['quantity'];
}

// Reset pointer
mysqli_data_seek($result, 0);

// 4. Insert into orders
$order_query = "INSERT INTO orders (user_id, total_amount, status)
                VALUES ($user_id, $total, 'pending')";

mysqli_query($conn, $order_query);

// Get order ID
$order_id = mysqli_insert_id($conn);

// 5. Insert into order_items
while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];

    $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                   VALUES ($order_id, $product_id, $quantity, $price)";
    mysqli_query($conn, $item_query);
}

// 6. Clear cart
$delete_query = "DELETE FROM cart WHERE user_id = $user_id";
mysqli_query($conn, $delete_query);
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<main>
    <div class="container">
        <h2>Order Placed Successfully 🎉</h2>
        <p>Your order is now <b>pending</b> and waiting for admin approval.</p>
        <a href="index.php">Continue Shopping</a>
    </div>
</main>

<?php include('../includes/footer.php'); ?>