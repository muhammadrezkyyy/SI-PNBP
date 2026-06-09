<?php

function generateRoundedFavicon($sourcePath, $destinationPath)
{
    $size = 64;
    $radius = 16;
    
    $info = getimagesize($sourcePath);
    if (!$info) die("Not an image\n");
    $mime = $info['mime'];
    if ($mime == 'image/jpeg') $src = imagecreatefromjpeg($sourcePath);
    elseif ($mime == 'image/png') $src = imagecreatefrompng($sourcePath);
    else die("Unsupported mime\n");
    
    $srcW = imagesx($src);
    $srcH = imagesy($src);
    $dim = min($srcW, $srcH);
    $tmp = imagecreatetruecolor($size, $size);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    $trans = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
    imagefill($tmp, 0, 0, $trans);
    
    imagecopyresampled($tmp, $src, 0, 0, ($srcW-$dim)/2, ($srcH-$dim)/2, $size, $size, $dim, $dim);
    
    $dest = imagecreatetruecolor($size, $size);
    imagealphablending($dest, false);
    imagesavealpha($dest, true);
    imagefill($dest, 0, 0, $trans);
    
    for ($x = 0; $x < $size; $x++) {
        for ($y = 0; $y < $size; $y++) {
            $color = imagecolorat($tmp, $x, $y);
            $alpha = ($color >> 24) & 0xFF;
            
            $maskAlpha = $alpha;
            // Top Left
            if ($x < $radius && $y < $radius) {
                $dist = sqrt(pow($radius - $x, 2) + pow($radius - $y, 2));
                if ($dist > $radius) $maskAlpha = 127;
            }
            // Top Right
            elseif ($x >= $size - $radius && $y < $radius) {
                $dist = sqrt(pow($x - ($size - $radius - 1), 2) + pow($radius - $y, 2));
                if ($dist > $radius) $maskAlpha = 127;
            }
            // Bottom Left
            elseif ($x < $radius && $y >= $size - $radius) {
                $dist = sqrt(pow($radius - $x, 2) + pow($y - ($size - $radius - 1), 2));
                if ($dist > $radius) $maskAlpha = 127;
            }
            // Bottom Right
            elseif ($x >= $size - $radius && $y >= $size - $radius) {
                $dist = sqrt(pow($x - ($size - $radius - 1), 2) + pow($y - ($size - $radius - 1), 2));
                if ($dist > $radius) $maskAlpha = 127;
            }
            
            if ($maskAlpha == 127) {
                imagesetpixel($dest, $x, $y, $trans);
            } else {
                $rgb = imagecolorsforindex($tmp, $color);
                $newColor = imagecolorallocatealpha($dest, $rgb['red'], $rgb['green'], $rgb['blue'], $maskAlpha);
                imagesetpixel($dest, $x, $y, $newColor);
            }
        }
    }
    
    imagepng($dest, $destinationPath);
    echo "Saved to $destinationPath\n";
}

// create a dummy image to test
$dummy = imagecreatetruecolor(200, 200);
imagefill($dummy, 0, 0, imagecolorallocate($dummy, 255, 0, 0));
imagejpeg($dummy, 'dummy.jpg');

generateRoundedFavicon('dummy.jpg', 'favicon.png');
