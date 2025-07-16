<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\CreateQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\Question\QuestionResource;
use App\Models\ShipmentQuestion;
use App\Services\Question\QuestionService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    use ResponseTrait;

    public function __construct(protected QuestionService $questionService) {}

    public function show(int $categoryId): JsonResponse
    {
        $question = $this->questionService->getQuestions($categoryId);

        return self::success([
            'question' => QuestionResource::collection($question)
        ], __('question.shown'));
    }

    public function showQuestion(int $questionId): JsonResponse
    {
        $question = $this->questionService->getQuestion($questionId);

        return self::Success([
            'question' => new QuestionResource($question)
        ], __('question.shown'));
    }

    public function store(CreateQuestionRequest $request): JsonResponse
    {
        $question = $this->questionService->createWithOptions($request->validated());

        return self::Success([
            'question' => new QuestionResource($question)
        ], __('question.created'));
    }

    public function update(UpdateQuestionRequest $request, ShipmentQuestion $question): JsonResponse
    {
        $question = $this->questionService->updateQuestion($question, $request->validated());

        return self::Success([
            'question' => new QuestionResource($question)
        ], __('question.updated'));
    }

    public function destroy(ShipmentQuestion $question): JsonResponse
    {
        $this->questionService->deleteQuestion($question);

        return self::Success([], __('question.deleted'));
    }

}
