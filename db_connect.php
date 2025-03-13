<?php
// Include configuration file
require_once 'config.php';

// Database connection parameters from config
$servername = DB_HOST;
$port = DB_PORT;
$username = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character set
$conn->set_charset("utf8mb4");

// You can use $conn throughout your application
// Remember to close the connection when done: $conn->close();
?> 