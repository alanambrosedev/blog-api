<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class MediaService
{

    public function uploadImage(Model $model, string $url)
    {
        return $model->image()->updateOrCreate(
            [],
            ['url' => $url]
        );
    }
}
