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

            // 5. Bonus: Non-destructive Tagging for the Author
            // Add "Active Author" (Tag ID 1) without removing their "Staff" or "Premium" tags
            $article->user->tags()->syncWithoutDetaching([1]);

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
