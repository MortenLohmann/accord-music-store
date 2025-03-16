<?php
// Database connection parameters
$servername = "localhost";
$username = "accord_user";
$password = "accord_password";
$dbname = "accord_db";
$port = 3306;

// Create connection with error handling
try {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    // Set character set
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // If connection fails, set $conn to null and continue
    $conn = null;
    // Log the error but don't display it
    error_log("Database connection failed: " . $e->getMessage());
}

// You can use $conn throughout your application
// Remember to close the connection when done: $conn->close();
?> 