<?php
require_once '../includes/init.php';
include('../includes/header.php');
include('../includes/navbar.php');

// Add to cart functionality (AJAX-friendly)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];

    $user_id    = $_SESSION['user_id'] ?? null;
    $session_id = session_id();

    $where = $user_id ? "user_id = $user_id" : "session_id = '$session_id'";

    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE product_id = $product_id AND $where");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE product_id = $product_id AND $where");
    } else {
        mysqli_query($conn, "INSERT INTO cart (product_id, quantity, user_id, session_id) VALUES ($product_id, 1, " . ($user_id ?? "NULL") . ", '$session_id')");
    }

    // Get updated cart count
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE $where"));
    $cart_count = $row['total'] ?? 0;

    echo json_encode(["status" => "success", "cart_count" => $cart_count]);
    exit;
}

// Fetch product info
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_num_rows($result) ? mysqli_fetch_assoc($result) : null;
?>

<main>
    <div class="homepage-container">
        <?php if ($product): ?>
            <div class="product-detail-container">
                <!-- Product Image -->
                <div class="product-image">
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                    <h5 class="product-brand">Brand: <?php echo htmlspecialchars($product['brand']); ?></h5>
                    <h3 class="product-price">Rs. <?php echo number_format($product['price']); ?></h3>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                    <button class="btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert">Product not found!</div>
        <?php endif; ?>
    </div>
</main>

<?php include('../includes/footer.php'); ?>