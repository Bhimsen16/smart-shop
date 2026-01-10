<?php
require_once '../includes/init.php';
require_once 'admin_guard.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {

  $conn->begin_transaction();

  try {
    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
    $header = fgetcsv($file); // skip header

    while (($row = fgetcsv($file)) !== false) {
      if (count($row) < 24) {
        continue; // skip bad row
      }

      // destructure row
      [
        $product_name, $brand, $category, $price, $description, $image,
        $cpu, $cores_threads, $clock_speed, $cache,
        $gpu, $ram, $storage,
        $display, $resolution, $refresh_rate, $anti_glare,
        $os, $utility, $weight, $warranty,
        $battery, $charger, $connectivity
      ] = $row;

      // products insert
      $stmt = $conn->prepare(
        "INSERT INTO products (product_name, brand, category, price, description, image)
           VALUES (?, ?, ?, ?, ?, ?)"
      );

      $stmt->bind_param("sssiss",
        $product_name,
        $brand,
        $category,
        $price,
        $description,
        $image
      );

      $stmt->execute();
      $product_id = $stmt->insert_id;
      $stmt->close();

      // processor insert
      $stmt = $conn->prepare(
        "INSERT INTO product_processor_specs
          (product_id, cpu, cores_threads, clock_speed, cache)
          VALUES (?, ?, ?, ?, ?)"
      );

      $stmt->bind_param("issss",
        $product_id,
        $cpu,
        $cores_threads,
        $clock_speed,
        $cache
      );

      $stmt->execute();
      $stmt->close();

      // memory insert
      $stmt = $conn->prepare(
        "INSERT INTO product_memory_specs
          (product_id, gpu, gpu_tdp, ram, storage)
          VALUES (?, ?, ?, ?, ?)"
      );

      $stmt->bind_param("isss",
        $product_id,
        $gpu,
        $ram,
        $storage
      );

      $stmt->execute();
      $stmt->close();

      // display insert
      $stmt = $conn->prepare(
        "INSERT INTO product_display_specs
           (product_id, display, resolution, refresh_rate, anti_glare)
           VALUES (?, ?, ?, ?, ?)"
      );

      $stmt->bind_param("issss",
        $product_id,
        $display,
        $resolution,
        $refresh_rate,
        $anti_glare
      );

      $stmt->execute();
      $stmt->close();

      // general insert
      $stmt = $conn->prepare(
        "INSERT INTO product_general_specs
           (product_id, os, utility, weight, warranty)
           VALUES (?, ?, ?, ?, ?)"
      );

      $stmt->bind_param("issss",
        $product_id,
        $os,
        $utility,
        $weight,
        $warranty
      );

      $stmt->execute();
      $stmt->close();

      // power + connectivity insert
      $stmt = $conn->prepare(
        "INSERT INTO product_power_connectivity_specs
           (product_id, battery, charger, connectivity)
           VALUES (?, ?, ?, ?)"
      );

      $stmt->bind_param(
        "isss",
        $product_id,
        $battery,
        $charger,
        $connectivity
      );

      $stmt->execute();
      $stmt->close();
    }

    fclose($file);
    $conn->commit();
    $success = "CSV Import Successful. ";
  } catch (Exception $e) {
    $conn->rollback();
    die("Import failed: " . $e->getMessage());
  }
}
?>

<div class="admin-layout">
  <?php require_once 'sidebar.php'; ?>

  <div class="admin-main">
    <?php require_once '../includes/navbar.php'; ?>

    <main class="admin-content">
      <div class="admin-page">
        <h2>Import Products via CSV</h2>

        <?php if (!empty($success)): ?>
          <p style="color: green;"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
          <label>Upload Products CSV</label>

          <div class="file-input-wrap">
            <input type="file" name="csv_file" id="csvFile" accept=".csv" required hidden>

            <label for="csvFile" class="file-btn">Choose CSV File</label>
            <span id="fileName" class="file-name">No file selected</span>
          </div>

          <button type="submit">Import CSV</button>
        </form>
      </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>
  </div>
</div>