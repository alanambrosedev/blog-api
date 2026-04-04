<?php

require __DIR__.'/vendor/autoload.php';

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver);
echo "Manager instantiated.\n";
