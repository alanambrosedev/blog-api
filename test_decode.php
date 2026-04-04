<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver);
$image = UploadedFile::fake()->image('test.jpg');
$processed = $manager->decode($image);
// Or try:
// $processed = $manager->decodeSplFileInfo($image);
echo get_class($processed);
