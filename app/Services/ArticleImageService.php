<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ArticleImageService
{
    public function store(UploadedFile $file, string $articleTitle)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, $allowed)) {
            throw new \InvalidArgumentException("Unsupported file type: {$extension}");
        }
        $manager = new ImageManager(new Driver);
        $processed = $manager->decode($file)->scale(width: 1200)->encode(new WebpEncoder(quality: 80));

        $slug = Str::slug($articleTitle);
        $unique = time().'-'.Str::uuid();
        $fileName = "{$slug}-{$unique}.{$extension}";
        $path = 'articles/'.now()->format('Y/m').'/'.$fileName;
        Storage::disk('public')->put($path, (string) $processed);

        return $path;
    }
}
