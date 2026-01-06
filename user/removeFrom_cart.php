<?php
require_once '../includes/init.php';

// Check if cart ID is provided
if(isset($_GET['id'])){
    $cart_id = $_GET['id'];

    // Delete the item from the cart
    $delete = mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

    // Redirect back to cart page
    header("Location: cart.php");
    exit;
} else {
    // If no ID, just go back to cart
    header("Location: cart.php");
    exit;
}
?>
