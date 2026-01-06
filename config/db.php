<?php
$servername = "localhost";
$username = "root"; // or 'smartuser' if you created one
$password = "@Easygame1618";
$database = "smartshop";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully"; // Uncomment to test
?>