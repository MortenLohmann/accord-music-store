<?php
// Set the image width and height
$width = 300;
$height = 300;

// Create the image resource
$image = imagecreatetruecolor($width, $height);

// Define colors
$darkGray = imagecolorallocate($image, 43, 43, 43);
$darkerGray = imagecolorallocate($image, 26, 26, 26);
$black = imagecolorallocate($image, 0, 0, 0);
$darkestGray = imagecolorallocate($image, 17, 17, 17);
$mediumGray = imagecolorallocate($image, 51, 51, 51);
$lightGray = imagecolorallocate($image, 153, 153, 153);
$white = imagecolorallocate($image, 240, 240, 240);

// Fill the background
imagefill($image, 0, 0, $darkGray);

// Draw the vinyl record
imagefilledellipse($image, $width/2, $height/2, 200, 200, $darkerGray);
imageellipse($image, $width/2, $height/2, 200, 200, $mediumGray);

// Draw the inner rings
imagefilledellipse($image, $width/2, $height/2, 60, 60, $darkestGray);
imageellipse($image, $width/2, $height/2, 60, 60, $mediumGray);
imagefilledellipse($image, $width/2, $height/2, 20, 20, $black);

// Draw some record grooves
imageellipse($image, $width/2, $height/2, 170, 170, $mediumGray);
imageellipse($image, $width/2, $height/2, 140, 140, $mediumGray);
imageellipse($image, $width/2, $height/2, 110, 110, $mediumGray);
imageellipse($image, $width/2, $height/2, 80, 80, $mediumGray);

// Add text
$font = 4; // Built-in font
$text1 = "ALBUM ARTWORK";
$text2 = "ACCORD MUSIC STORE";

// Calculate positions for centered text
$text1_width = imagefontwidth($font) * strlen($text1);
$text1_x = ($width - $text1_width) / 2;
$text1_y = $height - 70;

$text2_width = imagefontwidth($font-1) * strlen($text2);
$text2_x = ($width - $text2_width) / 2;
$text2_y = $height - 50;

// Add the text to the image
imagestring($image, $font, $text1_x, $text1_y, $text1, $white);
imagestring($image, $font-1, $text2_x, $text2_y, $text2, $lightGray);

// Set the content type header
header('Content-Type: image/png');

// Output the image to browser or file
if (isset($_GET['save']) && $_GET['save'] == 'true') {
    // Save to file
    imagepng($image, 'album-placeholder.png');
    echo "Image saved as album-placeholder.png";
} else {
    // Output to browser
    imagepng($image);
}

// Free up memory
imagedestroy($image);
?> 