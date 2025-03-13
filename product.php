<?php
// Include database connection and helper functions
require_once 'db_connect.php';
require_once 'functions.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to products page if no valid ID
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];

// Query to fetch specific product
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Product not found
    header('Location: products.php');
    exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['album_title']); ?> | <?php echo htmlspecialchars($product['artist']); ?> | Accord Music Store</title>
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
        • Hjem / <a href="products.php">Katalog</a> / <a href="artist.php?name=<?php echo urlencode($product['artist']); ?>"><?php echo htmlspecialchars($product['artist']); ?></a> /
    </div>

    <main class="product-container">
        <div class="product-left">
            <div class="title-section">
                <h1><?php echo htmlspecialchars($product['album_title']); ?></h1>
                <h2><a href="artist.php?name=<?php echo urlencode($product['artist']); ?>"><?php echo htmlspecialchars($product['artist']); ?></a></h2>
            </div>
            <div class="album-cover">
                <?php 
                $image_url = isset($product['image_url']) ? $product['image_url'] : '';
                $img_src = get_album_image_url($image_url);
                ?>
                <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($product['album_title']); ?>">
                <?php
                // Display release banner if release date is in the future
                if (is_future_release($product['release_date'])) {
                    echo '<div class="release-banner">RELEASE: ' . format_danish_date($product['release_date']) . '</div>';
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
                    <span class="value"><?php echo htmlspecialchars($product['status']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">FORMAT:</span>
                    <span class="value"><?php echo htmlspecialchars($product['format']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">MEDIE ANTAL:</span>
                    <span class="value"><?php echo htmlspecialchars($product['media_count']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">GENRE/SUBGENRE:</span>
                    <span class="value"><?php echo htmlspecialchars($product['genre']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">UDGIVET:</span>
                    <span class="value">Europe <?php echo date('Y', strtotime($product['release_date'])); ?></span>
                </div>
                <div class="price-section">
                    <span class="price-label">PRIS:</span>
                    <span class="price"><?php echo format_danish_price($product['price']); ?></span>
                </div>
                
                <div class="add-to-cart">
                    <button class="cart-button">LÆG I KURV</button>
                </div>
            </div>
            
            <?php if (!empty($product['description'])): ?>
            <div class="product-description">
                <h3>Om albummet</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($product['artist_bio'])): ?>
            <div class="artist-bio-preview">
                <h3>Om <?php echo htmlspecialchars($product['artist']); ?></h3>
                <p>
                    <?php 
                    // Show first 150 characters of the bio
                    $preview = substr($product['artist_bio'], 0, 150);
                    echo nl2br(htmlspecialchars($preview));
                    if (strlen($product['artist_bio']) > 150) {
                        echo '... ';
                    }
                    ?>
                    <a href="artist.php?name=<?php echo urlencode($product['artist']); ?>">Læs mere</a>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php $conn->close(); ?>
</body>
</html> 