<?php
/**
 * AI-powered artist biography generator for Accord Music Store
 * 
 * This file provides functions to generate artist descriptions using AI
 */

// Include database connection and configuration
require_once 'config.php';
require_once 'db_connect.php';

/**
 * Generate an artist biography using AI
 * 
 * @param string $artist_name The name of the artist
 * @param string $genre Optional genre to provide context
 * @return string The generated artist biography
 */
function generate_artist_bio($artist_name, $genre = '') {
    // Get API key from config file
    $api_key = OPENAI_API_KEY;
    $model = OPENAI_API_MODEL;
    
    // Create a prompt for the AI
    $genre_context = !empty($genre) ? " They are known for their $genre music." : "";
    $prompt = "Write a concise biography (150-200 words) of the music artist '$artist_name'.$genre_context Include their key achievements, musical style, and influence on music. Format the response as a proper paragraph suitable for a music store website.";
    
    // Call the OpenAI API for text generation
    $response = call_openai_api($prompt, $api_key, $model);
    
    // If AI fails, provide a generic bio
    if (empty($response)) {
        return "Information about $artist_name will be coming soon. Check back later for a full artist biography.";
    }
    
    return $response;
}

/**
 * Call the OpenAI API to generate text
 * 
 * @param string $prompt The prompt for text generation
 * @param string $api_key Your OpenAI API key
 * @param string $model The AI model to use
 * @return string The generated text
 */
function call_openai_api($prompt, $api_key, $model = 'gpt-3.5-turbo-instruct') {
    // Log API request (for debugging, remove in production)
    error_log("Making OpenAI API request for prompt: " . substr($prompt, 0, 50) . "...");
    
    // OpenAI API endpoint
    $url = 'https://api.openai.com/v1/completions';
    
    // Request data
    $data = [
        'model' => $model,
        'prompt' => $prompt,
        'max_tokens' => 500,
        'temperature' => 0.7
    ];
    
    // Initialize cURL
    $ch = curl_init($url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    
    // Execute cURL request
    $response = curl_exec($ch);
    $err = curl_error($ch);
    
    // Get HTTP status code
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Close cURL
    curl_close($ch);
    
    // Handle errors
    if ($err) {
        error_log("cURL Error: " . $err);
        return "";
    }
    
    // Handle non-200 responses
    if ($http_code !== 200) {
        error_log("OpenAI API Error (HTTP $http_code): " . $response);
        return "";
    }
    
    // Parse response
    $response_data = json_decode($response, true);
    
    // Extract generated text
    if (isset($response_data['choices'][0]['text'])) {
        return trim($response_data['choices'][0]['text']);
    }
    
    error_log("OpenAI API returned unexpected response format: " . json_encode($response_data));
    return "";
}

/**
 * Generate and save biography for an artist in the database
 * 
 * @param string $artist_name The name of the artist
 * @param string $genre Optional genre to provide context
 * @return bool True if successful, false if failed
 */
function save_artist_bio($artist_name, $genre = '') {
    global $conn;
    
    // Skip if artist name is empty
    if (empty($artist_name)) {
        return false;
    }
    
    // Check if we already have a bio for this artist in any product
    $sql = "SELECT artist_bio FROM products WHERE artist = ? AND artist_bio IS NOT NULL AND artist_bio != '' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $artist_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $existing_bio = $row['artist_bio'];
        
        // If we have an existing bio, update all products with this artist
        if (!empty($existing_bio)) {
            $update_sql = "UPDATE products SET artist_bio = ? WHERE artist = ? AND (artist_bio IS NULL OR artist_bio = '')";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $existing_bio, $artist_name);
            $update_stmt->execute();
            return true;
        }
    }
    
    // Generate a new bio
    $bio = generate_artist_bio($artist_name, $genre);
    
    // Save to database - update all products with this artist
    $sql = "UPDATE products SET artist_bio = ? WHERE artist = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $bio, $artist_name);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error saving artist bio: " . $stmt->error);
        return false;
    }
}

/**
 * Function to generate bios for all artists without one
 * This can be run periodically or manually
 */
function generate_missing_artist_bios() {
    global $conn;
    
    // Find all unique artists without bios
    $sql = "SELECT DISTINCT artist, genre FROM products WHERE 
            artist_bio IS NULL OR artist_bio = '' 
            GROUP BY artist";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            save_artist_bio($row['artist'], $row['genre']);
            // Sleep to avoid API rate limits
            sleep(1);
        }
    }
    
    return true;
}

// If this file is called directly, generate missing bios
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    if (isset($_GET['generate']) && $_GET['generate'] == 'all') {
        generate_missing_artist_bios();
        echo "Generated missing artist biographies.";
    } elseif (isset($_GET['artist'])) {
        $artist = $_GET['artist'];
        $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
        if (save_artist_bio($artist, $genre)) {
            echo "Generated biography for $artist.";
        } else {
            echo "Failed to generate biography for $artist.";
        }
    } else {
        echo "Use ?generate=all to generate all missing bios or ?artist=NAME to generate for a specific artist.";
    }
}
?> 