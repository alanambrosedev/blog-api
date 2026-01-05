<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345678',
        ]);

        $tech = Category::factory()->create(['name' => 'Technology', 'slug' => 'technology']);
        $life = Category::factory()->create(['name' => 'Lifestyle', 'slug' => 'lifestyle']);
        $news = Category::factory()->create(['name' => 'News', 'slug' => 'news']);

        $tags = Tag::factory(10)->create();

        Article::factory(10)
            ->for($tech)
            ->create()
            ->each(function ($article) use ($tags) {
                $article->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        Article::factory(10)
            ->for($news)
            ->create()
            ->each(function ($article) use ($tags) {
                $article->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        Article::factory(10)
            ->for($life)
            ->create()
            ->each(function ($article) use ($tags) {
                $article->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
    }
}
