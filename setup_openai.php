<?php
/**
 * OpenAI API Setup and Test Script
 * This script helps configure and test your OpenAI API integration
 */

// Check if config file exists
if (!file_exists('config.php')) {
    echo "<p style='color: red;'>Error: config.php file not found.</p>";
    echo "<p>Please create the config.php file first.</p>";
    exit;
}

// Include configuration
require_once 'config.php';

// Process form submission to update API key
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_key'])) {
    $api_key = $_POST['api_key'] ?? '';
    $api_model = $_POST['api_model'] ?? 'gpt-3.5-turbo-instruct';
    
    if (empty($api_key)) {
        $error_message = "API key cannot be empty.";
    } else {
        // Read the current config file
        $config_content = file_get_contents('config.php');
        
        // Replace the API key
        $config_content = preg_replace(
            "/define\('OPENAI_API_KEY', '.*?'\);/", 
            "define('OPENAI_API_KEY', '$api_key');", 
            $config_content
        );
        
        // Replace the API model
        $config_content = preg_replace(
            "/define\('OPENAI_API_MODEL', '.*?'\);/", 
            "define('OPENAI_API_MODEL', '$api_model');", 
            $config_content
        );
        
        // Write back to the file
        if (file_put_contents('config.php', $config_content)) {
            $success_message = "API configuration updated successfully!";
            // Reload the constants
            define('OPENAI_API_KEY', $api_key);
            define('OPENAI_API_MODEL', $api_model);
        } else {
            $error_message = "Failed to update config.php. Check file permissions.";
        }
    }
}

// Process test request
$test_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_api'])) {
    $test_artist = $_POST['test_artist'] ?? '';
    $test_genre = $_POST['test_genre'] ?? '';
    
    if (empty($test_artist)) {
        $error_message = "Artist name cannot be empty for testing.";
    } else {
        // Include the artist bio generator
        require_once 'ai_artist_bio.php';
        
        // Start output buffering to capture errors
        ob_start();
        
        try {
            // Generate a bio for testing
            $bio = generate_artist_bio($test_artist, $test_genre);
            
            if (!empty($bio)) {
                $test_result = [
                    'success' => true,
                    'bio' => $bio
                ];
            } else {
                $test_result = [
                    'success' => false,
                    'message' => "API returned empty response. Check the error log for details."
                ];
            }
        } catch (Exception $e) {
            $test_result = [
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ];
        }
        
        // Get any output/errors
        $output = ob_get_clean();
        if (!empty($output)) {
            $test_result['debug_output'] = $output;
        }
    }
}

// Get current configuration
$current_api_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : 'YOUR_API_KEY';
$current_api_model = defined('OPENAI_API_MODEL') ? OPENAI_API_MODEL : 'gpt-3.5-turbo-instruct';

// Simple key masking for display
$masked_key = substr($current_api_key, 0, 3) . str_repeat('*', strlen($current_api_key) - 6) . substr($current_api_key, -3);
if ($current_api_key === 'YOUR_API_KEY') {
    $masked_key = $current_api_key;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI API Setup | Accord Music Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .section {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
        .bio-result {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 15px;
            white-space: pre-wrap;
        }
        .info {
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            color: #31708f;
        }
    </style>
</head>
<body>
    <h1>OpenAI API Setup</h1>
    
    <?php if (isset($success_message)): ?>
    <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
    <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <div class="section">
        <h2>OpenAI API Configuration</h2>
        <form method="post">
            <label for="api_key">API Key:</label>
            <input type="text" id="api_key" name="api_key" placeholder="Enter your OpenAI API key" value="<?php echo htmlspecialchars($current_api_key === 'YOUR_API_KEY' ? '' : $current_api_key); ?>">
            
            <label for="api_model">API Model:</label>
            <select id="api_model" name="api_model">
                <option value="gpt-3.5-turbo-instruct" <?php echo $current_api_model === 'gpt-3.5-turbo-instruct' ? 'selected' : ''; ?>>GPT-3.5 Turbo Instruct</option>
                <option value="gpt-4" <?php echo $current_api_model === 'gpt-4' ? 'selected' : ''; ?>>GPT-4</option>
                <option value="gpt-4-turbo" <?php echo $current_api_model === 'gpt-4-turbo' ? 'selected' : ''; ?>>GPT-4 Turbo</option>
                <option value="gpt-4o" <?php echo $current_api_model === 'gpt-4o' ? 'selected' : ''; ?>>GPT-4o</option>
            </select>
            
            <button type="submit" name="update_key">Save Configuration</button>
        </form>
        
        <div class="message info" style="margin-top: 15px;">
            <p><strong>Current configuration:</strong></p>
            <p>API Key: <?php echo $masked_key; ?></p>
            <p>API Model: <?php echo htmlspecialchars($current_api_model); ?></p>
        </div>
    </div>
    
    <div class="section">
        <h2>Test API Integration</h2>
        <p>Test the OpenAI API integration by generating a sample artist biography:</p>
        
        <form method="post">
            <label for="test_artist">Artist Name:</label>
            <input type="text" id="test_artist" name="test_artist" placeholder="Enter an artist name (e.g., Pink Floyd)" value="Pink Floyd">
            
            <label for="test_genre">Genre (optional):</label>
            <input type="text" id="test_genre" name="test_genre" placeholder="Enter a genre (e.g., Rock)">
            
            <button type="submit" name="test_api">Test API</button>
        </form>
        
        <?php if ($test_result): ?>
            <?php if ($test_result['success']): ?>
                <div class="message success" style="margin-top: 15px;">API test successful!</div>
                <div class="bio-result"><?php echo htmlspecialchars($test_result['bio']); ?></div>
            <?php else: ?>
                <div class="message error" style="margin-top: 15px;"><?php echo htmlspecialchars($test_result['message']); ?></div>
                <?php if (isset($test_result['debug_output'])): ?>
                    <pre><?php echo htmlspecialchars($test_result['debug_output']); ?></pre>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Next Steps</h2>
        <ol>
            <li>After setting up the API key and testing it works, you can generate artist biographies.</li>
            <li>Go to <a href="admin_artist_bios.php">Artist Biography Management</a> to generate biographies for all artists.</li>
            <li>Customers can also request biographies to be generated from the artist page.</li>
        </ol>
    </div>
</body>
</html> 