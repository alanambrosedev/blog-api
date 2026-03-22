<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function uploadImage(Model $model, string $url)
    {
        $existingImage = $model->image;
        if ($existingImage) {
            Storage::disk('public')->delete($existingImage->url);
        }

        return $model->image()->updateOrCreate(
            [],
            ['url' => $url]
        );
    }
}
