<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    die("Product ID missing!");
}

$id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Update product on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'] ?? $product['category'];

    // Check if new image uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . "/../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image_name = $product['image']; // keep old image
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, brand=?, price=?, description=?, image=?, category=? WHERE id=?");
    $stmt->bind_param("ssdsssi", $name, $brand, $price, $description, $image_name, $category, $id);

    if ($stmt->execute()) {
        header("Location: view_products.php?msg=Product added successfully");
        exit();
    } else {
        header("Location: view_products.php?msg=" . urlencode("Error adding product: " . $conn->error));
        exit();
    }
}
?>

<div class="admin-layout">
    <?php require_once 'sidebar.php'; ?>

    <div class="admin-main">
        <?php require_once('../includes/navbar.php'); ?>
        
        <main class="admin-content">
            <div class="admin-page">
                <h2 class="admin-title">Edit Product</h2>

                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

                    <label>Brand</label>
                    <input type="text" name="brand" value="<?= htmlspecialchars($product['brand']) ?>">

                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

                    <label>Category</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>">

                    <label>Description</label>
                    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

                    <label>Product Image</label>
                    <input type="file" name="image">
                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" width="150" style="margin-top:10px;"><br><br>

                    <button type="submit">Update Product</button>
                </form>
            </div>
        </main>
        
        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>