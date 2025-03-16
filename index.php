<?php
// Include database connection and helper functions
require_once 'db_connect.php';
require_once 'functions.php';

// Default product data
$product = [
    'album_title' => 'LIVE IN DALLAS 1991',
    'artist' => 'Van Halen',
    'format' => 'Rød Vinyl, Album',
    'media_count' => '1',
    'genre' => 'Heavy Metal',
    'release_date' => '2025-03-28',
    'price' => '139.95',
    'status' => 'Ny'
];

// Only try to fetch from database if connection exists
if (isset($conn) && $conn !== null) {
    // If you need to fetch a specific product (e.g., for homepage feature)
    $featured_product_id = 1; // Example: Van Halen album
    $sql = "SELECT * FROM products WHERE id = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $featured_product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $db_product = $result->fetch_assoc();
        if ($db_product) {
            $product = $db_product;
        }
    } catch (Exception $e) {
        // Log the detailed error
        error_log("Database error: " . $e->getMessage());
        // We'll use the default product data defined above
    }
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
            <div class="album-artwork">
                <img src="images/van-halen-dallas-1991.jpg" alt="Van Halen Live in Dallas 1991 - Limited Edition Red Vinyl" class="main-image">
            </div>
            
            <div class="quick-details">
                <div class="detail-item">
                    <span class="detail-icon">💿</span>
                    <span class="detail-text"><?php echo isset($product['format']) ? htmlspecialchars($product['format']) : 'Rød Vinyl, Album'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">🎵</span>
                    <span class="detail-text"><?php echo isset($product['genre']) ? htmlspecialchars($product['genre']) : 'Heavy Metal'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">✨</span>
                    <span class="detail-text"><?php echo isset($product['status']) ? htmlspecialchars($product['status']) : 'Ny'; ?></span>
                </div>
            </div>

            <div class="product-description">
                <h3 class="description-title">OM ALBUMMET</h3>
                <div class="description-content" id="description-content">
                    <p>"Live in Dallas 1991" indfanger Van Halen i deres storhedstid med Sammy Hagar bag mikrofonen under den legendariske "For Unlawful Carnal Knowledge"-turné. Denne historiske koncert udgives nu for første gang nogensinde på eksklusiv rød vinyl!</p>
                    
                    <div class="expandable-content" id="expandable-content">
                        <p>Oplev Eddie Van Halens virtuose guitarspil, Sammy Hagars kraftfulde vokal, Michael Anthonys pumpende baslinjer og Alex Van Halens eksplosive trommespil i en perfekt kombination af nyt materiale og klassiske hits. Fra tordnende versioner af "Poundcake" og "Right Now" til publikumsfavoritter som "Panama" og "Jump" – denne koncertoptagelse leverer den rå, upolerede energi, der gjorde Van Halen til rockens ubestridte konger.</p>
                        
                        <p>Denne særlige udgivelse er omhyggeligt remasteret fra originale optagelser og præsenteres i en limiteret presning på rød vinyl, der matcher perfekt med albummets ikoniske cover. Pladernes fremragende lydkvalitet bringer dig tættere på den elektriske atmosfære fra den historiske aften i Dallas.</p>
                    </div>
                    
                    <p class="highlight-text">En absolut essentiel tilføjelse til enhver rocksamling.</p>
                </div>
                <button class="expand-button" id="expand-button" onclick="toggleDescription()">
                    <span class="expand-text">LÆS MERE</span>
                    <span class="expand-icon">▼</span>
                </button>
            </div>
        </div>

        <div class="product-right">
            <div class="product-header">
                <h1 class="artist-name"><?php echo isset($product['artist']) ? htmlspecialchars($product['artist']) : 'Van Halen'; ?></h1>
                <h2 class="album-title"><?php echo isset($product['album_title']) ? htmlspecialchars($product['album_title']) : 'LIVE IN DALLAS 1991'; ?></h2>
            </div>

            <div class="release-countdown">
                <div class="release-date-banner">
                    <span class="release-icon">📅</span>
                    <span class="release-text">UDKOMMER 28. MARTS KL. 08:00</span>
                </div>
                <div class="countdown" id="countdown">
                    <div class="countdown-group">
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-days">000</span>
                            <span class="countdown-label">DAGE</span>
                        </div>
                    </div>
                    <div class="countdown-group">
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-hours">00</span>
                            <span class="countdown-label">TIMER</span>
                        </div>
                        <div class="countdown-separator">:</div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-minutes">00</span>
                            <span class="countdown-label">MIN</span>
                        </div>
                        <div class="countdown-separator">:</div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-seconds">00</span>
                            <span class="countdown-label">SEK</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="purchase-section">
                <div class="price-section">
                    <div class="price-label">Pris</div>
                    <div class="price-value">kr. 139,95</div>
                    <button class="pre-order-btn">Forudbestil nu</button>
                </div>

                <div class="cta-section">
                    <button class="pre-order-btn">RESERVÉR NU</button>
                    <div class="stock-info">
                        <span class="stock-icon">⚡</span>
                        Kun <?php echo isset($product['stock_quantity']) ? $product['stock_quantity'] : '100'; ?> eksemplarer tilbage
                    </div>
                    <div class="trust-badges">
                        <div class="badge"><span>🔒</span> Sikker betaling</div>
                        <div class="badge"><span>🚚</span> Hurtig levering</div>
                        <div class="badge"><span>🔄</span> 30 dages returret</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    function toggleDescription() {
        const content = document.getElementById('expandable-content');
        const button = document.getElementById('expand-button');
        const buttonText = button.querySelector('.expand-text');
        const buttonIcon = button.querySelector('.expand-icon');
        
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            buttonText.textContent = 'LÆS MERE';
            buttonIcon.textContent = '▼';
            content.classList.remove('expanded');
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            buttonText.textContent = 'LÆS MINDRE';
            buttonIcon.textContent = '▲';
            content.classList.add('expanded');
        }
    }

    function updateCountdown() {
        const releaseDate = new Date('2025-03-28T08:00:00');
        const now = new Date();
        const difference = releaseDate - now;

        if (difference > 0) {
            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            document.getElementById('countdown-days').textContent = days.toString().padStart(3, '0');
            document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countdown-seconds').textContent = seconds.toString().padStart(2, '0');

            // Add pulse animation to seconds
            const secondsElement = document.getElementById('countdown-seconds');
            secondsElement.style.animation = 'none';
            secondsElement.offsetHeight; // Trigger reflow
            secondsElement.style.animation = 'pulse 0.5s';
        }
    }

    // Update countdown every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
    </script>
    
    <?php if (isset($conn)) $conn->close(); ?>
</body>
</html> 