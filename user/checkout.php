<?php
require_once '../includes/init.php';
include('../includes/header.php');
include('../includes/navbar.php');

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
?>
    <main>
        <div class="checkout-box">
            <div class="icon">🔒</div>
            <h2>Login Required</h2>
            <p>
                You need to be logged in to proceed with checkout.<br>
                Please log in or create an account to continue.
            </p>

            <div class="actions">
                <a href="../user/index.php" class="btn-primary">Login / Register</a>
                <a href="index.php" class="btn-secondary">Continue Shopping</a>
            </div>
        </div>
    </main>

<?php
    include('../includes/footer.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Get cart items
$query = "SELECT cart.*, products.product_name, products.price 
          FROM cart 
          JOIN products ON cart.product_id = products.id
          WHERE cart.user_id = $user_id";
$result = mysqli_query($conn, $query);

// 3. If cart empty
if (mysqli_num_rows($result) == 0) {
?>
    <main>
        <div class="checkout-box">
            <h2>Your Cart is Empty 🛒</h2>
            <a href="index.php" class="btn-primary">Continue Shopping</a>
        </div>
    </main>

<?php
    include('../includes/footer.php');
    exit();
}

// 4. Calculate total
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['price'] * $row['quantity'];
}

// Reset pointer
mysqli_data_seek($result, 0);

// 5. Insert order
$order_query = "INSERT INTO orders (user_id, total_amount, status) 
                VALUES ($user_id, $total, 'pending')";
mysqli_query($conn, $order_query);
$order_id = mysqli_insert_id($conn);

// 6. Insert order items
while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];

    $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                   VALUES ($order_id, $product_id, $quantity, $price)";
    mysqli_query($conn, $item_query);
}

// 7. Clear cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");
?>

<main>
    <div class="checkout-box">
        <h2>Order Placed Successfully 🎉</h2>
        <p>Your order is now <b>pending</b> and waiting for admin approval.</p>
        <a href="index.php" class="btn-primary">Continue Shopping</a>
    </div>
</main>

<?php include('../includes/footer.php'); ?>