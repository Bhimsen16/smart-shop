<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<div class="admin-layout">
    <?php require_once 'sidebar.php'; ?>

    <div class="admin-main">
        <?php require_once('../includes/navbar.php'); ?>

        <main class="admin-content">
            <div class="admin-page admin-products">
                <h2 class="admin-title">Products</h2>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="table-img">
                                </td>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td>Rs. <?= number_format($row['price']) ?></td>
                                <td><?= htmlspecialchars($row['category'] ?: 'Uncategorized') ?></td>
                                <td class="actions">
                                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                    <a href="delete_product.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Delete this product?')"
                                        class="btn-delete">Delete</a>
                                    <a href="manage_images.php?id=<?= $row['id'] ?>" class="btn-images">
                                        Manage Images
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>