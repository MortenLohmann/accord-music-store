<?php
// Include database connection and helper functions
require_once 'config.php';
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'ai_artist_bio.php';

// Check if OpenAI API key is configured
$api_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
$api_configured = !empty($api_key) && $api_key !== 'YOUR_API_KEY';

// Process form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_all'])) {
        // Check if API is configured
        if (!$api_configured) {
            $message = "Error: OpenAI API key is not configured. Please set it up first.";
            $message_type = "error";
        } else {
            // Generate bios for all artists without one
            generate_missing_artist_bios();
            $message = "Started generating missing artist biographies. This may take some time.";
            $message_type = "success";
        }
    } elseif (isset($_POST['artist_id']) && isset($_POST['action'])) {
        $artist_id = $_POST['artist_id'];
        $action = $_POST['action'];
        
        if ($action === 'generate') {
            // Check if API is configured
            if (!$api_configured) {
                $message = "Error: OpenAI API key is not configured. Please set it up first.";
                $message_type = "error";
            } else {
                // Get artist info
                $sql = "SELECT artist, genre FROM products WHERE id = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $artist_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $artist_info = $result->fetch_assoc();
                    if (save_artist_bio($artist_info['artist'], $artist_info['genre'])) {
                        $message = "Generated biography for " . htmlspecialchars($artist_info['artist']);
                        $message_type = "success";
                    } else {
                        $message = "Failed to generate biography. Check error logs for details.";
                        $message_type = "error";
                    }
                }
            }
        } elseif ($action === 'edit' && isset($_POST['bio_text'])) {
            // Update bio directly
            $bio_text = $_POST['bio_text'];
            $artist_name = $_POST['artist_name'];
            
            $sql = "UPDATE products SET artist_bio = ? WHERE artist = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $bio_text, $artist_name);
            
            if ($stmt->execute()) {
                $message = "Updated biography for " . htmlspecialchars($artist_name);
                $message_type = "success";
            } else {
                $message = "Failed to update biography";
                $message_type = "error";
            }
        }
    }
}

// Get all artists with their bio status
$sql = "SELECT 
            p.id,
            p.artist, 
            p.genre,
            MAX(CASE WHEN p.artist_bio IS NOT NULL AND p.artist_bio != '' THEN 1 ELSE 0 END) AS has_bio,
            COUNT(p.id) AS album_count
        FROM 
            products p
        GROUP BY 
            p.artist
        ORDER BY 
            p.artist ASC";

$result = $conn->query($sql);
$artists = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $artists[] = $row;
    }
}

// If editing a specific artist bio
$editing_artist = null;
$editing_bio = '';
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $artist_name = $_GET['edit'];
    
    $sql = "SELECT artist, artist_bio FROM products WHERE artist = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $artist_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $artist_data = $result->fetch_assoc();
        $editing_artist = $artist_data['artist'];
        $editing_bio = $artist_data['artist_bio'];
    }
}

// Calculate statistics
$total_artists = count($artists);
$artists_with_bios = 0;
$artists_without_bios = 0;

foreach ($artists as $artist) {
    if ($artist['has_bio']) {
        $artists_with_bios++;
    } else {
        $artists_without_bios++;
    }
}

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artist Biographies | Accord Music Store Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .message {
            background-color: #f8f9fa;
            border-left: 4px solid #4CAF50;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .message.error {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .admin-actions {
            display: flex;
            gap: 10px;
        }
        .stats-panel {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 20px;
        }
        .stat-card {
            flex: 1;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
        }
        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .api-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 5px;
        }
        .status-ok {
            background-color: #d4edda;
            color: #155724;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-yes {
            background-color: #4CAF50;
        }
        .status-no {
            background-color: #F44336;
        }
        .button {
            background-color: #444;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 0.9em;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #555;
        }
        .button-small {
            padding: 5px 10px;
            font-size: 0.8em;
        }
        .edit-bio-form {
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .edit-bio-form textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            margin-bottom: 10px;
            font-family: inherit;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">ACCORD ADMIN</div>
            <nav class="main-nav">
                <ul>
                    <li><a href="products.php">KATALOG</a></li>
                    <li><a href="#">BUTIKKER</a></li>
                    <li><a href="#">ADMIN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Manage Artist Biographies</h1>
            <div class="admin-actions">
                <a href="setup_openai.php" class="button">OpenAI API Setup</a>
                <form method="post">
                    <button type="submit" name="generate_all" class="button" <?php echo !$api_configured ? 'disabled' : ''; ?>>Generate All Missing Bios</button>
                </form>
            </div>
        </div>
        
        <?php if (!empty($message)): ?>
        <div class="message <?php echo isset($message_type) ? $message_type : ''; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="stats-panel">
            <div class="stat-card">
                <div>OpenAI API Status</div>
                <div class="number">
                    <span class="api-status <?php echo $api_configured ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $api_configured ? 'Configured' : 'Not Configured'; ?>
                    </span>
                </div>
                <?php if (!$api_configured): ?>
                <a href="setup_openai.php" class="button button-small">Configure API</a>
                <?php endif; ?>
            </div>
            <div class="stat-card">
                <div>Total Artists</div>
                <div class="number"><?php echo $total_artists; ?></div>
            </div>
            <div class="stat-card">
                <div>Artists with Bios</div>
                <div class="number"><?php echo $artists_with_bios; ?></div>
            </div>
            <div class="stat-card">
                <div>Artists without Bios</div>
                <div class="number"><?php echo $artists_without_bios; ?></div>
            </div>
        </div>
        
        <?php if ($editing_artist): ?>
        <div class="edit-bio-form">
            <h2>Editing biography for <?php echo htmlspecialchars($editing_artist); ?></h2>
            <form method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="artist_id" value="0">
                <input type="hidden" name="artist_name" value="<?php echo htmlspecialchars($editing_artist); ?>">
                <textarea name="bio_text"><?php echo htmlspecialchars($editing_bio); ?></textarea>
                <div>
                    <button type="submit" class="button">Save Biography</button>
                    <a href="admin_artist_bios.php" class="button">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Artist</th>
                    <th>Genre</th>
                    <th>Albums</th>
                    <th>Has Biography</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($artists as $artist): ?>
                <tr>
                    <td>
                        <a href="artist.php?name=<?php echo urlencode($artist['artist']); ?>">
                            <?php echo htmlspecialchars($artist['artist']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($artist['genre'] ?? ''); ?></td>
                    <td><?php echo $artist['album_count']; ?></td>
                    <td>
                        <span class="status-indicator status-<?php echo $artist['has_bio'] ? 'yes' : 'no'; ?>"></span>
                        <?php echo $artist['has_bio'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <?php if ($artist['has_bio']): ?>
                            <a href="admin_artist_bios.php?edit=<?php echo urlencode($artist['artist']); ?>" class="button button-small">Edit</a>
                        <?php else: ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="artist_id" value="<?php echo $artist['id']; ?>">
                                <input type="hidden" name="action" value="generate">
                                <button type="submit" class="button button-small" <?php echo !$api_configured ? 'disabled title="API not configured"' : ''; ?>>Generate</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php $conn->close(); ?>
</body>
</html> 