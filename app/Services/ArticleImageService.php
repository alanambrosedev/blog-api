<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
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

        $manager = new ImageManager(new Driver);
        $manager = ImageManager::usingDriver(Driver::class);

        $image = $manager->decode($file);

        $image->scale(width: 1200);
        $image->text('@Blog', 1180, 20, function ($font) {
            $font->size(24);
            $font->color('ffffff');
            $font->align('right');
            $font->valign('top');
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
