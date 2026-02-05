<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: view_products.php");
    exit;
}

$product = $conn->query(
    "SELECT product_name, product_images FROM products WHERE id = $product_id"
)->fetch_assoc();

if (!$product) {
    echo "<div class='alert'>Product not found</div>";
    exit;
}

$images = [];

if (!empty($product['product_images'])) {
    $decoded = json_decode($product['product_images'], true);
    if (is_array($decoded)) {
        $images = $decoded;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {

    $upload_dir = '../uploads/';
    $new_images = [];

    foreach ($_FILES['images']['name'] as $key => $name) {

        if ($_FILES['images']['error'][$key] === 0) {

            $tmp  = $_FILES['images']['tmp_name'][$key];
            $ext  = pathinfo($name, PATHINFO_EXTENSION);

            $safe_name = uniqid('prod_', true) . '.' . $ext;

            if (move_uploaded_file($tmp, $upload_dir . $safe_name)) {
                $new_images[] = $safe_name;
            }
        }
    }

    // merge old + new
    $final_images = array_merge($images, $new_images);

    $json = json_encode($final_images);

    $conn->query(
        "UPDATE products SET product_images = '$json' WHERE id = $product_id"
    );

    $_SESSION['success'] = "Images uploaded successfully. ";
    header("Location: manage_images.php?id=$product_id");
    exit;
}
?>

<div class="admin-layout">
    <?php require_once 'sidebar.php'; ?>

    <div class="admin-main">
        <?php require_once '../includes/navbar.php'; ?>

        <main class="admin-content">
            <div class="admin-page">

                <!-- FLASH SUCCESS MESSAGE -->
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="flash-success">
                        <?= $_SESSION['success'] ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <h2 class="admin-title">
                    Manage Images – <?= htmlspecialchars($product['product_name']) ?>
                </h2>

                <!-- Existing Images -->
                <div class="image-grid">
                    <?php if ($images): ?>
                        <?php foreach ($images as $img): ?>
                            <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No images uploaded yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Upload Form -->
                <form method="post" enctype="multipart/form-data" class="image-upload-form">
                    <label>Select Images</label>
                    <input type="file" name="images[]" multiple required>

                    <button type="submit">Upload Images</button>
                </form>

                <a href="view_products.php" class="btn-back">← Back to Products</a>

            </div>
        </main>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>