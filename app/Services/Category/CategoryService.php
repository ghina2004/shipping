<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function getCategories(): Collection
    {
        return Category::all();
    }

    public function getCategoryById(int $categoryId): Category
    {
        return Category::query()->findOrFail($categoryId);
    }

    public function getCategoryWithQuestions(int $categoryId): Category
    {
        return Category::with(['ShipmentQuestions.questionOption'])->findOrFail($categoryId);
    }

    public function createCategory($request)
    {
        return Category::query()->create($request);
    }

    public function updateCategory($categoryId, $request)
    {
        $category = Category::query()->findOrFail($categoryId);
        $category->update($request);
        return $category;

    }

    public function deleteCategory($categoryId): void
    {
        Category::query()->findOrFail($categoryId)->delete();
    }
}
