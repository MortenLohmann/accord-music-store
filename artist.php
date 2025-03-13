<?php
// Include database connection and helper functions
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'ai_artist_bio.php';

// Check if artist name is provided
if (!isset($_GET['name']) || empty($_GET['name'])) {
    // Redirect to catalog if no artist is specified
    header('Location: products.php');
    exit();
}

$artist_name = $_GET['name'];

// Get artist information and biography
$sql = "SELECT DISTINCT artist, genre, artist_bio FROM products WHERE artist = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $artist_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Artist not found
    header('Location: products.php');
    exit();
}

$artist_info = $result->fetch_assoc();

// If no bio exists, generate one
if (empty($artist_info['artist_bio']) && isset($_GET['generate_bio'])) {
    save_artist_bio($artist_name, $artist_info['genre']);
    
    // Refresh to get the new bio
    header("Location: artist.php?name=" . urlencode($artist_name));
    exit();
}

// Get all albums by this artist
$albums_sql = "SELECT * FROM products WHERE artist = ? ORDER BY release_date DESC";
$albums_stmt = $conn->prepare($albums_sql);
$albums_stmt->bind_param("s", $artist_name);
$albums_stmt->execute();
$albums_result = $albums_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artist_name); ?> | Accord Music Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .artist-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .artist-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .artist-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 30px;
        }
        .artist-name {
            font-size: 2.5em;
            margin: 0 0 10px 0;
        }
        .artist-genre {
            font-size: 1.2em;
            color: #666;
            margin: 0 0 20px 0;
        }
        .artist-bio {
            line-height: 1.6;
            margin-bottom: 40px;
            font-size: 1.1em;
        }
        .generate-bio-btn {
            background-color: #444;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .artist-albums {
            margin-top: 40px;
        }
        .artist-albums h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }
        .albums-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
    </style>
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
        • Hjem / Katalog / <?php echo htmlspecialchars($artist_name); ?> /
    </div>

    <main class="artist-container">
        <div class="artist-header">
            <img class="artist-image" src="artist-placeholder.jpg" alt="<?php echo htmlspecialchars($artist_name); ?>">
            <div>
                <h1 class="artist-name"><?php echo htmlspecialchars($artist_name); ?></h1>
                <?php if (!empty($artist_info['genre'])): ?>
                <p class="artist-genre"><?php echo htmlspecialchars($artist_info['genre']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="artist-bio">
            <?php if (!empty($artist_info['artist_bio'])): ?>
                <p><?php echo nl2br(htmlspecialchars($artist_info['artist_bio'])); ?></p>
            <?php else: ?>
                <p>No biography available for this artist.</p>
                <a href="artist.php?name=<?php echo urlencode($artist_name); ?>&generate_bio=1" class="generate-bio-btn">Generate Biography with AI</a>
            <?php endif; ?>
        </div>
        
        <div class="artist-albums">
            <h2>Albums by <?php echo htmlspecialchars($artist_name); ?></h2>
            
            <div class="albums-grid">
                <?php 
                if ($albums_result->num_rows > 0) {
                    while($album = $albums_result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<div class="album-cover">';
                        
                        // Get image URL (with fallback to placeholder)
                        $img_src = get_album_image_url($album["image_url"]);
                        echo '<img src="' . htmlspecialchars($img_src) . '" alt="' . htmlspecialchars($album["album_title"]) . '">';
                        
                        // Display release banner if release date is in the future
                        if (is_future_release($album["release_date"])) {
                            echo '<div class="release-banner">RELEASE: ' . format_danish_date($album["release_date"]) . '</div>';
                        }
                        
                        echo '</div>';
                        echo '<h2>' . htmlspecialchars($album["album_title"]) . '</h2>';
                        echo '<div class="format">' . htmlspecialchars($album["format"]) . '</div>';
                        echo '<div class="price">' . format_danish_price($album["price"]) . ' kr</div>';
                        echo '<a href="product.php?id=' . $album["id"] . '" class="view-button">VIS DETALJER</a>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No albums found for this artist.</p>";
                }
                ?>
            </div>
        </div>
    </main>
    
    <?php $conn->close(); ?>
</body>
</html> 