<?php
// Set the image width and height
$width = 300;
$height = 300;

// Create the image resource
$image = imagecreatetruecolor($width, $height);

// Define colors
$darkGray = imagecolorallocate($image, 51, 51, 51);  // #333333
$darkerGray = imagecolorallocate($image, 34, 34, 34); // #222222
$black = imagecolorallocate($image, 0, 0, 0);
$medium1 = imagecolorallocate($image, 85, 85, 85);   // #555555
$medium2 = imagecolorallocate($image, 102, 102, 102); // #666666
$medium3 = imagecolorallocate($image, 119, 119, 119); // #777777
$light1 = imagecolorallocate($image, 136, 136, 136);  // #888888
$lightGray = imagecolorallocate($image, 153, 153, 153); // #999999
$white = imagecolorallocate($image, 240, 240, 240);  // #f0f0f0

// Fill the background
imagefill($image, 0, 0, $darkGray);

// Draw the head (circle)
imagefilledellipse($image, $width/2, 120, 140, 140, $darkerGray);
imageellipse($image, $width/2, 120, 140, 140, $medium1);

// Draw the body (simplified)
$points = array(
    80, 230,   // Top left
    220, 230,  // Top right
    220, 300,  // Bottom right
    80, 300    // Bottom left
);
imagefilledpolygon($image, $points, 4, $darkerGray);
imagepolygon($image, $points, 4, $medium1);

// Draw the microphone stand (rectangle)
imagefilledrectangle($image, 135, 160, 165, 240, $medium1);

// Draw the microphone head (circle)
imagefilledellipse($image, $width/2, 160, 50, 50, $medium2);
imageellipse($image, $width/2, 160, 50, 50, $medium3);
imagefilledellipse($image, $width/2, 160, 30, 30, $light1);

// Add text
$font = 4; // Built-in font
$text1 = "ARTIST";
$text2 = "ACCORD MUSIC STORE";

// Calculate positions for centered text
$text1_width = imagefontwidth($font) * strlen($text1);
$text1_x = ($width - $text1_width) / 2;
$text1_y = 260;

$text2_width = imagefontwidth($font-1) * strlen($text2);
$text2_x = ($width - $text2_width) / 2;
$text2_y = 280;

// Add the text to the image
imagestring($image, $font, $text1_x, $text1_y - imagefontheight($font), $text1, $white);
imagestring($image, $font-1, $text2_x, $text2_y - imagefontheight($font-1), $text2, $lightGray);

// Set the content type header
header('Content-Type: image/png');

// Output the image to browser or file
if (isset($_GET['save']) && $_GET['save'] == 'true') {
    // Save to file
    imagepng($image, 'artist-placeholder.png');
    echo "Image saved as artist-placeholder.png";
} else {
    // Output to browser
    imagepng($image);
}

// Free up memory
imagedestroy($image);
?> 