<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ArticleImageService
{
    public function store(UploadedFile $file, string $articleTitle): string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, $allowed)) {
            throw new \InvalidArgumentException("Unsupported file type: {$extension}");
        }

        $driverClass = extension_loaded('imagick') ? ImagickDriver::class : GdDriver::class;
        $manager = new ImageManager($driverClass);

        $image = $manager->decode($file);

        $image->scale(width: 1200);
        $image->text('@Blog', 1180, 20, function ($font) {
            $font->size(24);
            $font->color('ffffff');
            $font->align('right', 'top');
        });

        $slug = Str::slug($articleTitle);
        $unique = time().'-'.Str::uuid();
        $fileName = "{$slug}-{$unique}.webp";
        $path = 'articles/'.now()->format('Y/m').'/'.$fileName;

        Storage::disk('public')->put(
            $path,
            (string) $image->encode(new WebpEncoder(quality: 80))
        );

        return $path;
    }
}
