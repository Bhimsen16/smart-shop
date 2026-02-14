<?php
require_once '../includes/init.php';
include('../includes/header.php');
include('../includes/navbar.php');

$where = [];
$order = "ORDER BY created_at DESC";

// SEARCH
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $conn->real_escape_string($_GET['search']);
    $where[] = "(product_name LIKE '%$search%' 
                OR brand LIKE '%$search%')";
}

// CATEGORY
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $category = $conn->real_escape_string($_GET['category']);
    $where[] = "category = '$category'";
}

// SORT
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'asc') {
        $order = "ORDER BY price ASC";
    } elseif ($_GET['sort'] === 'desc') {
        $order = "ORDER BY price DESC";
    }
}

$query = "SELECT * FROM products";

if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}

$query .= " $order";

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