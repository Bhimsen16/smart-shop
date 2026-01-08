<?php
require_once '../includes/init.php';
require_once '../config/db.php';

if (!isset($_FILES['csv_file'])) {
    die('No file uploaded');
}

$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
$header = fgetcsv($file); // skip header

//loop rows
while (($row = fgetcsv($file)) !== false) {

    [
      $product_name, $brand, $category, $price, $description, $image,
      $cpu, $cores_threads, $clock_speed, $cache,
      $gpu, $ram, $storage,
      $display, $resolution, $refresh_rate, $anti_glare,
      $os, $utility, $weight, $warranty,
      $battery, $charger, $connectivity
    ] = $row;

    //insert into products
    $stmt = $conn->prepare(
  "INSERT INTO products (product_name, brand, category, price, description, image)
   VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
  "sssiss",
  $product_name, $brand, $category, $price, $description, $image
);
$stmt->execute();

$product_id = $stmt->insert_id;

//insert processor specs
$conn->prepare(
  "INSERT INTO product_processor_specs
   (product_id, cpu, cores_threads, clock_speed, cache)
   VALUES (?, ?, ?, ?, ?)"
)->bind_param(
  "issss",
  $product_id, $cpu, $cores_threads, $clock_speed, $cache
)->execute();

//insert memory specs
$conn->prepare(
  "INSERT INTO product_memory_specs
   (product_id, gpu, ram, storage)
   VALUES (?, ?, ?, ?)"
)->bind_param(
  "isss",
  $product_id, $gpu, $ram, $storage
)->execute();

//display specs
$conn->prepare(
  "INSERT INTO product_display_specs
   (product_id, display, resolution, refresh_rate, anti_glare)
   VALUES (?, ?, ?, ?, ?)"
)->bind_param(
  "issss",
  $product_id, $display, $resolution, $refresh_rate, $anti_glare
)->execute();

//general specs
$conn->prepare(
  "INSERT INTO product_general_specs
   (product_id, os, utility, weight, warranty)
   VALUES (?, ?, ?, ?, ?)"
)->bind_param(
  "issss",
  $product_id, $os, $utility, $weight, $warranty
)->execute();

//connectivity specs
$conn->prepare(
  "INSERT INTO product_power_connectivity_specs
   (product_id, battery, charger, connectivity)
   VALUES (?, ?, ?, ?)"
)->bind_param(
  "isss",
  $product_id, $battery, $charger, $connectivity
)->execute();
}
fclose($file);

echo "CSV Import Successful.";
