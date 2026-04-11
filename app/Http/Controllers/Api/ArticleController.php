<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    public function __construct(protected ArticleService $service) {}

    public function index()
    {
        $article = Article::with(['category', 'tags', 'image'])
            ->where('published_at', true)
            ->latest()
            ->paginate(10);

        return ArticleResource::collection($article)->additional([
            'meta' => [
                'api_version' => '1.0.0',
                'support_email' => 'dev_support@dev.com'
            ]
        ]);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article->load(['category', 'tags', 'image']));
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->service->createArticle($request->validated(), $request->file('image'));

        return new ArticleResource($article->load(['category', 'tags', 'image']));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $updated = $this->service->updateArticle($article, $request->validated(), $request->file('image'));

        return new ArticleResource($updated->load(['category', 'tags', 'image']));
    }

    public function destroy(Article $article)
    {
        $this->service->deleteArticle($article);

        return response()->noContent();
    }

    public function getTrendingArticles($category)
    {
        $articles = $this->service->analyticData($category);

        return response()->json($articles);
    }
}
