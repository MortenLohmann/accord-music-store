<?php
// Include database connection
require_once 'db_connect.php';

// Set headers to return JSON
header('Content-Type: application/json');

// Check if ID is provided
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Query to fetch product
$sql = "SELECT * FROM products WHERE id = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Convert to JSON and output
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
    } else {
        // Product not found
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }
} catch (Exception $e) {
    // Log the detailed error
    error_log("Database error: " . $e->getMessage());
    
    // Return error message
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}

// Close the connection
$conn->close();
?> 