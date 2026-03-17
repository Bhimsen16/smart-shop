<!-- Auth Dropdown -->
<div class="auth-wrapper">
  <div class="auth-box" id="authBox">
    <!-- Login Form -->
    <div id="loginForm" style="<?php echo $show_register ? 'display:none;' : ''; ?>">
      <form method="POST" action="../auth/login.php">

        <label>Email</label>
        <input type="email" name="email" placeholder="Email" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <?php if ($login_error): ?>
          <p class="error">Invalid email or password</p>
        <?php endif; ?>

        <button type="submit">Login</button>

        <p class="switch">
          Don’t have an account?
          <a href="#" onclick="showRegister(); return false;">Register</a>
        </p>

      </form>
    </div>

    <!-- Register Form -->
    <div id="registerForm" style="<?php echo $show_register ? '' : 'display:none;'; ?>">
      <form method="POST" action="../auth/register.php">

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" required>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Register</button>

        <p class="switch">
          Already have an account?
          <a href="#" onclick="showLogin(); return false;">Login</a>
        </p>

      </form>
    </div>
  </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Shop</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/products.css">
    <link rel="stylesheet" href="../assets/css/product_details.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/includes.css">

    <!-- Only for admins -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false): ?>
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/user_management.css">
    <?php endif; ?>

    <!-- Custom JS -->
    <script src="../assets/js/script.js" defer></script>
</head>

<body>
    <!-- Notification (must be inside body, not in head) -->
    <div id="notification" class="notification"></div>