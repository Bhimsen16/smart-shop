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

// Processor specs
$processor = $conn->query(
    "SELECT * FROM product_processor_specs WHERE product_id = $product_id"
)->fetch_assoc();

// Memory specs
$memory = $conn->query(
    "SELECT * FROM product_memory_specs WHERE product_id = $product_id"
)->fetch_assoc();

// Display specs
$display = $conn->query(
    "SELECT * FROM product_display_specs WHERE product_id = $product_id"
)->fetch_assoc();

// General specs
$general = $conn->query(
    "SELECT * FROM product_general_specs WHERE product_id = $product_id"
)->fetch_assoc();

// Power & Connectivity specs
$power = $conn->query(
    "SELECT * FROM product_power_connectivity_specs WHERE product_id = $product_id"
)->fetch_assoc();

$product = $conn->query(
    "SELECT * FROM products WHERE id = $product_id"
)->fetch_assoc();
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

            <!-- Product Specs Section -->
            <div class="product-specs">
                <h3>Specifications</h3>

                <?php if ($processor): ?>
                    <h4>Processor</h4>
                    <ul>
                        <li>CPU: <?= htmlspecialchars($processor['cpu']) ?></li>
                        <li>Cores/Threads: <?= htmlspecialchars(preg_replace('/(\d+\s*cores)(\s+\d+\s*threads)/i', '$1, $2', $processor['cores_threads'])) ?>
                        <li>Clock Speed: <?= htmlspecialchars(preg_replace('/(\d+\.?\d*GHz\s*base)(\s+\d+\.?\d*GHz\s*boost)/i', '$1, $2', $processor['clock_speed'])) ?>
                        <li>Cache: <?= htmlspecialchars($processor['cache']) ?></li>
                    </ul>
                <?php endif; ?>

                <?php if ($memory): ?>
                    <h4>Memory</h4>
                    <ul>
                        <li>GPU: <?= htmlspecialchars($memory['gpu']) ?></li>
                        <li>RAM: <?= htmlspecialchars(preg_replace('/(\d+GB\s*\w*)(\s*\d+MHz)/i', '$1, $2', $memory['ram'])) ?>
                        <li>Storage: <?= htmlspecialchars($memory['storage']) ?></li>
                    </ul>
                <?php endif; ?>

                <?php if ($display): ?>
                    <h4>Display</h4>
                    <ul>
                        <li>Display: <?= htmlspecialchars($display['display']) ?></li>
                        <li>Resolution: <?= htmlspecialchars($display['resolution']) ?></li>
                        <li>Refresh Rate: <?= htmlspecialchars($display['refresh_rate']) ?></li>
                        <li>Anti-Glare: <?= htmlspecialchars($display['anti_glare']) ?></li>
                    </ul>
                <?php endif; ?>

                <?php if ($general): ?>
                    <h4>General</h4>
                    <ul>
                        <li>OS: <?= htmlspecialchars($general['os']) ?></li>
                        <li>Utility: <?= htmlspecialchars($general['utility']) ?></li>
                        <li>Weight: <?= htmlspecialchars($general['weight']) ?></li>
                        <li>Warranty: <?= htmlspecialchars($general['warranty']) ?></li>
                    </ul>
                <?php endif; ?>

                <?php if ($power): ?>
                    <h4>Power & Connectivity</h4>
                    <ul>
                        <li>Battery: <?= htmlspecialchars($power['battery']) ?></li>
                        <li>Charger: <?= htmlspecialchars($power['charger']) ?></li>
                        <li>Connectivity:
                            <?= htmlspecialchars(implode(', ', preg_split('/\s{2,}|\s(?=[A-Z])/u', $power['connectivity']))) ?>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="alert">Product not found!</div>
        <?php endif; ?>
    </div>
</main>

<?php include('../includes/footer.php'); ?>