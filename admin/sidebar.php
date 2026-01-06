<aside class="admin-sidebar">
  <div class="sidebar-top">
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="fa-solid fa-bars"></i>
    </button>
  </div>

  <nav class="admin-nav">
    <a href="index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
      <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
    </a>

    <a href="view_products.php" class="<?= $currentPage == 'view_products.php' ? 'active' : '' ?>">
      <i class="fa-solid fa-box"></i><span>Products</span>
    </a>

    <a href="add_product.php" class="<?= $currentPage == 'add_product.php' ? 'active' : '' ?>">
      <i class="fa-solid fa-plus"></i><span>Add Product</span>
    </a>

    <a href="users.php" class="<?= $currentPage == 'users.php' ? 'active' : '' ?>">
      <i class="fa-solid fa-users"></i><span>Users</span>
    </a>

    <a href="../auth/logout.php">
      <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
    </a>
  </nav>
</aside>