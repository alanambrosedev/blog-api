<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    public function createTag(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return Tag::create($data);
    }

    public function updateTag(Tag $tag, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $tag->update($data);

        return $tag;
    }

    public function deleteTag(Tag $tag)
    {
        $tag->delete();
    }
}
