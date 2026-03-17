<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::all()->each(function ($article) {
            if (!$article->image) {
                Image::create([
                    'url' => 'articles/' . fake()->uuid() . '.jpg',
                    'imageable_type' => Article::class,
                    'imageable_id' => $article->id,
                ]);
            }
        });

        Category::all()->each(function ($category) {
            if (!$category->image) {
                Image::create([
                    'url' => 'categories/' . fake()->uuid() . '.jpg',
                    'imageable_type' => Category::class,
                    'imageable_id' => $category->id,
                ]);
            }
        });
    }
}
