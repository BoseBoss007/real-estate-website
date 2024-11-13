<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // If you have set a password for MySQL, enter it here
$dbname = "home_db1"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
