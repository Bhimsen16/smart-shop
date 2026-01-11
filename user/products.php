<div class="products-container">
  <?php while ($row = $result->fetch_assoc()) { ?>
    <div class="product-card">

      <!-- Image clickable -->
      <a href="product_details.php?id=<?php echo $row['id']; ?>" class="product-img">
        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
      </a>

      <!-- Info -->
      <div class="product-info">
        <h4>
          <a href="product_details.php?id=<?php echo $row['id']; ?>">
            <?php echo htmlspecialchars($row['product_name']); ?>
          </a>
        </h4>

        <p class="price">Rs. <?php echo number_format($row['price']); ?></p>

        <!-- Listing specs -->
        <ul class="listing-specs">
          <?php
          $specs = explode('|', $row['listing_specs']);
          foreach ($specs as $spec) {
            echo '<li>' . htmlspecialchars(trim($spec)) . '</li>';
          }
          ?>
        </ul>

        <a href="product_details.php?id=<?php echo $row['id']; ?>" class="view-link">
          View details →
        </a>

      </div>
    </div>
  <?php } ?>
</div>
