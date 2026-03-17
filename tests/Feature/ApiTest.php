<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get articles index', function () {
    Category::factory()->create();
    Article::factory()->published()->create();

    $response = $this->get('/api/articles');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'content',
                    'image_url',
                    'published_at',
                    'category',
                    'tags',
                    'created_at'
                ]
            ]
        ]);
});

test('can get single article', function () {
    $article = Article::factory()->published()->create();

    $response = $this->get('/api/articles/' . $article->slug);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'content',
                'image_url',
                'published_at',
                'category',
                'tags',
                'created_at'
            ]
        ]);
});

test('can get categories', function () {
    Category::factory()->create();

    $response = $this->get('/api/categories');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'image_url',
                    'articles_count'
                ]
            ]
        ]);
});

test('can get tags', function () {
    Tag::factory()->create();

    $response = $this->get('/api/tags');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug'
                ]
            ]
        ]);
});

test('can login', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user',
            'token'
        ]);
});

test('can create article when authenticated', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->post('/api/articles', [
        'title' => 'Test Article',
        'text' => 'Test content',
        'category_id' => $category->id,
        'published_at' => true
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'content',
                'image_url',
                'published_at',
                'category',
                'tags',
                'created_at'
            ]
        ]);
});

test('can update article when authenticated', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user, 'api');

    $response = $this->put('/api/articles/' . $article->id, [
        'title' => 'Updated Title',
        'text' => 'Updated content'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'content',
                'image_url',
                'published_at',
                'category',
                'tags',
                'created_at'
            ]
        ]);
});

test('can delete article when authenticated', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user, 'api');

    $response = $this->delete('/api/articles/' . $article->id);

    $response->assertStatus(204);
});

test('can create category when authenticated', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->post('/api/categories', [
        'name' => 'Test Category'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'image_url',
                'articles_count'
            ]
        ]);
});

test('can update category when authenticated', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->put('/api/categories/' . $category->id, [
        'name' => 'Updated Category'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'image_url',
                'articles_count'
            ]
        ]);
});

test('can delete category when authenticated', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->delete('/api/categories/' . $category->id);

    $response->assertStatus(204);
});

test('can create tag when authenticated', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->post('/api/tags', [
        'name' => 'Test Tag'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug'
            ]
        ]);
});

test('can update tag when authenticated', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->put('/api/tags/' . $tag->id, [
        'name' => 'Updated Tag'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug'
            ]
        ]);
});

test('can delete tag when authenticated', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->delete('/api/tags/' . $tag->id);

    $response->assertStatus(204);
});
