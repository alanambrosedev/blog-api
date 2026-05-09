<?php

use App\Models\Category;

it('generates a slug from the name', function () {
    $category = Category::create(['name' => 'Web Development']);
    expect($category->slug)->toBe('web-development');
});
