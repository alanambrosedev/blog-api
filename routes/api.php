<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Mail\ArticleFeatured;
use App\Models\Article;
use App\Services\JsonService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/test/users', function () {
    $response = Http::retry(5)->timeout(200)->post('https://jsonplaceholder.typicode.com/posts', [
        "title" => "Test User",
        "body" => "Lorem ipsum",
        "userId" => 1
    ]);

    if ($response->successful()) {
        return "Success";
    }
});
Route::get('/test/posts', function (JsonService $json) {
    return $json->getUsers();
});
Route::get('/test/dashboard', function () {
    $responses = Http::pool(fn($pool) => [
        $pool->get('https://jsonplaceholder.typicode.com/users'),
        $pool->get('https://jsonplaceholder.typicode.com/posts'),
    ]);

    return [
        'users' => $responses[0]->json(),
        'posts' => $responses[1]->json(),
    ];
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/test-featured-mail/{id}', function ($id) {
    $article = Article::with('user')->findOrFail($id);

    // simulate feature toggle
    $article->is_featured = true;

    Mail::to($article->user)
        ->send(new ArticleFeatured($article));

    return 'Mail sent!';
});
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json(['message' => 'Your email has been successfully verified.']);
})->middleware(['auth:api', 'signed'])->name('verification.verify');

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article:slug}', [ArticleController::class, 'show']);
Route::get('/notifications/unread', [NotificationController::class, 'unread']);
Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/tags', [TagController::class, 'index']);

Route::middleware('auth:api', 'verified')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('articles', ArticleController::class)
        ->except(['index', 'show']);
    Route::get('/trending-articles/{category_id}', [ArticleController::class, 'getTrendingArticles']);
    Route::get('/audits', [AuditController::class, 'index']);
    Route::apiResource('users', UserController::class);
    Route::middleware('can:manage-taxonomy')->group(function () {
        Route::apiResource('categories', CategoryController::class)
            ->except(['index']);

        Route::apiResource('tags', TagController::class)
            ->except(['index']);
    });
});
