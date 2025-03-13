<?php
/**
 * Helper functions for Accord Music Store
 */

/**
 * Get image URL with fallback to placeholder if image doesn't exist
 * 
 * @param string $image_url The original image URL from database
 * @return string The image URL to use (original or placeholder)
 */
function get_album_image_url($image_url) {
    // Placeholder image path
    $placeholder = 'album-placeholder.png';
    
    // If no image URL is provided, return placeholder
    if (empty($image_url)) {
        return $placeholder;
    }
    
    // Check if the image file exists
    if (file_exists($image_url)) {
        return $image_url;
    }
    
    // If file doesn't exist, return placeholder
    return $placeholder;
}

/**
 * Format price in Danish format (e.g. 139,95)
 * 
 * @param float $price The price to format
 * @return string Formatted price
 */
function format_danish_price($price) {
    return number_format((float)$price, 2, ',', '.');
}

/**
 * Check if a release date is in the future
 * 
 * @param string $release_date The release date in MySQL format (YYYY-MM-DD)
 * @return bool True if release date is in the future
 */
function is_future_release($release_date) {
    if (empty($release_date)) {
        return false;
    }
    
    $release = new DateTime($release_date);
    $today = new DateTime();
    
    return $release > $today;
}

/**
 * Format a date in Danish format (DD.MM.YYYY)
 * 
 * @param string $date The date in MySQL format (YYYY-MM-DD)
 * @return string Formatted date
 */
function format_danish_date($date) {
    if (empty($date)) {
        return '';
    }
    
    $datetime = new DateTime($date);
    return $datetime->format('d.m.Y');
}
?> 