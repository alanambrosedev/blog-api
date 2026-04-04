<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ArticleObserver
{
    public function deleting(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image->url);
            $article->image->delete();
        }
    }

    public function updating(Article $article)
    {
        if ($article->isDirty('image')) {
            $oldPath = $article->getOriginal('image');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    }
}
