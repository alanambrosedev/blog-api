<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver);
$image = UploadedFile::fake()->image('test.jpg');
$processed = $manager->decode($image);
file_put_contents('imagemethods.txt', implode(PHP_EOL, get_class_methods($processed)));
