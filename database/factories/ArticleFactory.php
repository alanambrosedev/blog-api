<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'slug' => Str::slug($title),
            'text' => fake()->paragraph(3, true),
            'image' => "https://picsum.photos/640/480",
            'published_at' => false,
            'is_featured' => false
        ];
    }

    public function published()
    {
        return $this->state(fn() => [
            'published_at' => true
        ]);
    }

    public function draft()
    {
        return $this->state(fn() => [
            'published_at' => false
        ]);
    }

    public function featured()
    {
        return $this->state(fn() => [
            'is_featured' => true
        ]);
    }
}
