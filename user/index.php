<?php
require_once '../includes/init.php';
include('../includes/header.php');
include('../includes/navbar.php');

$show_register = ($_GET['tab'] ?? '') === 'register';
$login_error = isset($_GET['login_error']);
$registered  = isset($_GET['registered']);
//$active_tab  = $_GET['tab'] ?? 'login';

$result = $conn->query("SELECT * FROM products");
if (!$result) {
  die("Database query failed: " . $conn->error);
}
?>

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

<?php if ($login_error || $show_register || $registered): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const authBox = document.getElementById('authBox');
      if (authBox) {
        authBox.classList.add('show');
        document.body.classList.add('auth-active');
      }
    });
  </script>
<?php endif; ?>

<div class="homepage-container">
  <section class="hero-section">
    <div class="hero-left">
      <h1>Find the perfect laptop for your needs</h1>
      <p>Compare specs, choose smart — Smart Shop helps you pick the right laptop easily.</p>

      <div class="hero-buttons">
        <a href="products.php" class="btn-primary">Browse All</a>
        <a href="products.php?category=gaming" class="btn-secondary">Gaming Laptops</a>
        <a href="products.php?category=everyday" class="btn-secondary">Everyday Use</a>
      </div>

      <div class="trust-badges">
        <span>✔ Genuine Specs</span>
        <span>✔ Secure Purchase</span>
        <span>✔ Fast Delivery</span>
      </div>
    </div>

    <div class="hero-right">
      <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8" alt="Laptop">
    </div>
  </section>

  <!-- ===================== CATEGORIES ===================== -->
  <section class="categories-section">
    <a href="products.php?category=gaming" class="category-link">
      <div class="category-card">
        <img src="https://img.icons8.com/fluency/48/000000/game-controller.png">
        <div>
          <h4>Gaming</h4>
          <p>Performance beasts</p>
        </div>
      </div>
    </a>

    <a href="products.php?category=everyday" class="category-link">
      <div class="category-card">
        <img src="https://img.icons8.com/fluency/48/000000/laptop.png">
        <div>
          <h4>Everyday</h4>
          <p>For students & office</p>
        </div>
      </div>
    </a>

    <a href="products.php?category=budget" class="category-link">
      <div class="category-card">
        <img src="https://img.icons8.com/fluency/48/000000/wallet.png">
        <div>
          <h4>Budget</h4>
          <p>Value for money</p>
        </div>
      </div>
    </a>

    <a href="products.php?category=productivity" class="category-link">
      <div class="category-card">
        <img src="https://img.icons8.com/fluency/48/000000/briefcase.png">
        <div>
          <h4>Productivity</h4>
          <p>Work & creation</p>
        </div>
      </div>
    </a>
  </section>

  <!-- ===================== FEATURED PRODUCTS ===================== -->
  <section class="featured-section">
    <div class="section-header">
      <h2>Featured Laptops</h2>

      <select id="sortSelect" onchange="sortProducts()">
        <option value="default">Sort: Featured</option>
        <option value="asc">Price Low → High</option>
        <option value="desc">Price High → Low</option>
      </select>
    </div>

    <div class="product-grid">

      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="product-card">

          <!-- Image clickable -->
          <a href="product_details.php?id=<?php echo $row['id']; ?>">
            <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
          </a>

          <!-- Product Name clickable -->
          <h3>
            <a href="product_details.php?id=<?php echo $row['id']; ?>">
              <?php echo htmlspecialchars($row['product_name']); ?>
            </a>
          </h3>

          <p class="price">Rs. <?php echo number_format($row['price']); ?></p>
        </div>
      <?php } ?>

    </div>
  </section>
</div>

<?php include('../includes/footer.php'); ?>