<?php
// Include database connection and helper functions
require_once 'db_connect.php';
require_once 'functions.php';

// Query to fetch products
$sql = "SELECT id, artist, album_title, format, price, image_url, release_date FROM products";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Accord Music Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">ACCORD</div>
            <nav class="main-nav">
                <ul>
                    <li><a href="products.php" class="active">KATALOG</a></li>
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
        • Hjem / Katalog /
    </div>

    <main class="products-grid">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<div class="album-cover">';
                
                // Get image URL (with fallback to placeholder)
                $img_src = get_album_image_url($row["image_url"]);
                echo '<img src="' . htmlspecialchars($img_src) . '" alt="' . htmlspecialchars($row["album_title"]) . '">';
                
                // Display release banner if release date is in the future
                if (is_future_release($row["release_date"])) {
                    echo '<div class="release-banner">RELEASE: ' . format_danish_date($row["release_date"]) . '</div>';
                }
                
                echo '</div>';
                echo '<h2>' . htmlspecialchars($row["album_title"]) . '</h2>';
                echo '<h3><a href="artist.php?name=' . urlencode($row["artist"]) . '">' . htmlspecialchars($row["artist"]) . '</a></h3>';
                echo '<div class="format">' . htmlspecialchars($row["format"]) . '</div>';
                echo '<div class="price">' . format_danish_price($row["price"]) . ' kr</div>';
                echo '<a href="product.php?id=' . $row["id"] . '" class="view-button">VIS DETALJER</a>';
                echo '</div>';
            }
        } else {
            echo "<p class='no-products'>No products found</p>";
        }
        $conn->close();
        ?>
    </main>
</body>
</html> 