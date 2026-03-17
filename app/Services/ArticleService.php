<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleService
{

    public function __construct(protected MediaService $mediaService) {}
    public function createArticle(array $data, ?UploadedFile $image = null)
    {
        return DB::transaction(function () use ($data, $image) {
            // 1. Prepare Article Data (Slug generation)
            $data['slug'] = Str::slug($data['title'] . '-' . Str::random(6));
            $data['user_id'] = auth()->id();

            // 2. Create the Article (No 'image' column needed in articles table anymore!)
            $article = Article::create($data);

            // 3. Handle Polymorphic Image via Service
            if ($image) {
                $path = $image->store('articles', 'public');
                $this->mediaService->uploadImage($article, $path);
            }

            // 4. Sync Tags to Article (Destructive: replaces old with new)
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
                $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
            }

            if ($image) {
                $path = $image->store('articles', 'public');
                $this->mediaService->uploadImage($article, $path);
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
            Storage::disk('public')->delete($article->image->url);
            $article->image->delete();
        }
        $article->delete();
    }
}
