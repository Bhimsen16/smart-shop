<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';

// 1. Validation & Header Check
if (!isset($_GET['id'])) {
    die("Product ID missing!");
}
$id = (int)$_GET['id']; // Cast to int for safety

// 2. Fetch image to delete the file from the server
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if ($product && !empty($product['image'])) {
    $file = __DIR__ . "/../uploads/" . $product['image'];
    if (file_exists($file)) {
        unlink($file);
    }
}

// 3. Delete dependent rows first (Child Tables)
$dependentTables = [
    'cart',
    'product_processor_specs',
    'product_memory_specs',
    'product_display_specs',
    'product_general_specs',
    'product_power_connectivity_specs'
];

foreach ($dependentTables as $table) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// 4. Finally, delete the product itself (Parent Table)
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// 5. Redirect BEFORE any HTML is sent
header("Location: view_products.php?msg=deleted");
exit;
