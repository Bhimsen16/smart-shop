<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
if (!$result) {
  die("Failed to fetch users: " . $conn->error);
}
?>

<div class="admin-layout">
  <?php require_once 'sidebar.php'; ?>

  <div class="admin-main">
    <?php require_once('../includes/navbar.php'); ?>

    <main class="admin-content user-management">
      <h2 class="admin-title">Users</h2>
      <div class="admin-table-wrap">
        <div class="admin-card">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>

              <?php while ($user = $result->fetch_assoc()) { ?>
                <tr>
                  <td><?= $user['id'] ?></td>
                  <td><?= htmlspecialchars($user['username']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>

                  <td>
                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                      <?= ucfirst($user['role']) ?>
                    </span>
                  </td>

                  <td>
                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                      <?= ucfirst($user['status']) ?>
                    </span>
                  </td>

                  <td><?= $user['created_at'] ?></td>

                  <td class="actions">

                    <!-- Toggle Role -->
                    <?php if ($user['role'] === 'user') { ?>
                      <a class="btn small"
                        href="user_actions.php?action=make_admin&id=<?= $user['id'] ?>">
                        Make Admin
                      </a>
                    <?php } else { ?>
                      <a class="btn small warning"
                        href="user_actions.php?action=make_user&id=<?= $user['id'] ?>">
                        Make User
                      </a>
                    <?php } ?>

                    <!-- Toggle Status -->
                    <?php if ($user['status'] === 'active') { ?>
                      <a class="btn small warning"
                        href="user_actions.php?action=deactivate&id=<?= $user['id'] ?>">
                        Deactivate
                      </a>
                    <?php } else { ?>
                      <a class="btn small"
                        href="user_actions.php?action=activate&id=<?= $user['id'] ?>">
                        Activate
                      </a>
                    <?php } ?>

                    <!-- Delete -->
                    <a class="btn small danger"
                      href="user_actions.php?action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">
                      Delete
                    </a>
                  </td>
                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>
  </div>
</div>