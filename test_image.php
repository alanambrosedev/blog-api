<?php

use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$article = Article::factory()->create();
$service = app(ArticleService::class);
$service->uploadImage($article, 'test/path.jpg');
echo 'Image URL: '.$article->load('image')->image->url."\n";
