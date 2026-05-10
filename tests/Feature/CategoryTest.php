<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


it('generates a slug from the name', function () {
    $category = Category::create(['name' => 'Web Development']);
    expect($category->slug)->toBe('web-development');
});

it('allows an authenticated user to create an article and defaults featured to false', function () {

    $user = User::factory()->create();
    $category = Category::factory()->create();

    $payload = [
        'title' => 'My first Article',
        'text' => 'This is the content of the article.',
        'slug' => 'my-first-article',
        'category_id' => $category->id,
        'published_at' => false,
    ];

    $response = $this->actingAs($user, 'api')
        ->postJson(route('articles.store'), $payload);

    $response->assertCreated();

    $this->assertDatabaseHas('articles', [
        'title' => 'My first Article',
        'category_id' => $category->id,
        'is_featured' => 0,
    ]);
});
