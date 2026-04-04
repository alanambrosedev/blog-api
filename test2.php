<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$category = Category::factory()->create();
$user = User::factory()->create();

auth()->login($user); // login instead

Storage::fake('public');
$image = UploadedFile::fake()->image('test.jpg');

$request = Request::create('/api/articles', 'POST', [
    'title' => 'Test', 'text' => 'Content', 'category_id' => $category->id,
], [], ['image' => $image]);

$request->headers->set('Accept', 'application/json');

$response = app()->handle($request);
echo $response->getContent();
