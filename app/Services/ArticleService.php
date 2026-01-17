<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleService
{
    public function createArticle(array $data, ?UploadedFile $image = null)
    {
        return DB::transaction(function () use ($data, $image) {
            $data['slug'] = Str::slug($data['title'].'-'.Str::random(6));

            if ($image) {
                $path = $image->store('articles', 'public');
                $data['image'] = $path;
            }

            $article = Article::create($data);

            if (isset($data['tags'])) {
                $article->tags()->sync($data['tags']);
            }

            return $article;
        });
    }

    public function updateArticle(Article $article, array $data, ?UploadedFile $image = null)
    {
        return DB::transaction(function () use ($article, $data, $image) {
            if (! $article->published_at && isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
            }

            if ($image) {
                if ($article->image) {
                    Storage::disk('public')->delete($article->image);
                }
                $data['image'] = $image->store('articles', 'public');
            }

            $article->update($data);
            if (isset($data['tags'])) {
                $article->tags()->sync($data['tags']);
            }

            return $article;
        });
    }

    public function deleteArticle(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();
    }
}
