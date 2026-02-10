<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

// Admin access enforced via admin_guard.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Image upload
    $target_dir = __DIR__ . "/../uploads/"; // Absolute path, safer
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // creates folder with permissions if missing
    }

    $image_ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $image_name = uniqid('prod_', true) . '.' . $image_ext;

    $target_file = $target_dir . $image_name;

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array(strtolower($image_ext), $allowed)) {
        die("Invalid image type.");
    }

    if (!is_numeric($price)) {
        die("Invalid price.");
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("Error uploading image.");
    }

    $category = trim($_POST['category'] ?? '');

    // Insert into DB
    $stmt = $conn->prepare(
        "INSERT INTO products (product_name, brand, price, description, image, category)
   VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssdsss", $name, $brand, $price, $description, $image_name, $category);

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
        <?php require_once '../includes/navbar.php'; ?>

        <main class="admin-content">
            <div class="admin-page admin-form-page">
                <h2 class="admin-title">Add New Product</h2>

                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <label>Product Name</label>
                    <input type="text" name="name" required>

                    <label>Brand</label>
                    <input type="text" name="brand">

                    <label>Category</label>
                    <input type="text" name="category">

                    <label>Price</label>
                    <input type="number" step="0.01" name="price" required>

                    <label>Description</label>
                    <textarea name="description"></textarea>

                    <label>Product Image</label>
                    <input type="file" name="image" onchange="previewImage(event)" required>
                    <img id="imagePreview">

                    <button type="submit">Add Product</button>
                </form>
            </div>
        </main>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = 'block';
    }
</script>