<?php
$filename = 'DSCN7626.JPG'; // Uno que falló
$paths = [
    'C:\Users\munoz\Downloads\HAM10000\HAM10000_images_part_1',
    'C:\Users\munoz\Downloads\HAM10000\HAM10000_images_part_2',
];

foreach ($paths as $path) {
    $fullPath = $path . DIRECTORY_SEPARATOR . $filename;
    echo "Check: $fullPath -> " . (file_exists($fullPath) ? 'Exists' : 'Not found') . "
";
}
