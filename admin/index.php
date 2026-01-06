<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

/* ---- DASHBOARD COUNTS ---- */
// Total products
$productResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$productCount = mysqli_fetch_assoc($productResult)['total'] ?? 0;

// Total users
$userResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCount = mysqli_fetch_assoc($userResult)['total'] ?? 0;
?>

<div class="admin-layout">
    <?php require_once 'sidebar.php'; ?>

    <div class="admin-main">
        <?php require_once('../includes/navbar.php'); ?>

        <main class="admin-content">
            <h1 class="admin-title">Dashboard</h1>

            <div class="admin-cards">
                <div class="admin-card">
                    <h3><?= $productCount ?></h3>
                    <p>Total Products</p>
                </div>

                <div class="admin-card">
                    <h3><?= $userCount ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </main>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>
