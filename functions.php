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
function get_album_image_url($image_url = '') {
    if (empty($image_url)) {
        return 'images/van-halen-dallas-1991.jpg';
    }
    return $image_url;
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
function is_future_release($date) {
    return strtotime($date) > time();
}

/**
 * Format a date in Danish format (DD.MM.YYYY)
 * 
 * @param string $date The date in MySQL format (YYYY-MM-DD)
 * @return string Formatted date
 */
function format_danish_date($date) {
    $months = array(
        '01' => 'JANUAR', '02' => 'FEBRUAR', '03' => 'MARTS',
        '04' => 'APRIL', '05' => 'MAJ', '06' => 'JUNI',
        '07' => 'JULI', '08' => 'AUGUST', '09' => 'SEPTEMBER',
        '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DECEMBER'
    );
    
    $date_parts = explode('-', $date);
    if (count($date_parts) === 3) {
        return $date_parts[2] . '. ' . $months[$date_parts[1]] . ' ' . $date_parts[0];
    }
    return $date;
}
?> 