<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isset($_GET['id'])) die("Product ID missing!");

$id = $_GET['id'];

// Optional: remove image file
$stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if($product && !empty($product['image'])) {
    $file = __DIR__ . "/../uploads/" . $product['image'];
    if(file_exists($file)) unlink($file);
}

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view_products.php");
exit;
?>

<?php require_once '../includes/footer.php'; ?>
