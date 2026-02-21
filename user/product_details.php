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

$images = [];

if (!empty($product['product_images'])) {
    $decoded = json_decode($product['product_images'], true);

    if (is_array($decoded)) {
        $images = $decoded;
    }
}

// fallback if gallery empty
if (empty($images) && !empty($product['image'])) {
    $images[] = $product['image'];
}
?>

<main>
    <div class="homepage-container">
        <?php if ($product): ?>
            <div class="product-detail-container">
                <!-- Product Image -->
                <div class="product-image">

                    <div class="main-image-wrapper">
                        <button class="img-arrow left">&#10094;</button>

                        <img id="mainImage"
                            src="../uploads/<?php echo htmlspecialchars($images[0]); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>">

                        <button class="img-arrow right">&#10095;</button>
                    </div>

                    <!-- Thumbnail Row -->
                    <div class="thumbnail-row">
                        <?php foreach ($images as $index => $img): ?>
                            <img
                                class="thumb <?= $index === 0 ? 'active' : '' ?>"
                                src="../uploads/<?= htmlspecialchars($img) ?>"
                                data-index="<?= $index ?>"
                                alt="<?= htmlspecialchars($product['product_name']) ?> thumbnail">
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                    <h5 class="product-brand">Brand: <?php echo htmlspecialchars($product['brand']); ?></h5>
                    <p class="product-price">Rs. <?php echo number_format($product['price']); ?></p>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                    <button class="btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            </div>

            <!-- Product Specs Section -->
            <div class="product-specs">
                <h3>Specifications</h3>

                <div class="spec-grid">
                    <div class="spec-block">
                        <h4 class="spec-title">Processor</h4>
                        <div class="spec-card">
                            <?php if ($processor): ?>
                                <table class="spec-table">
                                    <tr>
                                        <td>CPU</td>
                                        <td><?= htmlspecialchars($processor['cpu']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cores / Threads</td>
                                        <td><?= htmlspecialchars(preg_replace('/(\d+\s*cores)(\s+\d+\s*threads)/i', '$1, $2', $processor['cores_threads'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Clock Speed</td>
                                        <td><?= htmlspecialchars(preg_replace('/(\d+\.?\d*GHz\s*base)(\s+\d+\.?\d*GHz\s*boost)/i', '$1, $2', $processor['clock_speed'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cache</td>
                                        <td><?= htmlspecialchars($processor['cache']) ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="spec-grid">
                    <div class="spec-block">
                        <h4 class="spec-title">Memory</h4>
                        <div class="spec-card">
                            <?php if ($memory): ?>
                                <table class="spec-table">
                                    <tr>
                                        <td>GPU</td>
                                        <td><?= htmlspecialchars($memory['gpu']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>RAM</td>
                                        <td><?= htmlspecialchars(preg_replace('/(\d+GB\s*\w*)(\s*\d+MHz)/i', '$1, $2', $memory['ram'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Storage</td>
                                        <td><?= htmlspecialchars($memory['storage']) ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="spec-grid">
                    <div class="spec-block">
                        <h4 class="spec-title">Display</h4>
                        <div class="spec-card">
                            <?php if ($display): ?>
                                <table class="spec-table">
                                    <tr>
                                        <td>Display</td>
                                        <td><?= htmlspecialchars($display['display']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Resolution</td>
                                        <td><?= htmlspecialchars($display['resolution']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Refresh Rate</td>
                                        <td><?= htmlspecialchars($display['refresh_rate']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Anti-Glare</td>
                                        <td><?= htmlspecialchars($display['anti_glare']) ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="spec-grid">
                    <div class="spec-block">
                        <h4 class="spec-title">General</h4>
                        <div class="spec-card">
                            <?php if ($general): ?>
                                <table class="spec-table">
                                    <tr>
                                        <td>OS</td>
                                        <td><?= htmlspecialchars($general['os']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Utility</td>
                                        <td><?= htmlspecialchars($general['utility']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Weight</td>
                                        <td><?= htmlspecialchars($general['weight']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Warranty</td>
                                        <td><?= htmlspecialchars($general['warranty']) ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="spec-grid">
                    <div class="spec-block">
                        <h4 class="spec-title">Power & Connectivity</h4>
                        <div class="spec-card">
                            <?php if ($power): ?>
                                <table class="spec-table">
                                    <tr>
                                        <td>Battery</td>
                                        <td><?= htmlspecialchars($power['battery']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Charger</td>
                                        <td><?= htmlspecialchars($power['charger']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Connectivity</td>
                                        <td><?= htmlspecialchars(implode(', ', explode('|', $power['connectivity']))) ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert">Product not found!</div>
        <?php endif; ?>
    </div>
</main>

<?php include('../includes/footer.php'); ?>
<script src="../assets/js/product_details.js"></script>