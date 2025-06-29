<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function __construct(protected CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getCategories();

        return self::Success([
            'categories' => CategoryResource::collection($categories)
        ],__('question.shown'));
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);

        return self::Success([
            'category' => new CategoryResource($category)
        ],__('question.shown'));
    }

    public function showWithQuestions(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryWithQuestions($id);

        return self::Success([
            'category' => new CategoryResource($category)
        ],__('question.shown'));
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());

        return self::Success([
            'category' => new CategoryResource($category)
        ], __('category.created'));
    }

    public function update(UpdateCategoryRequest $request, $categoryId): JsonResponse
    {

        $category = $this->categoryService->updateCategory($categoryId, $request->validated());

        return self::Success([
            'category' => new CategoryResource($category)
        ], __('category.updated'));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->deleteCategory($id);

        return self::Success([], __('category.deleted'));
    }
}
