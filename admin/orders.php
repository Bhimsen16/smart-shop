<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';
?>

<div class="admin-layout">
    <?php require_once 'sidebar.php'; ?>

    <div class="admin-main">
        <?php require_once('../includes/navbar.php'); ?>

        <main class="admin-content">
            <div class="orders-container">
                <h2>Orders Management</h2>

                <?php
                $query = "SELECT orders.*, users.username 
                      FROM orders 
                      JOIN users ON orders.user_id = users.id
                      ORDER BY orders.id DESC";

                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die("Query Failed: " . mysqli_error($conn));
                }
                ?>

                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Placed On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="order-row <?= $row['status'] ?>">
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>Rs. <?= number_format($row['total_amount']) ?></td>
                                <td class="status"><?= ucfirst($row['status']) ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <td class="actions">
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <a href="approve_order.php?id=<?= $row['id'] ?>" class="btn-approve">Approve</a>
                                        <a href="reject_order.php?id=<?= $row['id'] ?>" class="btn-reject">Reject</a>
                                    <?php elseif ($row['status'] === 'approved'): ?>
                                        <span class="status-approved">Approved</span>
                                    <?php elseif ($row['status'] === 'rejected'): ?>
                                        <span class="status-rejected">Rejected</span>
                                    <?php endif; ?>
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