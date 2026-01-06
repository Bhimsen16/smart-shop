<?php
require_once '../includes/init.php';

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

$query = "SELECT cart.id AS cart_id, cart.quantity, products.id AS product_id, products.product_name, products.price, products.image 
          FROM cart 
          JOIN products ON cart.product_id = products.id
          WHERE " . ($user_id ? "cart.user_id=$user_id" : "cart.session_id='$session_id'");

$result = mysqli_query($conn, $query);
$cart_empty = (mysqli_num_rows($result) == 0);

// calculate total
$total = 0;
if (!$cart_empty) {
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        $total += $row['price'] * $row['quantity'];
    }
    mysqli_data_seek($result, 0); // reset pointer for loop below
}
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<main>
    <div class="cart-container">
        <h2>Your Cart</h2>

        <div class="cart-wrapper">

            <div id="emptyCart" class="cart-alert" style="display: <?= $cart_empty ? 'block' : 'none' ?>">
                Your cart is empty!
                <br>
                <a href="index.php" class="continue-shopping">← Continue Shopping</a>
            </div>

            <!-- Left: Cart Items -->
            <div class="cart-items">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="cart-card" id="cart-row-<?php echo $row['cart_id']; ?>">

                        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" class="cart-img">

                        <div class="cart-info">
                            <h5 class="cart-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                            <p class="cart-price">Rs. <?php echo number_format($row['price']); ?></p>

                            <div class="qty-box">
                                <button onclick="updateCart(<?php echo $row['cart_id']; ?>, 'decrease')">-</button>
                                <input type="number" id="qty-<?php echo $row['cart_id']; ?>" value="<?php echo $row['quantity']; ?>" readonly>
                                <button onclick="updateCart(<?php echo $row['cart_id']; ?>, 'increase')">+</button>
                            </div>

                            <p class="cart-subtotal">
                                Subtotal:
                                <span id="subtotal-<?php echo $row['cart_id']; ?>">
                                    Rs. <?php echo $row['price'] * $row['quantity']; ?>
                                </span>
                            </p>
                        </div>

                        <button class="remove-btn" onclick="removeFromCart(<?php echo $row['cart_id']; ?>)">Remove</button>

                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Right: Order Summary -->
            <div class="order-summary" id="orderSummary" style="display: <?= $cart_empty ? 'none' : 'block' ?>">
                <h4>Order Summary</h4>
                <p class="summary-line">
                    Total:
                    <span id="cart-total">Rs. <?php echo number_format($total); ?></span>
                </p>

                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>

        </div>
    </div>
</main>

<?php include('../includes/footer.php'); ?>