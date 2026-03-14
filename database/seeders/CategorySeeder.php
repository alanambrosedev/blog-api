<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user we created in DatabaseSeeder
        // Or create one if it doesn't exist for safety
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        $categories = ['Tech', 'Lifestyle', 'Travel', 'Business', 'Health'];

        foreach ($categories as $categoryName) {
            $category = Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryName)],
                ['name' => $categoryName]
            );

            Article::factory()
                ->count(5)
                ->published()
                ->for($category)
                ->for($user)
                ->create();
        }
    }
}
