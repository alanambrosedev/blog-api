<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver);
$image = UploadedFile::fake()->image('test.jpg');
$processed = $manager->decode($image)->scale(width: 1200)->encode(new WebpEncoder(quality: 80));
echo 'SUCCESS';
