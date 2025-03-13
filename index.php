<?php
// Include database connection and helper functions
require_once 'db_connect.php';
require_once 'functions.php';

// If you need to fetch a specific product (e.g., for homepage feature)
$featured_product_id = 1; // Example: Van Halen album
$sql = "SELECT * FROM products WHERE id = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $featured_product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} catch (Exception $e) {
    // Log the detailed error
    error_log("Database error: " . $e->getMessage());
    // We'll show a generic product if there's an error
    $product = [
        'album_title' => 'LIVE IN DALLAS 1991',
        'artist' => 'Van Halen',
        'format' => 'CD, Album',
        'media_count' => '1',
        'genre' => 'Heavy',
        'release_date' => '2025-03-28',
        'price' => '139.95',
        'status' => 'Ny'
    ];
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($product) ? htmlspecialchars($product['album_title']) : 'Van Halen - Live in Dallas 1991'; ?> | Accord Music Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">ACCORD</div>
            <nav class="main-nav">
                <ul>
                    <li><a href="products.php">KATALOG</a></li>
                    <li><a href="#">BUTIKKER ▼</a></li>
                    <li><a href="#">SÆLG & BYT</a></li>
                    <li><a href="#">GRADUERING</a></li>
                    <li><a href="#">ACCORD</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-bottom">
            <div class="search-cart">
                <span>SØG 🔍</span>
                <span>KASSE(0) 📦</span>
            </div>
        </div>
    </header>

    <div class="breadcrumb">
        • Hjem / Preorders /
    </div>

    <main class="product-container">
        <div class="product-left">
            <div class="title-section">
                <h1><?php echo isset($product) ? htmlspecialchars($product['album_title']) : 'LIVE IN DALLAS 1991'; ?></h1>
                <h2><?php echo isset($product) ? htmlspecialchars($product['artist']) : 'Van Halen'; ?></h2>
            </div>
            <div class="album-cover">
                <?php 
                $image_url = isset($product['image_url']) ? $product['image_url'] : '';
                $img_src = get_album_image_url($image_url);
                ?>
                <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo isset($product) ? htmlspecialchars($product['album_title']) : 'Album Cover'; ?>">
                <?php 
                if (isset($product['release_date']) && is_future_release($product['release_date'])) {
                    echo '<div class="release-banner">RELEASE: ' . format_danish_date($product['release_date']) . '</div>';
                } elseif (!isset($product['release_date'])) {
                    echo '<div class="release-banner">RELEASE: 28.3.2025</div>';
                }
                ?>
            </div>
        </div>

        <div class="product-right">
            <div class="tabs">
                <button class="tab active">⬤ INFO</button>
                <button class="tab">▶ TRACKLIST</button>
            </div>
            <div class="product-details">
                <div class="detail-row">
                    <span class="label">STAND, MEDIE/COVER:</span>
                    <span class="value"><?php echo isset($product) ? htmlspecialchars($product['status']) : 'Ny'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">FORMAT:</span>
                    <span class="value"><?php echo isset($product) ? htmlspecialchars($product['format']) : 'CD, Album'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">MEDIE ANTAL:</span>
                    <span class="value"><?php echo isset($product) ? htmlspecialchars($product['media_count']) : '1'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">GENRE/SUBGENRE:</span>
                    <span class="value"><?php echo isset($product) ? htmlspecialchars($product['genre']) : 'Heavy'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">UDGIVET:</span>
                    <span class="value">Europe <?php echo isset($product['release_date']) ? date('Y', strtotime($product['release_date'])) : '2025'; ?></span>
                </div>
                <div class="price-section">
                    <span class="price-label">PRIS:</span>
                    <span class="price"><?php echo isset($product) ? format_danish_price($product['price']) : '139,95'; ?></span>
                </div>
            </div>
        </div>
    </main>
    
    <?php if (isset($conn)) $conn->close(); ?>
</body>
</html> 