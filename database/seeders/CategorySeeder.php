<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
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
        $user = User::first() ?? User::factory()->create();

        $categories = ['Tech', 'Lifestyle', 'Travel', 'Business', 'Health'];

        foreach ($categories as $categoryName) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
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
