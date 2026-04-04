<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleService
{
    public function __construct(protected ArticleImageService $imageService) {}

    public function createArticle(array $data, ?UploadedFile $image = null)
    {
        return DB::transaction(function () use ($data, $image) {
            // 1. Prepare Article Data (Slug generation)
            $data['slug'] = Str::slug($data['title'].'-'.Str::random(6));
            $data['user_id'] = auth()->id();

            // 2. Create the Article (No 'image' column needed in articles table anymore!)
            $article = Article::create($data);

            // 3. Handle Polymorphic Image via Service
            if ($image) {
                $path = $this->imageService->store($image, $data['title']);
                $this->uploadImage($article, $path);
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
                $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
            }

            if ($image) {
                $title = $data['title'] ?? $article->title;
                $path = $this->imageService->store($image, $title);
                $this->uploadImage($article, $path);
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

    public function getTrendingArticles()
    {
        return Article::query()->whereNotNull('published_at')
            ->take(5)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => Str::title($article->title),
                    'text' => Str::limit($article->title, 100),
                ];
            })->toArray();
    }

    public function getHeroAndFeed()
    {
        $articles = Article::with('category')
            ->whereNotNull('published_at')
            ->limit(20)
            ->get();
        [$featured, $regular] = $articles->partition(function ($article) {
            return $article->is_featured;
        });

        return [
            'hero' => $featured->sortBy(fn ($a) => $a->category?->name)->values(),
            'feed' => $regular->sortBy(fn ($a) => $a->category?->name)->values(),
        ];
    }

    public function analyticData($categoryId)
    {
        return $articles = Article::with('tags')->latest()
            ->take(50)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get()
            ->pluck('tags')
            ->flatten()
            ->groupBy('id')
            ->map(function ($group, $id) {
                return [
                    'id' => $id,
                    'name' => $group->first()->name,
                    'count' => $group->count(),
                ];
            })
            ->sortDesc()
            ->take(3)
            ->values();
    }

    public function getDeletedData()
    {
        return Article::onlyTrashed();
    }

    public function restoreArticle($id)
    {
        $article = Article::withTrashed()->findOrFail($id);
        $article->restore();
    }

    public function forceDeleteArticle($id)
    {
        $article = Article::withTrashed()->findOrFail($id);
        if ($article->image) {
            Storage::disk('public')->delete($article->image->url);
        }
        $article->forceDelete();
    }

    public function uploadImage(Article $article, string $url)
    {
        $existingImage = $article->image;
        if ($existingImage) {
            Storage::disk('public')->delete($existingImage->url);
        }

        return $article->image()->updateOrCreate(
            [],
            ['url' => $url]
        );
    }
}
