<?php
// navbar should ONLY use variables, not create them
$cart_count = $cart_count ?? 0;

$username = $_SESSION['username'] ?? '';
$email    = $_SESSION['email'] ?? '';

$avatar_letter = $username !== ''
  ? strtoupper($username[0])
  : 'U';
?>

<nav class="navbar">
  <div class="navbar-container">
    <a class="logo" href="../user/index.php">
      <img src="../assets/images/logo.png" alt="Smart Shop Logo">
    </a>

    <div class="nav-right">

      <!-- Products button -->
      <a class="nav-item" href="../user/products.php">
        <i class="fa-solid fa-laptop"></i>
        <span class="nav-text">Laptops</span>
      </a>
      
      <a class="nav-item" href="../user/cart.php">
        <span class="cart-icon-wrap">
          <i class="fa-solid fa-cart-shopping"></i>
          <sup class="cart-badge"><?= $cart_count ?></sup>
        </span>
        <span class="nav-text">Cart</span>
      </a>

      <?php if ($role === 'admin'): ?>
        <a class="nav-item" href="../admin/index.php">
          <i class="fa-solid fa-user-shield"></i>
          <span class="nav-text">Admin</span>
        </a>
      <?php endif; ?>

      <?php if ($user_id): ?>
        <div class="nav-user">
          <div class="user-trigger" onclick="toggleUserMenu()">
            <div class="avatar"><?= $avatar_letter ?></div>

            <div class="user-text">
              <span class="username"><?= htmlspecialchars($username) ?></span>
              <?php if ($role === 'admin'): ?>
                <small class="role">Administrator</small>
              <?php endif; ?>
            </div>

            <i class="fa-solid fa-chevron-down caret"></i>
          </div>

          <div class="user-menu" id="userMenu">
            <a href="../auth/logout.php">
              <i class="fa-solid fa-right-from-bracket"></i>
              Logout
            </a>
          </div>
        </div>

      <?php else: ?>
        <a class="nav-item" href="#" onclick="toggleAuthBox(); return false;">
          <i class="fa-solid fa-user"></i>
          <span>Login</span>
        </a>

      <?php endif; ?>
    </div>
  </div>
</nav>