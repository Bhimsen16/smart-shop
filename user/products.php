<?php
require_once '../includes/init.php';
include('../includes/header.php');
include('../includes/navbar.php');

// FETCH PRODUCTS
$query = "SELECT id, product_name, price, image, listing_specs 
          FROM products 
          ORDER BY created_at DESC";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<div class="products-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-card">

            <!-- Image clickable -->
            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="product-image">
                <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
            </a>

            <!-- Info -->
            <div class="product-info">
                <h4 class="product-title">
                    <a href="product_details.php?id=<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['product_name']); ?>
                    </a>
                </h4>

                <p class="price">Rs. <?php echo number_format($row['price']); ?></p>

                <!-- Listing specs -->
                <ul class="listing-specs">
                    <?php
                    $specs = explode('|', $row['listing_specs']);
                    $specs = array_slice($specs, 0, 8); // limit to max 8
                    foreach ($specs as $spec):
                    ?>
                        <li><?= htmlspecialchars(trim($spec)) ?></li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include('../includes/footer.php'); ?>