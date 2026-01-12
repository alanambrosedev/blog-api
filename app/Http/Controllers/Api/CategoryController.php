<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index()
    {
        return CategoryResource::collection(Category::withCount('articles')->get());
    }
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->createCategory($request->validated());
        return new CategoryResource($category);
    }
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updated = $this->service->updateCategory($category, $request->validated());
        return new CategoryResource($updated);
    }
    public function destroy(Category $category)
    {
        $this->service->deleteCategory($category);
        return response()->noContent();
    }
}
