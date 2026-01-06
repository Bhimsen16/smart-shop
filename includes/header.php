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
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/includes.css">

    <!-- Only for admins -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false): ?>
        <link rel="stylesheet" href="../assets/css/admin.css">
    <?php endif; ?>

    <!-- Custom JS -->
    <script src="../assets/js/script.js" defer></script>
</head>

<body>
    <!-- Notification (must be inside body, not in head) -->
    <div id="notification" class="notification"></div>