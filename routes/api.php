<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article:slug}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin Resources
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
});
