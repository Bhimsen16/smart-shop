<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy session
session_destroy();

// Optional: regenerate session ID
session_regenerate_id(true);

// Redirect to homepage
header("Location: ../user/index.php");
exit;
