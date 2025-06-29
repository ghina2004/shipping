<?php

namespace App\Services\Question;

use App\Models\ShipmentQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    public function getQuestions($categoryId): Collection
    {
        return ShipmentQuestion::query()
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->with(['categories', 'questionOption'])
            ->get();
    }


    public function createQuestion(array $data): ShipmentQuestion
    {
        $questionData = collect($data)->except('category_id', 'options')->toArray();
        return ShipmentQuestion::query()->create($questionData);
    }


    public function createQuestionOptions(ShipmentQuestion $question, array $options): void
    {
        foreach ($options as $option) {
            $question->questionOption()->create([
                'value_ar' => $option['value_ar'] ?? '',
                'value_en' => $option['value_en'] ?? '',
            ]);
        }
    }

    public function clearQuestionOptions(ShipmentQuestion $question): void
    {
        $question->questionOption()->delete();
    }

    public function handleQuestionOptions(ShipmentQuestion $question, array $data): void
    {
        $this->clearQuestionOptions($question);

        if (!empty($data['category_id'])) {
            $categoryIds = is_array($data['category_id']) ? $data['category_id'] : [$data['category_id']];
            $question->categories()->sync($categoryIds);
        }

        if (in_array($data['type'], ['select', 'radio', 'checkbox']) && !empty($data['options'])) {
            $this->createQuestionOptions($question, $data['options']);
        }
    }


    public function updateQuestionData(ShipmentQuestion $question, array $data): void
    {
        $question->update(
            collect($data)->except(['category_id', 'options'])->toArray()
        );
    }

    public function updateQuestion(ShipmentQuestion $question, array $data): ShipmentQuestion
    {
        DB::transaction(function () use ($question, $data) {
            $this->updateQuestionData($question, $data);
            $this->handleQuestionOptions($question, $data);
        });

        return $question->load('questionOption');
    }

    public function createWithOptions(array $data): ShipmentQuestion
    {
        $question = $this->createQuestion($data);

        if (!empty($data['category_id'])) {
            $categoryIds = is_array($data['category_id']) ? $data['category_id'] : [$data['category_id']];
            $question->categories()->sync($categoryIds);
        }

        if (in_array($data['type'], ['select', 'radio', 'checkbox']) && !empty($data['options'])) {
            $this->createQuestionOptions($question, $data['options']);
        }

        return $question->load(['questionOption', 'categories']);
    }



    public function deleteQuestion(ShipmentQuestion $question): void
    {
        DB::transaction(function () use ($question) {
            $this->clearQuestionOptions($question);
            $question->delete();
        });
    }
}
